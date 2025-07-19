// your code goes here
// No custom JS is strictly required for Bootstrap 5 carousel/accordion, but you can enhance carousel responsiveness here if needed.

// Optional: Responsive carousel for cards (show 1 card per slide on mobile)
document.addEventListener('DOMContentLoaded', function () {
    function updateCarouselItems() {
        const width = window.innerWidth;
        const carousel = document.getElementById('benefitsCarousel');
        if (!carousel) return;
        const items = carousel.querySelectorAll('.carousel-item');
        items.forEach(item => {
            const cols = item.querySelectorAll('.col-md-4');
            if (width < 768) {
                cols.forEach(col => {
                    col.classList.remove('col-md-4');
                    col.classList.add('col-12', 'mb-3');
                });
            } else {
                cols.forEach(col => {
                    col.classList.add('col-md-4');
                    col.classList.remove('col-12', 'mb-3');
                });
            }
        });
    }
    updateCarouselItems();
    window.addEventListener('resize', updateCarouselItems);
});

// Optionally, smooth scroll for anchor links
document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', function (e) {
        const target = document.querySelector(this.getAttribute('href'));
        if (target) {
            e.preventDefault();
            target.scrollIntoView({ behavior: 'smooth' });
        }
    });
});
// Modal tab switching for Login/Signup
document.addEventListener('DOMContentLoaded', function () {
    // Switch from login modal to signup modal
    document.getElementById('showSignupFromLogin').addEventListener('click', function(e) {
        e.preventDefault();
        var loginModal = bootstrap.Modal.getInstance(document.getElementById('loginModal'));
        loginModal.hide();
        setTimeout(function() {
            var signupModal = new bootstrap.Modal(document.getElementById('signupModal'));
            signupModal.show();
        }, 300);
    });

    // Switch from signup modal to login modal
    document.getElementById('showLoginFromSignup').addEventListener('click', function(e) {
        e.preventDefault();
        var signupModal = bootstrap.Modal.getInstance(document.getElementById('signupModal'));
        signupModal.hide();
        setTimeout(function() {
            var loginModal = new bootstrap.Modal(document.getElementById('loginModal'));
            loginModal.show();
        }, 300);
    });
});


 