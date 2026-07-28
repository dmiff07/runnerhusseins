// script.js

// Accept order function (for runners)
function acceptOrder(parcelId) {
    if (confirm('Are you sure you want to accept this order?')) {
        window.location.href = 'available_orders.php?accept=' + parcelId;
    }
}

// Update order status function (for runners)
function updateStatus(parcelId, status) {
    if (!confirm(`Are you sure you want to mark this order as "${status}"?`)) {
        return;
    }
    
    fetch('update_status.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: `parcel_id=${parcelId}&status=${status}`
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('✅ Status updated successfully!');
            location.reload();
        } else {
            alert('❌ Error: ' + data.message);
        }
    })
    .catch(error => {
        alert('❌ Error updating status: ' + error);
    });
}

// Cancel order function (for students)
function cancelOrder(parcelId) {
    if (!confirm('Are you sure you want to cancel this order?')) {
        return;
    }
    
    fetch('update_status.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: `parcel_id=${parcelId}&status=cancelled`
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('✅ Order cancelled successfully!');
            location.reload();
        } else {
            alert('❌ Error: ' + data.message);
        }
    })
    .catch(error => {
        alert('❌ Error cancelling order: ' + error);
    });
}

// Filter orders by location
function filterOrders() {
    const input = document.getElementById('locationFilter');
    if (!input) return;
    
    const filter = input.value.toLowerCase();
    const orders = document.querySelectorAll('.available-order');
    
    orders.forEach(order => {
        const location = order.getAttribute('data-location') || '';
        if (location.includes(filter)) {
            order.style.display = 'block';
        } else {
            order.style.display = 'none';
        }
    });
}

// Rate runner function
function rateOrder(parcelId) {
    const rating = prompt('Rate the runner (1-5 stars):', '5');
    if (rating === null) return;
    
    if (isNaN(rating) || rating < 1 || rating > 5) {
        alert('Please enter a number between 1 and 5');
        return;
    }
    
    const review = prompt('Leave a review (optional):', '');
    
    fetch('rate_runner.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: `parcel_id=${parcelId}&rating=${rating}&review=${review}`
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('✅ Thank you for rating!');
            location.reload();
        } else {
            alert('❌ Error: ' + data.message);
        }
    })
    .catch(error => {
        alert('❌ Error submitting rating: ' + error);
    });
}

// Auto-hide alerts after 5 seconds
document.addEventListener('DOMContentLoaded', function() {
    const alerts = document.querySelectorAll('.alert');
    alerts.forEach(alert => {
        setTimeout(() => {
            alert.style.transition = 'opacity 0.5s';
            alert.style.opacity = '0';
            setTimeout(() => {
                alert.style.display = 'none';
            }, 500);
        }, 5000);
    });
});

// Confirm before navigation (for forms)
document.addEventListener('DOMContentLoaded', function() {
    const forms = document.querySelectorAll('form');
    forms.forEach(form => {
        form.addEventListener('submit', function(e) {
            const button = this.querySelector('button[type="submit"]');
            if (button) {
                button.disabled = true;
                button.textContent = 'Processing...';
            }
        });
    });
});