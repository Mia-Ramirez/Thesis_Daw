document.addEventListener('DOMContentLoaded', () => {
    const checkboxes = document.querySelectorAll('.product-checkbox');
    const quantities = document.querySelectorAll('.quantity');
    const discountDropdown = document.getElementById('discount');
    const subtotalElement = document.getElementById('subtotal');
    const discountAmountElement = document.getElementById('discountAmount');
    const totalElement = document.getElementById('total');
    const checkoutButton = document.getElementById('checkout');
    
    let products = [
        { name: "Paracetamol 500mg", price: 10, discountedPrice: 10, quantity: 1, selected: false },
        { name: "Ibuprofen 400mg", price: 15, discountedPrice: 15, quantity: 1, selected: false },
        { name: "Amoxicillin 500mg", price: 25, discountedPrice: 25, quantity: 1, selected: false }
    ];

    // Update product selection based on checkbox
    checkboxes.forEach((checkbox, index) => {
        checkbox.addEventListener('change', () => {
            products[index].selected = checkbox.checked;
            updateSummary();
        });
    });

    // Update quantity and recalculate total
    quantities.forEach((input, index) => {
        input.addEventListener('input', (e) => {
            const value = parseInt(e.target.value);
            if (value >= 1) {
                products[index].quantity = value;
                updateSummary();
            }
        });
    });

    // Update discount selection
    discountDropdown.addEventListener('change', () => {
        updateDiscountedPrices();
        updateSummary();
    });

    // Update discounted prices based on selected discount
    function updateDiscountedPrices() {
        const discountRate = parseFloat(discountDropdown.value);
        products.forEach(product => {
            product.discountedPrice = product.price * (1 - discountRate);
            document.querySelectorAll('.discounted-price')[products.indexOf(product)].textContent = product.discountedPrice.toFixed(2);
        });
    }

    // Calculate and update summary
    function updateSummary() {
        let subtotal = 0;
        products.forEach((product, index) => {
            if (product.selected) {
                const total = product.discountedPrice * product.quantity;
                subtotal += total;
                document.querySelectorAll('.total')[index].textContent = total.toFixed(2);
            } else {
                document.querySelectorAll('.total')[index].textContent = '0';
            }
        });

        const discountRate = parseFloat(discountDropdown.value);
        const discountAmount = subtotal * discountRate;
        const total = subtotal - discountAmount;

        subtotalElement.textContent = subtotal.toFixed(2);
        discountAmountElement.textContent = discountAmount.toFixed(2);
        totalElement.textContent = total.toFixed(2);
    }

    // Checkout button functionality
    checkoutButton.addEventListener('click', () => {
        alert(`Checkout Successful! Total: ₱${totalElement.textContent}`);
    });

    // Initial update for discount and summary
    updateDiscountedPrices();
    updateSummary();
});
