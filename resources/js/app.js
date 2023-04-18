import './bootstrap';
import Swal from 'sweetalert2';
import 'flowbite';

const csrf = document.querySelector('meta[name="csrf-token"]').content;
const account = document.querySelector('meta[name="account"]').content;
const DETACH_PRODUCT_URL = "/account/product";

// set the modal menu element
const $targetEl = document.getElementById('modalEl');

// options with default values
const options = {
    backdrop: 'static',
    backdropClasses: 'bg-gray-900 bg-opacity-50 dark:bg-opacity-80 fixed inset-0 z-40',
    closable: true,
};
const modal = new Modal($targetEl, options);

document.getElementById('close-delete-products-button').addEventListener('click', function () {
   modal.hide();
});document.getElementById('close-delete-products-button2').addEventListener('click', function () {
   modal.hide();
});

Echo.channel('product-scanned-channel-'+ account)
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
                html: "<form action='/product/" + e.ean + "' method='post'>" +
                    "   <input type='hidden' name='_token' value='" + csrf + "' />" +
                    "   <input type='text' name='name' />" +
                    "   <button type='submit'>Opslaan</button>" +
                    "</form>" +
                    "",
                showConfirmButton: false,
            })
        }
    }).listen('.delete-product', (e) => {
        const list = document.getElementById('deleted-products-list');
        if (!list) return;
        list.innerHTML = "";

        for (let i = 0; i < e.products.length; i++) {
            let item = e.products[i];

            if (i === 0) {
                const productNameContainer = document.createElement('div');
                const text = document.createElement('div');
                text.innerText = item.name + ' ' + item.brand + ' ' + item.quantity_in_package;
                productNameContainer.appendChild(text);
                list.appendChild(productNameContainer);
            }

            const container = document.createElement('div');
            const el = document.createElement('div');
            const link = document.createElement('a');

            el.innerText = dateStringToHumanNL(item.pivot.expiration_date);
            // link.href = '/' + item.pivot.id;
            // link.innerText = 'Verwijder deze';
            const form = createDeleteProductForm(item.pivot.id);

            container.appendChild(el);
            // container.appendChild(link);
            container.appendChild(form);

            list.appendChild(container);
        }

        if (e.products.length <= 0) {
            list.innerHTML = "Er zijn geen producten om te verwijderen"
        }

        modal.show();
    });

function createDeleteProductForm(id) {
    // Create a form element
    const form = document.createElement('form');
    form.method = 'POST';
    form.action = DETACH_PRODUCT_URL;

    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    const inputMethod = document.createElement('input');
    inputMethod.type = 'hidden';
    inputMethod.name = '_method';
    inputMethod.value = 'DELETE';
    const inputToken = document.createElement('input');
    inputToken.type = 'hidden';
    inputToken.name = '_token';
    inputToken.value = csrfToken;
    const inputPivotId = document.createElement('input');
    inputPivotId.type = 'hidden';
    inputPivotId.name = 'pivot_id';
    inputPivotId.value = id;
    form.appendChild(inputMethod);
    form.appendChild(inputToken);
    form.appendChild(inputPivotId);

    form.addEventListener('submit', (event) => {
        event.preventDefault(); // prevent the default form submission behavior

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
                form.submit(); // submit the form
            }
        })
    });

    // Create a delete button element
    const deleteButton = document.createElement('button');
    deleteButton.type = 'button';
    deleteButton.textContent = 'Verwijder';
    deleteButton.addEventListener('click', () => {
        form.dispatchEvent(new Event('submit')); // dispatch a submit event to the form
    });

    // Append the button to the form
    form.appendChild(deleteButton);

    return form;
}

function dateStringToHumanNL(date) {
    if (!date) return "onbekend";

    const dateObject = new Date(date);
    const options = { day: 'numeric', month: 'long', year: 'numeric', timeZone: 'UTC' };

    return dateObject.toLocaleDateString('nl-NL', options); // Output: "20 april 2023"
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
        timer: 10000,
        timerProgressBar: true,
    }).then((result) => {
        if (result.isConfirmed) {
            axios.post('/account/add-to-shopping-list', {
                ean: showAddToShoppingList.getAttribute('ean'),
            })
        }
    })
}
