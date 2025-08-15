document.addEventListener("DOMContentLoaded", function() {
    const form = document.querySelector('form');
    
    form.addEventListener('submit', function(event) {
        let isValid = true;

        // Username validation
        const username = document.querySelector('input[name="username"]').value.trim();
        if (username === "") {
            alert("Username is required.");
            isValid = false;
        }

        // Email validation
        const email = document.querySelector('input[name="email"]').value.trim();
        if (email === "") {
            alert("Email is required.");
            isValid = false;
        } else if (!validateEmail(email)) {
            alert("Please enter a valid email address.");
            isValid = false;
        }

        // Password validation
        const password = document.querySelector('input[name="password"]').value.trim();
        const passwordPattern = /^(?=.*[A-Za-z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/;
        if (password === "") {
            alert("Password is required.");
            isValid = false;
        } else if (!passwordPattern.test(password)) {
            alert("Password must be at least 8 characters long and include at least one letter, one number, and one special character.");
            isValid = false;
        }

        // Prevent form submission if validation fails
        if (!isValid) {
            event.preventDefault();
        }
    });

    function validateEmail(email) {
        const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return emailPattern.test(email);
    }
});
