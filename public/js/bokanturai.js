document.addEventListener('DOMContentLoaded', function () {
    // Balance show/hide toggle
    const toggleBtn = document.getElementById('toggle-balance');
    const balanceEl = document.getElementById('wallet-balance');

    if (!toggleBtn || !balanceEl) return; // Exit if elements not found

    let hidden = false;
    const realBalance = balanceEl.textContent.trim();

    toggleBtn.addEventListener('click', function () {
        hidden = !hidden;

        if (hidden) {
            balanceEl.textContent = '₦••••••';
            toggleBtn.innerHTML = '<i class="fas fa-eye-slash"></i>';
        } else {
            balanceEl.textContent = realBalance;
            toggleBtn.innerHTML = '<i class="fas fa-eye"></i>';
        }
    });
});
