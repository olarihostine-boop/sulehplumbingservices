// Hamburger Menu Toggle
const hamburger = document.getElementById('hamburger');
const navLinks = document.getElementById('nav-links');

if (hamburger) {
    hamburger.addEventListener('click', () => {
        navLinks.classList.toggle('open');
    });
}

// Close menu when a link is clicked
document.querySelectorAll('.nav-links a').forEach(link => {
    link.addEventListener('click', () => {
        navLinks.classList.remove('open');
    });
});

// Contact Form AJAX Submission
const contactForm = document.getElementById('contact-form');
const formResponse = document.getElementById('form-response');

if (contactForm) {
    contactForm.addEventListener('submit', async (e) => {
        e.preventDefault();
        const submitBtn = contactForm.querySelector('button[type="submit"]');
        submitBtn.textContent = 'Sending...';
        submitBtn.disabled = true;

        try {
            const response = await fetch('contact.php', {
                method: 'POST',
                body: new FormData(contactForm)
            });
            const result = await response.json();
            if (result.status === 'success') {
                formResponse.innerHTML = `<p style="color:#2d7a2d;margin-top:10px;">${result.message}</p>`;
                contactForm.reset();
            } else {
                formResponse.innerHTML = `<p style="color:#cc0000;margin-top:10px;">${result.message}</p>`;
            }
        } catch (err) {
            formResponse.innerHTML = `<p style="color:#cc0000;margin-top:10px;">Something went wrong. Please call us directly.</p>`;
        } finally {
            submitBtn.textContent = 'Send Message';
            submitBtn.disabled = false;
        }
    });
}
