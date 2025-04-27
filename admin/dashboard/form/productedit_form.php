<div class="edit-form-container hidden" id="editProductForm">
    <h2>Edit Product</h2>
    <form action="admin/dashboard/table/product_management.php" method="POST" enctype="multipart/form-data">
        <input type="hidden" name="edit_product" value="1">
        <input type="hidden" name="id" id="edit_id">
        
        <!-- Similar fields as add form but with id prefixed with "edit_" -->
        <div class="form-group">
            <label for="edit_name">Product Name*:</label>
            <input type="text" id="edit_name" name="name" required>
        </div>
        
        <!-- Add other fields similarly -->
        
        <div class="form-group">
            <label>Current Images:</label>
            <div id="current_images" class="image-preview"></div>
        </div>
        
        <div class="form-group">
            <label for="edit_product_images">Add New Images:</label>
            <input type="file" id="edit_product_images" name="product_images[]" multiple accept="image/*" onchange="previewNewImages(event)">
            <div id="new_image_preview" class="image-preview"></div>
        </div>
        
        <button type="submit">Save Changes</button>
        <button type="button" onclick="closeEditForm()">Cancel</button>
    </form>
</div>

<script>
function openEditForm(productId) {
    // Fetch product data using AJAX
    fetch(`get_product.php?id=${productId}`)
        .then(response => response.json())
        .then(data => {
            document.getElementById('edit_id').value = data.id;
            document.getElementById('edit_name').value = data.name;
            // Set other field values
            
            // Load current images
            const currentImages = document.getElementById('current_images');
            currentImages.innerHTML = '';
            
            data.images.forEach(image => {
                const container = document.createElement('div');
                container.className = 'preview-container';
                
                const img = document.createElement('img');
                img.src = image.url;
                img.className = 'preview-image';
                
                const removeBtn = document.createElement('button');
                removeBtn.className = 'remove-image';
                removeBtn.innerHTML = 'x';
                removeBtn.onclick = () => removeExistingImage(image.id);
                
                container.appendChild(img);
                container.appendChild(removeBtn);
                currentImages.appendChild(container);
            });
            
            document.getElementById('editProductForm').classList.remove('hidden');
        });
}

function closeEditForm() {
    document.getElementById('editProductForm').classList.add('hidden');
}

function removeExistingImage(imageId) {
    if (confirm('Are you sure you want to remove this image?')) {
        fetch(`delete_product_image.php?id=${imageId}`, { method: 'POST' })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const container = document.querySelector(`[data-image-id="${imageId}"]`);
                    container.remove();
                }
            });
    }
}
</script>