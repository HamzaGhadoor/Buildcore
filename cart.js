document.addEventListener("DOMContentLoaded", () => {
    updateSummary();

    // Ensure proper event delegation for dynamic elements
    document.querySelector("#cartTable").addEventListener("change", (event) => {
        if (event.target.classList.contains("quantity")) {
            updateTotal(event.target);
        } else if (event.target.classList.contains("material-type")) {
            updatePrice(event.target);
        }
    });

    document.querySelector("#orderForm").addEventListener("submit", (event) => {
        if (!validateForm()) {
            event.preventDefault(); // Stop form submission if validation fails
        }
    });
});

function validateForm() {
    const customerName = document.getElementById("customerName").value.trim();
    const customerPhone = document.getElementById("customerPhone").value.trim();
    const customerEmail = document.getElementById("customerEmail").value.trim();
    const feedbackMessage = document.getElementById("feedbackMessage").value.trim();
    const cartItems = document.querySelectorAll("#cartTable tbody tr").length;

    if (!customerName || !customerPhone || !customerEmail || !feedbackMessage) {
        alert("Please fill in all customer details before placing the order.");
        return false;
    }

    if (!/^\d+$/.test(customerPhone)) {
        alert("Please enter a valid phone number (numbers only).");
        return false;
    }

    if (cartItems === 0) {
        alert("Your cart is empty. Please add items before placing an order.");
        return false;
    }

    return true;
}

function updateTotal(input) {
    const row = input.closest("tr");
    const unitPrice = parseFloat(row.querySelector(".unit-price").innerText.replace("₨", "")) || 0;
    const quantity = parseInt(input.value) || 1;
    const totalPrice = unitPrice * quantity;
    
    row.querySelector(".total-price").innerText = `₨${totalPrice.toFixed(2)}`;
    updateSummary();
}

function updatePrice(select) {
    const row = select.closest("tr");
    const unitPrice = parseFloat(select.value) || 0;
    
    row.querySelector(".unit-price").innerText = `₨${unitPrice}`;
    updateTotal(row.querySelector(".quantity"));
}

function updateSummary() {
    let subtotal = 0;
    document.querySelectorAll(".total-price").forEach(price => {
        subtotal += parseFloat(price.innerText.replace("₨", "")) || 0;
    });

    const delivery = 100;
    const totalCost = subtotal + delivery;

    document.getElementById("subtotal").innerText = `₨${subtotal.toFixed(2)}`;
    document.getElementById("delivery").innerText = `₨${delivery.toFixed(2)}`;
    document.getElementById("totalCost").innerText = `₨${totalCost.toFixed(2)}`;
    document.getElementById("totalOrderCost").value = totalCost.toFixed(2);
}

function removeItem(button) {
    if (confirm("Are you sure you want to remove this item?")) {
        button.closest("tr").remove();
        updateSummary();
    }
}

function confirmOrder() {
    if (!validateForm()) return;

    let orderSummary = "Your order includes:\n";
    document.querySelectorAll("#cartTable tbody tr").forEach(row => {
        const productName = row.querySelector("td:nth-child(2)").innerText;
        const materialType = row.querySelector(".material-type").selectedOptions[0].text;
        const quantity = row.querySelector(".quantity").value;
        orderSummary += `- ${productName} (${materialType}) x ${quantity}\n`;
    });

    orderSummary += `\nTotal Cost: ${document.getElementById("totalCost").innerText}`;

    if (confirm(orderSummary + "\n\nConfirm order?")) {
        document.querySelector("#orderForm").submit();
    }
}
function updatePrice(select) {
    const row = select.closest("tr");
    const unitPrice = parseFloat(select.value);
    row.querySelector(".unit-price").innerText = `₨${unitPrice.toFixed(2)}`;
    const quantity = parseInt(row.querySelector(".quantity").value) || 0;
    const totalPrice = unitPrice * quantity;
    row.querySelector(".total-price").innerText = `₨${totalPrice.toFixed(2)}`;
    updateSummary();
}

function updateTotal(input) {
    const row = input.closest("tr");
    const unitPrice = parseFloat(row.querySelector(".unit-price").innerText.replace("₨", ""));
    const quantity = parseInt(input.value) || 0;
    const totalPrice = unitPrice * quantity;
    row.querySelector(".total-price").innerText = `₨${totalPrice.toFixed(2)}`;
    updateSummary();
}

function updateSummary() {
    let subtotal = 0;
    document.querySelectorAll(".total-price").forEach(price => {
        subtotal += parseFloat(price.innerText.replace("₨", ""));
    });
    console.log("Updated subtotal: ₨" + subtotal.toFixed(2));
}

function removeItem(button) {
    button.closest("tr").remove();
    updateSummary();
}