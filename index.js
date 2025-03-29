document.addEventListener('DOMContentLoaded', () => {
    // Carousel functionality
    const movieGrid = document.getElementById('movieGrid');
    const leftBtn = document.querySelector('.carousel-btn.left');
    const rightBtn = document.querySelector('.carousel-btn.right');
    const scrollAmount = 300;

    leftBtn.addEventListener('click', () => {
        movieGrid.scrollLeft -= scrollAmount;
    });

    rightBtn.addEventListener('click', () => {
        movieGrid.scrollLeft += scrollAmount;
    });
});