
function showCancelOrderModal(order_id, order_reference_number) {

    var modal_header = document.getElementById("modal_header");
    
    modal_header.innerHTML = 'Order:<br/>' + order_reference_number;
    
    var modal_input = document.getElementById("order_id");
    modal_input.value = order_id;

    var modal = document.getElementById("cancelOrderModal");
    modal.style.display = "block";

};
