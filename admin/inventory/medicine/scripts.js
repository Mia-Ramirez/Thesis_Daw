// Script for Photo Operations

function previewImage(event, method) {
    const file = event.target.files[0];
    const container = document.querySelector('.image-container');

    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            if (method === 'add'){
                // Create an image element and set the source to the file
                const img = document.createElement('img');
                img.src = e.target.result;

                // Clear the container and add the image
                const span = document.getElementById('span_image_text');
                span.style.display = "none";
                
                container.appendChild(img);
                
            } else {
                const img = document.getElementById('img_photo');
                img.src = e.target.result;
            };
            
        };
        reader.readAsDataURL(file);
    }
};

// Script for Category Operations
const categoryInput = document.getElementById('category-input');
const categoriesContainer = document.getElementById('categories-container');
const suggestionsList = document.getElementById('suggestions-list');

// Add category on Enter key press
categoryInput.addEventListener('keydown', function(event) {
    if (event.key === 'Enter' && categoryInput.value.trim() !== '') {
        const categoryValue = categoryInput.value.trim();
        if (validCategories.includes(categoryValue) && !selectedCategories.includes(categoryValue)) {
            addCategory(categoryValue);
            selectedCategories.push(categoryValue); // Add the selected category to the list
            categoryInput.value = ''; // Clear input after adding category
            suggestionsList.innerHTML = ''; // Clear suggestions list

            const categoryNames = document.getElementById('category-names');
            categoryNames.value = selectedCategories;
        };
        event.preventDefault(); // Prevent form submission on Enter
    }
});

// Add a category to the container
function addCategory(categoryValue) {
    const categoryElement = document.createElement('div');
    categoryElement.classList.add('category');
    categoryElement.innerHTML = `${categoryValue} <span onclick="removeCategory(this)">×</span>`;
    categoriesContainer.insertBefore(categoryElement, categoryInput);
}

// Remove a category
function removeCategory(categoryElement) {
    const categoryText = categoryElement.parentElement.textContent.trim().slice(0, -1); // Get category text without '×'
    selectedCategories.splice(selectedCategories.indexOf(categoryText), 1); // Remove the category from selectedCategories array
    categoryElement.parentElement.remove(); // Remove the category from the DOM

    const categoryNames = document.getElementById('category-names');
    categoryNames.value = selectedCategories;
}

// Show suggestions when typing
categoryInput.addEventListener('input', function() {
    const query = categoryInput.value.trim().toLowerCase();

    if (query) {
        const filteredCategories = validCategories.filter(category => 
            category.toLowerCase().includes(query) && !selectedCategories.includes(category)
        );
        displaySuggestions(filteredCategories);
    } else {
        suggestionsList.innerHTML = '';
    }
});

// Display filtered suggestions
function displaySuggestions(suggestions) {
    suggestionsList.innerHTML = ''; // Clear previous suggestions
    suggestions.forEach(suggestion => {
        const li = document.createElement('li');
        li.textContent = suggestion;
        li.addEventListener('click', function() {
            addCategory(suggestion);
            selectedCategories.push(suggestion); // Add selected category to the list
            categoryInput.value = ''; // Clear input after selecting a suggestion
            suggestionsList.innerHTML = ''; // Clear suggestions list

            const categoryNames = document.getElementById('category-names');
            categoryNames.value = selectedCategories;
        });
        suggestionsList.appendChild(li);
    });
};
