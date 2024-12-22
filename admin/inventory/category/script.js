// Open the modal with appropriate action (Add or Edit)
function openModal(action, id = '', name = '') {
    document.getElementById('categoryModal').style.display = 'flex';
    document.getElementById('categoryId').value = id;
    document.getElementById('categoryName').value = name;
    
    if (action === 'edit') {
        document.getElementById('modalTitle').innerText = 'Edit Category';
    } else {
        document.getElementById('modalTitle').innerText = 'Add Category';
    }
}

// Close the modal
function closeModal() {
    document.getElementById('categoryModal').style.display = 'none';
}
