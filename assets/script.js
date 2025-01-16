// Confirmare la ștergere
const deleteLinks = document.querySelectorAll('a[href*="delete"]');

// Smooth scroll pentru linkurile de navigare
document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', function (e) {
        e.preventDefault();
        document.querySelector(this.getAttribute('href')).scrollIntoView({
            behavior: 'smooth'
        });
    });
});
