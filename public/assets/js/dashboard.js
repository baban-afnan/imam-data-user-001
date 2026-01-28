/**
 * Dashboard JavaScript
 * Handles balance toggle and transaction chart
 */

// Toggle balance visibility
document.addEventListener('DOMContentLoaded', function () {
    // Balance toggle functionality
    const toggleBtn = document.getElementById('toggle-balance');
    const balanceText = document.getElementById('wallet-balance');

    if (toggleBtn && balanceText) {
        const eyeIcon = toggleBtn.querySelector('.eye-icon');
        let isVisible = true;
        let actualBalance = balanceText.textContent;

        toggleBtn.addEventListener('click', function () {
            isVisible = !isVisible;
            if (isVisible) {
                balanceText.textContent = actualBalance;
                eyeIcon.classList.remove('fa-eye-slash');
                eyeIcon.classList.add('fa-eye');
            } else {
                balanceText.textContent = '₦****';
                eyeIcon.classList.remove('fa-eye');
                eyeIcon.classList.add('fa-eye-slash');
            }
        });
    }

    // Transaction Chart
    const chartCanvas = document.getElementById('transactionChart');
    if (chartCanvas) {
        const ctx = chartCanvas.getContext('2d');

        // Get data from data attributes
        const completedTx = parseInt(chartCanvas.dataset.completed || 0);
        const pendingTx = parseInt(chartCanvas.dataset.pending || 0);
        const failedTx = parseInt(chartCanvas.dataset.failed || 0);

        new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: ['Success', 'Pending', 'Failed'],
                datasets: [{
                    data: [completedTx, pendingTx, failedTx],
                    backgroundColor: [
                        '#28a745', // Success - Green
                        '#ffc107', // Pending - Yellow
                        '#dc3545'  // Failed - Red
                    ],
                    borderWidth: 0,
                    hoverOffset: 4
                }]
            },
            options: {
                cutout: '75%',
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        enabled: true
                    }
                }
            }
        });
    }
});
