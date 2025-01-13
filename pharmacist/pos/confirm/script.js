const amountInput = document.getElementById('amount');
const confirmButton = document.getElementById('confirm_transaction');
const changeSpan = document.getElementById('change');
const totalSpan = document.getElementById('total');

// Show suggestions when typing
amountInput.addEventListener('input', function() {
    const amount = amountInput.value;
    let confirm_button_is_disabled = false;
    if (amount) {
        let change = amount - totalSpan.innerText;
        if (change < 0){
            confirm_button_is_disabled = true;
        };
        changeSpan.innerText = change;
    } else {
        confirm_button_is_disabled = true;
        changeSpan.innerText = null;
    };

    if (confirm_button_is_disabled){
        confirmButton.disabled = true;
        confirmButton.style.backgroundColor = "gray";
        
    } else {
        confirmButton.disabled = false;
        confirmButton.style.backgroundColor = "green";
    }
});