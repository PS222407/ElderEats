import './bootstrap';
import Swal from 'sweetalert2';
import 'flowbite';

const csrf = document.querySelector('meta[name="csrf-token"]').content;
const account = document.querySelector('meta[name="account"]').content;

// set the modal menu element
const $targetEl = document.getElementById('modalEl');

// options with default values
const options = {
    backdrop: 'static',
    backdropClasses: 'bg-gray-900 bg-opacity-50 fixed inset-0 z-40',
    closable: true,
};
const modal = new Modal($targetEl, options);
const addProductModal = new Modal(document.getElementById('addProductModalEL'), options);
const addProductModalShoppingList = new Modal(document.getElementById('addProductModalShoppingListEL'), options);

const closeDeleteProductsButton = document.getElementById('close-delete-products-button');
const closeDeleteProductsButton2 = document.getElementById('close-delete-products-button2');
closeDeleteProductsButton?.addEventListener('click', () => modal.hide());
closeDeleteProductsButton2?.addEventListener('click', () => modal.hide());
const closeAddProductsButton = document.getElementById('close-add-products-button');
const closeAddProductsButton2 = document.getElementById('close-add-products-button2');
closeAddProductsButton?.addEventListener('click', () => addProductModal.hide());
closeAddProductsButton2?.addEventListener('click', () => addProductModal.hide());

const closeAddProductsButton_S = document.getElementById('close-add-products-button-s');
const closeAddProductsButton2_S = document.getElementById('close-add-products-button2-s');
closeAddProductsButton_S?.addEventListener('click', () => addProductModalShoppingList.hide());
closeAddProductsButton2_S?.addEventListener('click', () => addProductModalShoppingList.hide());
function playSound(name) {
    if (soundFeatureStatus()) {
        let audio = new Audio(`mp3/${name}.mp3`);
        audio.volume = 1;
        audio.play();
    }
}

Echo.channel('product-scanned-channel-' + account)
    .listen('.add-product', (e) => {
        if (e.productFound) {
            Swal.fire({
                allowOutsideClick: false,
                icon: "success",
                title: "Product toegevoegd",
                timer: 1500,
                showConfirmButton: false,
            })
        } else {
            Swal.fire({
                allowOutsideClick: false,
                icon: "error",
                title: "Product naam ontbreekt",
                html: "" +
                    "<form action='/products' method='post'>" +
                    "   <input type='hidden' name='_token' value='" + csrf + "' />" +
                    "   <input type='hidden' name='ean' value='" + e.ean + "' />" +
                    "   <input type='hidden' name='amount' value='" + e.amount + "' />" +
                    "   <input type='text' name='name' />" +
                    "   <button type='submit' class='btn-primary'>Opslaan</button>" +
                    "</form>" +
                    "",
                showConfirmButton: false,
                showCancelButton: true,
                cancelButtonText: 'Annuleer',
            })
        }
        Livewire.emit('livewireRefreshProductListHomepage');
    })
    .listen('.delete-product', (e) => {
        console.log(e.pusherId);
        axios.get(`api/v1/pusher/${e.pusherId}`)
            .then(function (response) {
                console.log(response)
                if (response.status === 200) {
                    const list = document.getElementById('deleted-products-list');
                    if (!list) return;
                    list.innerHTML = "";

                    let products = JSON.parse(response.data.pusherContent);

                    for (let i = 0; i < products.length; i++) {
                        let item = products[i];

                        if (i === 0) {
                            const text = document.createElement('h2');
                            text.innerText = item.name + ' ' + item.brand + ' ' + item.quantity_in_package;
                            list.appendChild(text);
                        }

                        const container = document.createElement('div');
                        const el = document.createElement('div');

                        el.innerText = dateStringToHumanNL(item.pivot.expiration_date);
                        const form = createDeleteProductForm(item.pivot.id);

                        container.appendChild(el);
                        container.appendChild(form);

                        list.appendChild(container);
                    }

                    if (products.length <= 0) {
                        list.innerHTML = "Er zijn geen producten om te verwijderen"
                    }

                    modal.show();
                }
            })
            .catch(function (error) {
                console.log(error);
            });
    });

Echo.channel('user-account-requests-' + account)
    .listen('.user-incoming', (e) => {
        Swal.fire({
            icon: "warning",
            title: "Verzoek voor verbinding",
            text: e.userName + " wil verbinden met dit account",
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'JA',
            cancelButtonText: "NEE",
        }).then((result) => {
            if (result.isConfirmed) {
                axios.post('/account/attach-user', {
                    userId: e.userId,
                })
                .then(function (response) {
                    if (response.status === 200) {
                        successAlert();
                        setTimeout(function () {
                            hideCode();
                        }, 1500);
                    }
                })
                .catch(function (error) {
                    console.log(error);
                });
            }
        });
    });

function createDeleteProductForm(pivotId) {
    const form = document.createElement('form');
    form.method = 'POST';
    form.action = '/products/' + pivotId + '/detach';

    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    const inputMethod = document.createElement('input');
    inputMethod.type = 'hidden';
    inputMethod.name = '_method';
    inputMethod.value = 'DELETE';
    const inputToken = document.createElement('input');
    inputToken.type = 'hidden';
    inputToken.name = '_token';
    inputToken.value = csrfToken;

    form.appendChild(inputMethod);
    form.appendChild(inputToken);

    const deleteButton = document.createElement('button');
    deleteButton.type = 'button';
    deleteButton.textContent = 'Verwijder';
    deleteButton.addEventListener('click', () => {
        playSound('alert');

        Swal.fire({
            allowOutsideClick: false,
            title: 'Verwijderen?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'JA',
            cancelButtonText: "NEE",
        }).then((result) => {
            if (result.isConfirmed) {
                form.submit();
            }
        });
    });

    form.appendChild(deleteButton);

    return form;
}

const showAddToShoppingList = document.getElementById('show-add-to-shopping-list')
if (showAddToShoppingList) {
    Swal.fire({
        allowOutsideClick: false,
        title: 'Toevoegen aan boodschappenlijst?',
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'JA',
        cancelButtonText: 'NEE',
        timer: 25000,
        timerProgressBar: true,
    }).then((result) => {
        if (result.isConfirmed) {
            const ean = showAddToShoppingList.getAttribute('ean');
            axios.post('/products/' + ean + '/add-to-shopping-list')
            .then(function (response) {
                if (response.status === 200) {
                    successAlert();
                }
            })
            .catch(function (error) {
                console.log(error);
            });
        }
    })
}

function dateStringToHumanNL(date) {
    if (!date) return "onbekend";

    const dateObject = new Date(date);
    const options = {day: 'numeric', month: 'long', year: 'numeric', timeZone: 'UTC'};

    return dateObject.toLocaleDateString('nl-NL', options); // Output: "20 april 2023"
}

const showSuccessAlert = document.getElementById('show-success-alert');
if (showSuccessAlert) {
    successAlert();
}

const showErrorAlert = document.getElementById('show-error-alert');
if (showErrorAlert) {
    errorAlert();
}

function successAlert() {
    Swal.fire({
        icon: "success",
        title: 'Succes',
        showConfirmButton: false,
        timer: 1500,
    });
}

function errorAlert() {
    Swal.fire({
        icon: "error",
        title: 'Fout',
        showConfirmButton: false,
        timer: 1500,
    });
}

window.hideCode = function() {
    document.getElementById('display-code').innerHTML = '';
    document.getElementById('display-qrcode').innerHTML = '';
    document.getElementById('close-sidenav-button').click();
}

document.addEventListener('delete-button-pressed', function (e) {
    playSound('alert');

    Swal.fire({
        allowOutsideClick: false,
        title: 'Verwijderen?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'JA',
        cancelButtonText: "NEE",
    }).then((result) => {
        if (result.isConfirmed) {
            const form = document.getElementById(e.detail.formId);
            form.submit();
        }
    });
})

document.addEventListener('add-product-button-pressed', function (e) {
    playSound('clashKingTile');

    addProductModal.show();
});
document.addEventListener('add-product-shoppinglist-button-pressed', function (e) {
    playSound('clashKingTile');

    addProductModalShoppingList.show();
});
document.addEventListener('add-non-existing-product-button-pressed', function (e) {
    playSound('clashKingTile');

    Swal.fire({
        allowOutsideClick: false,
        title: 'Voeg product toe',
        html:
            //Add product by name.
            "<form action='/add-manually-product' method='post'>" +
            "   <input type='hidden' name='_token' value='" + csrf + "' />" +
            "   <p class='mt-2'>Voer de naam in</p>" +
            "   <input placeholder='Product naam' type='text' name='name' />" +
            "   <button type='submit' class='btn-primary mt-4'>Opslaan</button>" +
            "</form>",
        showConfirmButton: false,
        showCancelButton: true,
        cancelButtonText: 'Annuleer',
    })
});

document.addEventListener('add-non-existing-product-button-pressed-shoppingList', function (e) {
    playSound('clashKingTile');

    Swal.fire({
        allowOutsideClick: false,
        title: 'Voeg product toe',
        html:
            "<form action='/add-manually-product-shoppinglist' method='post'>" +
            "   <input type='hidden' name='_token' value='" + csrf + "' />" +
            "   <input type='text' name='name' />" +
            "   <button type='submit' class='btn-primary'>Opslaan</button>" +
            "</form>",
        showConfirmButton: false,
        showCancelButton: true,
        cancelButtonText: 'Annuleer',
    })
});

window.toggleSound = function () {
    setCookie('sound', !soundFeatureStatus(), 9999);

    if (getCookie('sound') === "false") {
        document.getElementById('sound-off').style.display = "block";
        document.getElementById('sound-on').style.display = "none";
    } else {
        document.getElementById('sound-off').style.display = "none";
        document.getElementById('sound-on').style.display = "block";
    }
}

function soundFeatureStatus() {
    if (!cookieExists('sound')) {
        return true;
    } else {
        return getCookie('sound') === 'true';
    }
}

function setCookie(cname, cvalue, exdays) {
    const d = new Date();
    d.setTime(d.getTime() + (exdays * 24 * 60 * 60 * 1000));
    let expires = "expires=" + d.toUTCString();
    document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
}

function getCookie(cname) {
    let name = cname + "=";
    let ca = document.cookie.split(';');
    for (let i = 0; i < ca.length; i++) {
        let c = ca[i];
        while (c.charAt(0) === ' ') {
            c = c.substring(1);
        }
        if (c.indexOf(name) === 0) {
            return c.substring(name.length, c.length);
        }
    }
    return "";
}

function cookieExists(cname) {
    return getCookie(cname) !== "";
}

window.productDetailModal = function (id) {
    const el = document.getElementById(id);
    el.showModal();
}
