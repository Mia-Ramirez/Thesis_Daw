<div class="custom-modal" id="yesNoModal">
    <div class="modal-content">
        <p id="modal_message">Do you want to proceed?</p>
        <form action="process.php" method="POST">
            <input id="modal_value" type="hidden" name="input_name">
            <button id="button_yes" type="submit" name="action" class="modal-button yes-button" value="button_value">Yes</button>
            <button type="button" class="modal-button no-button" onclick="onNo('yesNoModal')">No</button>
        </form>
    </div>
</div>