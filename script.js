document.addEventListener('DOMContentLoaded', function() {
    const menuToggle = document.querySelector('.menu-toggle');
    const navLinks = document.querySelector('.nav-links');

    menuToggle.addEventListener('click', () => {
        navLinks.classList.toggle('open');
    });
});

// Validation for contact us form
document.getElementById('contactForm').addEventListener('submit', function(event) {
    event.preventDefault(); 
    console.log("Form submitted");

    const fullName = document.getElementById('full_name').value.trim();
    const email = document.getElementById('email').value.trim();
    const gender = document.getElementById('gender').value;
    const mobile = document.getElementById('mobile').value.trim();
    const address = document.getElementById('address').value.trim();

    // For name
    if (fullName === '') {
        alert('Full Name is required.');
        return;
    }

    // For Email
    const emailPattern = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,6}$/;
    if (!emailPattern.test(email)) {
        alert('Please enter a valid email address.');
        return;
    }

    // For Gender
    if (gender === '') {
        alert('Please select your gender.');
        return;
    }

    // For Mobile
    const mobilePattern = /^\d{10}$/;
    if (!mobilePattern.test(mobile)) {
        
        alert('Mobile number must be 10 digits and numeric.');
        return;
    }

    // For Address
    if (address === '') {
        alert('Address is required.');
        return;
    }

    alert('Form submitted successfully!');
    this.submit();
});