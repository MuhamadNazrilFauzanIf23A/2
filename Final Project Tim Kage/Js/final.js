const cards = document.querySelectorAll('.card');

// efek hover
cards.forEach((card) => {
    // saat mouse masuk
    card.addEventListener('mouseenter', () => {
        card.style.transition = '0.3s ease';
        card.style.boxShadow = '0 4px 8px rgba(0, 0, 0, 0.2)';
        card.style.transform = 'scale(1.07)';
     });

    // saat mouse keluar
    card.addEventListener('mouseleave', () => {
        card.style.boxShadow = 'none';
        card.style.transform = 'scale(1)';
    });
});


