// Main JavaScript for Pittsfield AASR Website

document.addEventListener('DOMContentLoaded', function() {
    // Mobile navigation toggle
    const hamburger = document.querySelector('.hamburger');
    const navMenu = document.querySelector('.nav-menu');
    
    if (hamburger && navMenu) {
        hamburger.addEventListener('click', function() {
            navMenu.classList.toggle('active');
            hamburger.classList.toggle('active');
            
            // Prevent body scroll when menu is open on mobile
            if (navMenu.classList.contains('active')) {
                document.body.style.overflow = 'hidden';
            } else {
                document.body.style.overflow = '';
            }
        });

        // Close mobile menu when clicking on a nav link
        const navLinks = document.querySelectorAll('.nav-link');
        navLinks.forEach(link => {
            link.addEventListener('click', function() {
                navMenu.classList.remove('active');
                hamburger.classList.remove('active');
                document.body.style.overflow = '';
            });
        });

        // Close mobile menu when clicking outside
        document.addEventListener('click', function(event) {
            const isClickInsideNav = navMenu.contains(event.target);
            const isClickOnHamburger = hamburger.contains(event.target);
            
            if (!isClickInsideNav && !isClickOnHamburger && navMenu.classList.contains('active')) {
                navMenu.classList.remove('active');
                hamburger.classList.remove('active');
                document.body.style.overflow = '';
            }
        });
        
        // Close menu on window resize to desktop size
        window.addEventListener('resize', function() {
            if (window.innerWidth > 768 && navMenu.classList.contains('active')) {
                navMenu.classList.remove('active');
                hamburger.classList.remove('active');
                document.body.style.overflow = '';
            }
        });
    }

        // Contact form handling\n    const contactForm = document.querySelector('.contact-form');\n    if (contactForm) {\n        contactForm.addEventListener('submit', function(e) {\n            e.preventDefault();\n            \n            const submitButton = contactForm.querySelector('button[type=\"submit\"]');\n            const originalButtonText = submitButton.textContent;\n            \n            // Show loading state\n            submitButton.textContent = 'Sending...';\n            submitButton.disabled = true;\n            \n            // Get form data\n            const formData = new FormData(contactForm);\n            \n            // Send the form data\n            fetch('contact-handler.php', {\n                method: 'POST',\n                body: formData\n            })\n            .then(response => response.json())\n            .then(data => {\n                if (data.success) {\n                    showFormSuccess(data.message);\n                } else {\n                    showFormError(data.message);\n                }\n            })\n            .catch(error => {\n                console.error('Error:', error);\n                showFormError('Sorry, there was an error sending your message. Please try again later.');\n            })\n            .finally(() => {\n                // Reset button state\n                submitButton.textContent = originalButtonText;\n                submitButton.disabled = false;\n            });\n        });\n    }\n\n    function showFormSuccess(message = 'Thank you for contacting the Valley of Pittsfield. We will respond to your inquiry within 2-3 business days.') {\n        const formCard = document.querySelector('.contact-form-card');\n        if (formCard) {\n            const successMessage = document.createElement('div');\n            successMessage.className = 'form-success';\n            successMessage.innerHTML = `\n                <div style=\"text-align: center; padding: 2rem;\">\n                    <h3 style=\"color: var(--success); margin-bottom: 1rem;\">✅ Message Sent Successfully!</h3>\n                    <p style=\"margin-bottom: 1.5rem;\">${message}</p>\n                    <button onclick=\"location.reload()\" class=\"btn btn-primary\">Send Another Message</button>\n                </div>\n            `;\n            \n            formCard.innerHTML = '';\n            formCard.appendChild(successMessage);\n        }\n    }\n\n    function showFormError(message) {\n        // Create or update error message\n        let errorDiv = document.querySelector('.form-error');\n        if (!errorDiv) {\n            errorDiv = document.createElement('div');\n            errorDiv.className = 'form-error';\n            errorDiv.style.cssText = `\n                background-color: #fee;\n                border: 1px solid #fcc;\n                color: #c33;\n                padding: 1rem;\n                border-radius: 5px;\n                margin-bottom: 1rem;\n            `;\n            contactForm.insertBefore(errorDiv, contactForm.firstChild);\n        }\n        \n        errorDiv.innerHTML = `<strong>Error:</strong> ${message}`;\n        \n        // Remove error message after 5 seconds\n        setTimeout(() => {\n            if (errorDiv && errorDiv.parentNode) {\n                errorDiv.parentNode.removeChild(errorDiv);\n            }\n        }, 5000);\n    }"

    // Smooth scrolling for anchor links
    const anchorLinks = document.querySelectorAll('a[href^="#"]');
    anchorLinks.forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            const targetId = this.getAttribute('href');
            const targetElement = document.querySelector(targetId);
            
            if (targetElement) {
                const offsetTop = targetElement.offsetTop - 80; // Account for fixed header
                window.scrollTo({
                    top: offsetTop,
                    behavior: 'smooth'
                });
            }
        });
    });

    // Add active state to navigation based on current page
    function setActiveNav() {
        const currentPage = window.location.pathname.split('/').pop() || 'index.html';
        const navLinks = document.querySelectorAll('.nav-link');
        
        navLinks.forEach(link => {
            const href = link.getAttribute('href');
            if (href === currentPage || (currentPage === '' && href === 'index.html')) {
                link.classList.add('active');
            } else {
                link.classList.remove('active');
            }
        });
    }
    
    setActiveNav();

    // Lazy loading for images
    if ('IntersectionObserver' in window) {
        const imageObserver = new IntersectionObserver((entries, observer) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const img = entry.target;
                    if (img.dataset.src) {
                        img.src = img.dataset.src;
                        img.removeAttribute('data-src');
                    }
                    observer.unobserve(img);
                }
            });
        });

        const lazyImages = document.querySelectorAll('img[data-src]');
        lazyImages.forEach(img => imageObserver.observe(img));
    }

    // Add fade-in animation for cards on scroll
    if ('IntersectionObserver' in window) {
        const cardObserver = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.style.opacity = '1';
                    entry.target.style.transform = 'translateY(0)';
                }
            });
        }, {
            threshold: 0.1,
            rootMargin: '0px 0px -50px 0px'
        });

        // Apply to cards with a slight delay for staggered effect
        const cards = document.querySelectorAll('.feature-card, .update-card, .value-card, .connection-card');
        cards.forEach((card, index) => {
            card.style.opacity = '0';
            card.style.transform = 'translateY(20px)';
            card.style.transition = `opacity 0.6s ease ${index * 0.1}s, transform 0.6s ease ${index * 0.1}s`;
            cardObserver.observe(card);
        });
    }

    // Simple form validation helper
    function validateForm(form) {
        const requiredFields = form.querySelectorAll('[required]');
        let isValid = true;
        
        requiredFields.forEach(field => {
            if (!field.value.trim()) {
                field.style.borderColor = 'var(--error)';
                isValid = false;
            } else {
                field.style.borderColor = 'var(--border-light)';
            }
        });
        
        return isValid;
    }

    // Add focus/blur effects to form fields
    const formFields = document.querySelectorAll('input, select, textarea');
    formFields.forEach(field => {
        field.addEventListener('focus', function() {
            this.style.borderColor = 'var(--primary-400)';
            this.style.boxShadow = '0 0 0 3px rgba(26, 35, 126, 0.1)';
        });
        
        field.addEventListener('blur', function() {
            this.style.borderColor = 'var(--border-light)';
            this.style.boxShadow = 'none';
        });
    });

    // Print functionality
    function printPage() {
        window.print();
    }

    // Expose print function globally
    window.printPage = printPage;

    // Add keyboard navigation support
    document.addEventListener('keydown', function(e) {
        // ESC key closes mobile menu
        if (e.key === 'Escape') {
            if (navMenu && navMenu.classList.contains('active')) {
                navMenu.classList.remove('active');
                hamburger.classList.remove('active');
                document.body.style.overflow = '';
            }
        }
    });

    // Initialize any tooltips or interactive elements
    function initializeInteractiveElements() {
        // Add hover effects to cards
        const interactiveCards = document.querySelectorAll('.feature-card, .value-card, .connection-card');
        interactiveCards.forEach(card => {
            card.addEventListener('mouseenter', function() {
                this.style.transform = 'translateY(-5px)';
                this.style.boxShadow = '0 10px 25px var(--card-shadow)';
            });
            
            card.addEventListener('mouseleave', function() {
                this.style.transform = 'translateY(0)';
                this.style.boxShadow = '0 5px 15px var(--card-shadow)';
            });
        });
    }

    initializeInteractiveElements();

    console.log('Valley of Pittsfield AASR website initialized successfully');
});

// Utility functions for future enhancement
const PittsfieldAASR = {
    // Function to create modal dialogs (for future use)
    createModal: function(content, title = '') {
        const modal = document.createElement('div');
        modal.className = 'modal-overlay';
        modal.innerHTML = `
            <div class="modal-content">
                <div class="modal-header">
                    <h3>${title}</h3>
                    <button class="modal-close">&times;</button>
                </div>
                <div class="modal-body">
                    ${content}
                </div>
            </div>
        `;
        
        document.body.appendChild(modal);
        
        // Close modal functionality
        const closeBtn = modal.querySelector('.modal-close');
        closeBtn.addEventListener('click', () => {
            document.body.removeChild(modal);
        });
        
        modal.addEventListener('click', (e) => {
            if (e.target === modal) {
                document.body.removeChild(modal);
            }
        });
        
        return modal;
    },

    // Function to format dates consistently
    formatDate: function(date) {
        const options = { year: 'numeric', month: 'long', day: 'numeric' };
        return new Date(date).toLocaleDateString('en-US', options);
    },

    // Function to validate email addresses
    isValidEmail: function(email) {
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return emailRegex.test(email);
    }
};

// Make utility functions available globally
window.PittsfieldAASR = PittsfieldAASR;