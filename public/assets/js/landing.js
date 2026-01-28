document.addEventListener('DOMContentLoaded', function () {
    // Initialize AOS
    if (typeof AOS !== 'undefined') {
        AOS.init({
            duration: 1000,
            once: true,
            offset: 100,
            easing: 'ease-out-cubic'
        });
    }

    // Scroll Header Effect
    const header = document.querySelector('header');

    if (header) {
        function updateHeader() {
            if (window.scrollY > 50) {
                header.classList.add('scrolled');
            } else {
                header.classList.remove('scrolled');
            }
        }

        window.addEventListener('scroll', updateHeader);
        updateHeader(); // Check on load
    }

    // Mobile Menu Toggle
    const mobileMenuBtn = document.querySelector('.mobile-menu');
    const navUl = document.querySelector('nav ul');

    if (mobileMenuBtn) {
        mobileMenuBtn.addEventListener('click', function (e) {
            e.stopPropagation();
            this.classList.toggle('active');
            navUl.classList.toggle('active');
            document.body.classList.toggle('menu-open');
        });
    }

    // Close mobile menu when clicking on a link
    document.querySelectorAll('nav ul li a').forEach(link => {
        link.addEventListener('click', function () {
            navUl.classList.remove('active');
            mobileMenuBtn.classList.remove('active');
            document.body.classList.remove('menu-open');
        });
    });

    // Close menu when clicking outside
    document.addEventListener('click', function (event) {
        if (navUl && navUl.classList.contains('active')) {
            if (!navUl.contains(event.target) && !mobileMenuBtn.contains(event.target)) {
                navUl.classList.remove('active');
                mobileMenuBtn.classList.remove('active');
                document.body.classList.remove('menu-open');
            }
        }
    });

    // Smooth scrolling for anchor links
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            const href = this.getAttribute('href');
            if (href === '#' || !href.startsWith('#')) return;

            const target = document.querySelector(href);
            if (target) {
                e.preventDefault();
                const headerOffset = 80;
                const elementPosition = target.getBoundingClientRect().top;
                const offsetPosition = elementPosition + window.pageYOffset - headerOffset;

                window.scrollTo({
                    top: offsetPosition,
                    behavior: 'smooth'
                });
            }
        });
    });

    // Privacy Banner Logic
    const privacyBanner = document.getElementById('privacyBanner');

    if (privacyBanner) {
        // Check localStorage
        const isAccepted = localStorage.getItem('aura_privacy_accepted');
        const isRejected = localStorage.getItem('aura_privacy_rejected');

        if (!isAccepted && !isRejected) {
            // Show with a slight delay for better UX
            setTimeout(() => {
                privacyBanner.style.display = 'block';
            }, 1500);
        }
    }

    // Make functions globally available for inline onclick handlers
    window.acceptPrivacyPolicy = function () {
        localStorage.setItem('aura_privacy_accepted', 'true');
        if (privacyBanner) privacyBanner.style.display = 'none';

        // Close modal if open
        const modalEl = document.getElementById('dataProtectionModal');
        if (modalEl) {
            const modalInstance = bootstrap.Modal.getInstance(modalEl);
            if (modalInstance) modalInstance.hide();
        }
    };

    window.rejectPrivacy = function () {
        if (privacyBanner) privacyBanner.style.display = 'none';
        localStorage.setItem('aura_privacy_rejected', 'true');
    };

    window.openDataProtectionModal = function () {
        const modalEl = document.getElementById('dataProtectionModal');
        if (modalEl) {
            const myModal = new bootstrap.Modal(modalEl, {
                keyboard: false
            });
            myModal.show();
        }
    };

    /* Authentication Logic */
    // Form Processing Logic
    const authForms = document.querySelectorAll('form');
    authForms.forEach(form => {
        form.addEventListener('submit', function () {
            const submitBtn = this.querySelector('button[type="submit"]');
            if (submitBtn) {
                submitBtn.classList.add('btn-processing');
                submitBtn.disabled = true;
                if (!submitBtn.querySelector('.spinner-border')) {
                    submitBtn.insertAdjacentHTML('afterbegin', '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>');
                }
            }
        });
    });

    // Password Toggle
    document.querySelectorAll('.toggle-password').forEach(toggle => {
        toggle.addEventListener('click', function () {
            const input = this.closest('.pass-group').querySelector('input');
            const isPassword = input.type === 'password';
            input.type = isPassword ? 'text' : 'password';
            this.classList.toggle('ti-eye-off', !isPassword);
            this.classList.toggle('ti-eye', isPassword);
        });
    });

    // Registration Strength Meter
    const passwordInput = document.getElementById('password');
    const authStrengthBar = document.getElementById('passwordStrengthBar');
    const authStrengthText = document.getElementById('passwordStrengthText');

    if (passwordInput && authStrengthBar) {
        passwordInput.addEventListener('input', () => {
            const val = passwordInput.value;
            let score = 0;
            if (val.length >= 8) score++;
            if (/[A-Z]/.test(val)) score++;
            if (/[a-z]/.test(val)) score++;
            if (/[0-9]/.test(val)) score++;
            if (/[^A-Za-z0-9]/.test(val)) score++;

            const colors = ['bg-danger', 'bg-danger', 'bg-warning', 'bg-info', 'bg-success', 'bg-success'];
            const labels = ['Too Weak', 'Weak', 'Fair', 'Good', 'Strong', 'Excellent'];

            authStrengthBar.style.width = (score * 20) + '%';
            authStrengthBar.className = 'progress-bar ' + colors[score];
            if (authStrengthText) authStrengthText.textContent = labels[score];
        });
    }
});
