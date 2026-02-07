document.addEventListener('DOMContentLoaded', () => {
    const hamburger = document.querySelector('.hamburger-menu');
    const links = document.querySelector('.links');

    if (hamburger && links) {
        hamburger.addEventListener('click', () => {
            hamburger.classList.toggle('active');
            links.classList.toggle('active');
            document.body.classList.toggle('menu-open');
        });

        // Close menu when clicking a link
        links.querySelectorAll('a').forEach(link => {
            link.addEventListener('click', () => {
                hamburger.classList.remove('active');
                links.classList.remove('active');
                document.body.classList.remove('menu-open');
            });
        });
    }
});
