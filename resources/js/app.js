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

const closeDeleteProductsButton = document.getElementById('close-delete-products-button');
const closeDeleteProductsButton2 = document.getElementById('close-delete-products-button2');
closeDeleteProductsButton?.addEventListener('click', function () {
    modal.hide();
});
closeDeleteProductsButton2?.addEventListener('click', function () {
    modal.hide();
});

console.log(account)
Echo.channel('product-scanned-channel-' + account)
    .listen('.add-product', (e) => {
        console.log('hallo');
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
                    "   <input type='text' name='name' />" +
                    "   <button type='submit'>Opslaan</button>" +
                    "</form>" +
                    "",
                showConfirmButton: false,
            })
        }
        Livewire.emit('livewireRefreshProductListHomepage');
    })
    .listen('.delete-product', (e) => {
        const list = document.getElementById('deleted-products-list');
        if (!list) return;
        list.innerHTML = "";

        for (let i = 0; i < e.products.length; i++) {
            let item = e.products[i];

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

        if (e.products.length <= 0) {
            list.innerHTML = "Er zijn geen producten om te verwijderen"
        }

        modal.show();
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
