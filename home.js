document.addEventListener("DOMContentLoaded", function() {
    const form = document.querySelector('.contact-form');
    
    form.addEventListener('submit', function(event) {
        let isValid = true;
        
        // First Name validation
        const firstName = document.getElementById('first-name').value.trim();
        if (firstName === "") {
            alert("First Name is required.");
            isValid = false;
        }

        // Email validation
        const email = document.getElementById('email').value.trim();
        const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (email === "") {
            alert("Email is required.");
            isValid = false;
        } else if (!emailPattern.test(email)) {
            alert("Please enter a valid email address.");
            isValid = false;
        }

        // Message validation
        const message = document.getElementById('message').value.trim();
        if (message === "") {
            alert("Message is required.");
            isValid = false;
        }

        // Terms checkbox validation
        const terms = document.querySelector('input[name="terms"]');
        if (!terms.checked) {
            alert("You must accept the terms.");
            isValid = false;
        }

        // Prevent form submission if validation fails
        if (!isValid) {
            event.preventDefault();
        }
    });
});
