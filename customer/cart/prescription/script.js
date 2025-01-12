
// function getDataIndexByValue(value) {
//     const option = document.querySelector(`#prescription-option option[value="${value}"]`);
//     return option ? option.getAttribute('data-index') : null;
// }

function showPrescriptionModal(prescription_id, product_name, selected_option) {
    
    var selectedOption = document.getElementById('prescription-option')

    selectedOption.value = selected_option;
    // var index = getDataIndexByValue(selected_option);

    var modal_header = document.getElementById("modal_header");
    var line_id = document.getElementById("line_id");
    
    line_id.value = prescription_id;
    modal_header.innerText = 'Upload Prescription for ' + product_name;

    var modal = document.getElementById("prescriptionModal");
    modal.style.display = "block";

};

document.getElementById('prescription-option').addEventListener('change', function(event) {
    // Get the selected option
    var selectedOption = event.target.options[event.target.selectedIndex];
    
    // Get the data-index attribute
    var index = selectedOption.getAttribute('data-index');
    
    // Do something with the index value
    const option = selectedOption.value;

    const prescriptionPhoto = document.getElementById("img_photo");

    const prescriptionNameInput = document.getElementById("prescription_name");
    const prescriptionNamePar = document.getElementById("p_prescription_name");
    const prescriptionFileInput = document.getElementById("prescription_file");
    const prescriptionDiv = document.getElementById("div_prescription");
    const selectImageVerbiage = document.getElementById("span_image_text");

    const applyButton = document.getElementById("button_apply");
    
    // button_apply
    if (option === "new") {
        prescriptionDiv.style.display = "block";
        prescriptionNamePar.style.display = "block";
        prescriptionNameInput.disabled = false;
        prescriptionFileInput.disabled = false;
        selectImageVerbiage.style.display = "block";
        applyButton.disabled = false;
        applyButton.style.backgroundColor = "green";
        prescriptionPhoto.src = "";
        prescriptionFileInput.className = "prescription_photo";

    } else if (option === ""){
        prescriptionDiv.style.display = "none";
        prescriptionNamePar.style.display = "none";
        prescriptionNameInput.disabled = true;
        prescriptionFileInput.disabled = true;
        selectImageVerbiage.style.display = "none";
        applyButton.disabled = true;
        applyButton.style.backgroundColor = "gray";
        prescriptionPhoto.src = "";
        prescriptionFileInput.className = "prescription_photo";

    } else {
        prescriptionDiv.style.display = "block";
        prescriptionNamePar.style.display = "none";
        prescriptionNameInput.disabled = true;
        prescriptionFileInput.disabled = true;
        selectImageVerbiage.style.display = "none";
        applyButton.disabled = false;
        applyButton.style.backgroundColor = "green";
        prescriptionPhoto.src = prescriptions[index].prescriptionPhoto;
        prescriptionFileInput.className = "loaded_prescription_photo";
    };

});


function previewImage(event) {
    const file = event.target.files[0];
    
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) { 
            const img = document.getElementById('img_photo');
            img.src = e.target.result;

            const span = document.getElementById('span_image_text');
            span.style.display = "none";
        };
        reader.readAsDataURL(file);
    }
};


var proceedButton = document.getElementById('proceed_button');

if (enableProceedButton == '1'){
    proceedButton.disabled = false;
    proceedButton.style.backgroundColor = "green";
} else {
    proceedButton.disabled = true;
    proceedButton.style.backgroundColor = "gray";
};