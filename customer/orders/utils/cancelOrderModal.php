<div class="custom-modal" id="cancelOrderModal">
    <div class="modal-content">
        <h1 id="modal_header">Order</h1>
        <p id="modal_message">Are you sure you want to cancel this Order?</p>
        <form action="process.php" method="POST">
            <input id="order_id" type="hidden" name="order_id">
            <div class="input-group">
                <label for="remarks">Reason for Cancellation</label>
                <input type="text" name="remarks" required>
            </div>
            <button id="button_yes" type="submit" name="action" class="modal-button yes-button" value="cancel_order">Submit</button>
            <button type="button" class="modal-button no-button" onclick="onNo('cancelOrderModal')">Close</button>
        </form>
    </div>
</div>