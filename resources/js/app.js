import './bootstrap';

const account = document.querySelector('meta[name="account"]').content;
const DETACH_PRODUCT_URL = "/account/product";

Echo.channel('my-channel-test')
    .listen('.my-event-test', (e) => {
        console.log('event fired');
        alert('event fired');
    });

Echo.channel('product-scanned-channel-'+ account)
    .listen('.add-product', (e) => {
        alert('product added: ' + '')
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

            document.getElementById('deleted-products-list').appendChild(container);
        }
    });

function createDeleteProductForm(id) {
    // Create a form element
    const form = document.createElement('form');
    form.method = 'POST';
    form.action = DETACH_PRODUCT_URL;
    form.addEventListener('submit', (event) => {
        event.preventDefault(); // prevent the default form submission behavior
        const confirmDelete = confirm('Weet u zeker dat u dit wilt verwijderen?'); // ask for confirmation
        if (confirmDelete) {
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
            form.submit(); // submit the form
        }
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
