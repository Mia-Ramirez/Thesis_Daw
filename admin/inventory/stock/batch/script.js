

// Hide the modal
function closeModal(modalID) {
    var modal = document.getElementById(modalID);
    modal.style.display = "none";
};

// Action when 'No' is clicked
function onNo(modalID) {
    closeModal(modalID);
};

function showDisposeStockModal(index) {
    const batch_reference_number = batches[index].referenceNumber;
    const batch_max = batches[index].maxQuantity;
    const batch_id = batches[index].batchID;
    const product_id = batches[index].productID;

    var modal_header = document.getElementById("modal_header");
    
    modal_header.innerHTML = 'Batch:<br/>' + batch_reference_number;
    
    var modal_batch_reference_name_input = document.getElementById("batch_reference_name");
    modal_batch_reference_name_input.value = batch_reference_number;

    var modal_batch_id_input = document.getElementById("batch_id");
    modal_batch_id_input.value = batch_id;

    var modal_product_id_input = document.getElementById("product_id");
    modal_product_id_input.value = product_id;

    var modal_batch_max_input = document.getElementById("disposed_quantity");
    modal_batch_max_input.max = batch_max;

    var modal = document.getElementById("disposeStockModal");
    modal.style.display = "block";

};
