<div class="custom-modal" id="disposeStockModal">
    <div class="modal-content">
        <h1 id="modal_header">Batch</h1>
        <p id="modal_message">Are you sure you want to dispose this Batch?</p>
        <form action="process.php" method="POST">
            <input id="batch_id" type="hidden" name="batch_id">
            <input id="product_id" type="hidden" name="product_id">
            <input id="batch_reference_name" type="hidden" name="batch_reference_name">
            <div class="input-group">
                <label for="disposed_quantity">Quantity to Dispose</label>
                <input id="disposed_quantity" type="number" name="disposed_quantity" required>
            </div>
            <button id="button_yes" type="submit" name="action" class="modal-button yes-button" value="dispose_stock">Submit</button>
            <button type="button" class="modal-button no-button" onclick="onNo('disposeStockModal')">Close</button>
        </form>
    </div>
</div>