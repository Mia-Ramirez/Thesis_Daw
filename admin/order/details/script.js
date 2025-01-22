

// Hide the modal
function closeModal(modalID) {
    var modal = document.getElementById(modalID);
    modal.style.display = "none";
};

// Action when 'No' is clicked
function onNo(modalID) {
    closeModal(modalID);
};

function showCancelOrderModal(order_id, order_reference_number) {

    var modal_header = document.getElementById("modal_header");
    
    modal_header.innerHTML = 'Order:<br/>' + order_reference_number;
    
    var modal_input = document.getElementById("order_id");
    modal_input.value = order_id;

    var modal = document.getElementById("cancelOrderModal");
    modal.style.display = "block";

};

const ready_products = document.querySelectorAll('.product-checkbox');
const readyForPickupButton = document.getElementById("ready_for_pickup");

function checkAllChecked() {
    const allChecked = Array.from(ready_products).every(checkbox => checkbox.checked);
    if (allChecked){
        readyForPickupButton.disabled = false;
        readyForPickupButton.style.backgroundColor = "green";
        readyForPickupButton.classList.remove('disabled');
    } else {
        readyForPickupButton.disabled = true;
        readyForPickupButton.classList.add('disabled');
    };
    
};

// Attach event listeners to all checkboxes
ready_products.forEach(checkbox => {
    checkbox.addEventListener('change', checkAllChecked);
});

// Initial check when the page loads
checkAllChecked();