// Add Material
document.getElementById('addMaterialForm').addEventListener('submit', function (event) {
    event.preventDefault();

    const materialName = document.getElementById('materialName').value;
    const materialType = document.getElementById('materialType').value;
    const materialPrice = document.getElementById('materialPrice').value;
    const materialImage = document.getElementById('materialImage').files[0];

    const formData = new FormData();
    formData.append('materialName', materialName);
    formData.append('materialType', materialType);
    formData.append('materialPrice', materialPrice);
    formData.append('materialImage', materialImage);

    fetch('add_material.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Material added successfully');
            document.getElementById('addMaterialForm').reset();
        } else {
            alert('Failed to add material');
        }
    })
    .catch(error => console.error('Error:', error));
});

// Remove Material
document.getElementById('removeMaterialForm').addEventListener('submit', function (event) {
    event.preventDefault();

    const materialName = document.getElementById('removeMaterialName').value;

    fetch('remove_material.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({ materialName })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Material removed successfully');
            document.getElementById('removeMaterialForm').reset();
        } else {
            alert('Failed to remove material');
        }
    })
    .catch(error => console.error('Error:', error));
});