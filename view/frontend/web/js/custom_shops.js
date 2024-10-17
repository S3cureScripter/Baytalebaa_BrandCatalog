$('.best-seller-section .owl-carousel').owlCarousel({
    items: 4,
    loop: false,
    margin: 14,
    nav: true,
    dots: false,
    navText: [
        '<div class="custom-prev"><i class="fas fa-chevron-left"></i></div>',
        '<div class="custom-next"><i class="fas fa-chevron-right"></i></div>'
    ],
    responsive: {
        0: {
            items: 1
        },
        600: {
            items: 2
        },
        1000: {
            items: 4
        },
        1200: {
            items: 4
        }
    }
});


$('.new-arrivals-section .owl-carousel').owlCarousel({
    items: 4,
    loop: false,
    margin: 14,
    nav: true,
    dots: false,
    navText: [
        '<div class="custom-prev"><i class="fas fa-chevron-left"></i></div>',
        '<div class="custom-next"><i class="fas fa-chevron-right"></i></div>'
    ],
    responsive: {
        0: {
            items: 1
        },
        600: {
            items: 2
        },
        1000: {
            items: 4
        },
        1200: {
            items: 4
        }
    }
});