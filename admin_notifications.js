// admin_notifications.js - Fetch notifications via AJAX
function fetchNotifications() {
    fetch('fetch_notifications.php')
    .then(response => response.json())
    .then(data => {
        const notificationsContainer = document.querySelector("#notifications");
        notificationsContainer.innerHTML = "";
        data.notifications.forEach(notification => {
            const notificationElement = document.createElement("div");
            notificationElement.classList.add("notification");
            notificationElement.innerHTML = `
                <p>${notification.message}</p>
                <button onclick="markAsRead(${notification.id})">Mark as Read</button>
            `;
            notificationsContainer.appendChild(notificationElement);
        });
    })
    .catch(error => console.error('Error fetching notifications:', error));
}

function markAsRead(notificationId) {
    fetch('mark_as_read.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded'
        },
        body: `notificationId=${notificationId}`
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            fetchNotifications(); // Refresh notifications
        }
    })
    .catch(error => console.error('Error marking notification as read:', error));
}

fetchNotifications(); // Fetch notifications when the admin panel loads
