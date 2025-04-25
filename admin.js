document.getElementById('changePasswordForm').addEventListener('submit', function(event) {
    event.preventDefault();

    const currentPassword = document.getElementById('currentPassword').value;
    const newPassword = document.getElementById('newPassword').value;

    // Send password change request to the server
    fetch('change_password.php', {
        method: 'POST',
        body: JSON.stringify({
            currentPassword: currentPassword,
            newPassword: newPassword
        }),
        headers: {
            'Content-Type': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Password changed successfully!');
            document.getElementById('changePasswordForm').reset();
        } else {
            alert('Current password is incorrect!');
        }
    })
    .catch(error => console.error('Error:', error));
});
// admin.js - This script will update the price using AJAX
document.querySelector("#updatePriceForm").addEventListener("submit", function (event) {
    event.preventDefault();

    let materialId = document.querySelector("#materialId").value;
    let newPrice = document.querySelector("#newPrice").value;

    // Send the data using AJAX
    fetch('update_price.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded'
        },
        body: `materialId=${materialId}&newPrice=${newPrice}`
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert(data.message);
            // Optionally, you can update the price on the front end
            document.querySelector(`#price${materialId}`).innerText = `₨${newPrice}`;
        } else {
            alert(data.message);
        }
    })
    .catch(error => console.error('Error:', error));
});
