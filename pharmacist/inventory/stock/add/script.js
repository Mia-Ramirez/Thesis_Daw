

const modal = document.getElementById('supplierModal');

function checkSelection() {
    const dropdown = document.getElementById('supplier-option');
    const selectedOption = dropdown.options[dropdown.selectedIndex];
    const selectedValue = selectedOption.value;
    const editSupplierButton = document.getElementById('edit_supplier_button');

    editSupplierButton.style.display = "none";
    
    // If the selected value is "showModal", display the modal
    if (selectedValue === "new_supplier") {
        openModal('add');
        
    } else if (selectedValue !== "") {
        editSupplierButton.style.display = "block";
        editSupplierButton.addEventListener("click", (event) => {
            openModal('edit', selectedValue, selectedOption.text);
        });
    };
};

// Open the modal with appropriate action (Add or Edit)
function openModal(action, id = '', name = '') {
    document.getElementById('supplier-option').removeAttribute('required');
    document.getElementById('cost_input').removeAttribute('required');
    document.getElementById('reference_number_input').removeAttribute('required');
    document.getElementById('quantity_input').removeAttribute('required');
    document.getElementById('expiration_date_input').removeAttribute('required');

    document.getElementById('supplierName').setAttribute('required', 'true');

    modal.style.display = 'flex';
    
    document.getElementById('supplierId').value = id;
    document.getElementById('supplierName').value = name;
    
    if (action === 'edit') {
        document.getElementById('modalTitle').innerText = 'Edit Supplier';
    } else {
        document.getElementById('modalTitle').innerText = 'Add Supplier';
    }
}

// Close the modal
function closeModal() {
    // const dropdown = document.getElementById('supplier-option');
    modal.style.display = 'none';

    document.getElementById('supplier-option').setAttribute('required', 'true');
    document.getElementById('cost_input').setAttribute('required', 'true');
    document.getElementById('reference_number_input').setAttribute('required', 'true');
    document.getElementById('quantity_input').setAttribute('required', 'true');
    document.getElementById('expiration_date_input').setAttribute('required', 'true');

    document.getElementById('supplierName').removeAttribute('required');
}
