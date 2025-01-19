function compareDates(d1, d2) {
    // Convert the 'Y-m-d' strings to Date objects
    // const d1 = new Date(date1);
    // const d2 = new Date(date2);

    // Compare the dates (ignoring time)
    if (d1.getFullYear() !== d2.getFullYear() || d1.getMonth() !== d2.getMonth() || d1.getDate() !== d2.getDate()) {
        if (d1 > d2) {
            return 2;//`${d1} is later than ${d2}`;
        } else {
            return 0;//`${d1} is earlier than ${d2}`;
        }
    } else {
        return 1;//`${d1} is the same as ${d2}`;
    }
};

function setActivePage(active_element_id) {
    const nav_link_elements = document.querySelectorAll('.menu');
    nav_link_elements.forEach(element => {
        element.className = 'menu';
    });

    document.getElementById(active_element_id).classList.add("active");
    
};

function openFullscreen(imgElement) {
    if (imgElement.requestFullscreen) {
        imgElement.requestFullscreen();  // Chrome, Firefox, Safari, Opera
    } else if (imgElement.mozRequestFullScreen) { // Firefox
        imgElement.mozRequestFullScreen();
    } else if (imgElement.webkitRequestFullscreen) { // Chrome, Safari and Opera
        imgElement.webkitRequestFullscreen();
    } else if (imgElement.msRequestFullscreen) { // IE/Edge
        imgElement.msRequestFullscreen();
    }
};

function adjustInputValue(input) {
    let maxValue = parseInt(input.max);
    let currentValue = parseInt(input.value);
    var step = parseFloat(input.step);
    
    if (!isNaN(currentValue)) {
        var adjustedValue = Math.round(currentValue / step) * step;
        
        input.value = adjustedValue;
        currentValue = adjustedValue;
    };

    if (isNaN(currentValue)) {
        input.value = '';
    } else if (currentValue > maxValue) {
        input.value = maxValue;
    };
};