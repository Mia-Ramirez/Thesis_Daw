document.addEventListener('DOMContentLoaded', () => {
    const checkboxes = document.querySelectorAll('.product-checkbox');
    const quantities = document.querySelectorAll('.quantity');
    const discountDropdown = document.getElementById('discount');
    const subtotalElement = document.getElementById('subtotal');
    const discountAmountElement = document.getElementById('discountAmount');
    const totalElement = document.getElementById('total');
    const checkoutButton = document.getElementById('checkout');

    const selectedIDs = document.getElementById('selected_ids');
    const selectedQty = document.getElementById('selected_items_qty');
    const selectedDiscount = document.getElementById('selected_discount');
    
    let prescription_is_required = false;

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
        let selected_discount = discountDropdown.value.split("_");
    
        const discountRate = parseFloat(selected_discount[1]);
        const discountLabel = selected_discount[0];
        selectedDiscount.value = discountLabel;

        products.forEach(product => {
            if (product.applicableDiscounts.includes(discountLabel) || product.applicableDiscounts === "Both"){
                product.discountedPrice = product.price * (1 - discountRate);
                document.querySelectorAll('.discounted-price')[products.indexOf(product)].textContent = '₱'+product.discountedPrice.toFixed(2);
            } else {
                product.discountedPrice = product.price;
                document.querySelectorAll('.discounted-price')[products.indexOf(product)].textContent = '₱'+product.discountedPrice;
            };            
        });
    }

    // Calculate and update summary
    function updateSummary() {
        let subtotal = 0;
        let discountAmount = 0;

        let selected_ids = '';
        let selected_qty = '';
        
        let checkout_is_disabled = true;
        prescription_is_required = false;

        products.forEach((product, index) => {
            if (product.selected === true) {
                checkout_is_disabled = false;
                if (selected_ids === ''){
                    selected_ids = product.lineID.toString();
                    selected_qty = product.quantity.toString();
                } else {
                    selected_ids += "," + product.lineID.toString();
                    selected_qty += "," + product.quantity.toString();
                };

                if (product.prescriptionIsRequired == 1){
                    prescription_is_required = true;
                };

                const total = product.price * product.quantity;
                const discount = total - (product.discountedPrice * product.quantity);
                subtotal += total;
                discountAmount += discount;
                document.querySelectorAll('.total')[index].textContent = '₱'+total.toFixed(2);
                
            } else {
                document.querySelectorAll('.total')[index].textContent = '₱0';
            };
        });
        
        const total = subtotal - discountAmount;

        subtotalElement.textContent = subtotal.toFixed(2);
        discountAmountElement.textContent = discountAmount.toFixed(2);
        totalElement.textContent = total.toFixed(2);

        selectedIDs.value = selected_ids;
        selectedQty.value = selected_qty;

        checkoutButton.disabled = checkout_is_disabled;
        if (checkout_is_disabled === false){
            checkoutButton.style.backgroundColor = "green";
        } else {
            checkoutButton.style.backgroundColor = "gray";
        };
    }

    // Checkout button functionality
    checkoutButton.addEventListener('click', () => {
        if (prescription_is_required === true){
            alert(`Medicine(s) with required Prescription detected, please select the respective Prescription`);
        } else {
            alert(`Checkout Successful! Please review your order and confirm on the next page`);
        };
    });

    // Initial update for discount and summary
    updateDiscountedPrices();
    updateSummary();
});
