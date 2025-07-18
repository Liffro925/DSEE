document.addEventListener('DOMContentLoaded', () => {
    const slider = document.querySelector('.slider');
    const dotsContainer = document.querySelector('.dots-container');
    const prevButton = document.querySelector('.prev');
    const nextButton = document.querySelector('.next');
    let slideIndex = 0;
    let slides = [];
    let dots = [];
    let slideInterval;

    // Fetch images from the rotating folder
    fetch('get_images.php')
        .then(response => response.json())
        .then(images => {
            console.log('Fetched images:', images);
            slides = images.map(image => {
                const slide = document.createElement('div');
                slide.className = 'slide';
                const img = document.createElement('img');
                img.src = `rotating/${image}`;
                slide.appendChild(img);
                return slide;
            });
            console.log('Created slides:', slides);

            if (slides.length > 0) {
                slides.forEach(slide => slider.appendChild(slide));
                createDots();
                showSlide(slideIndex);
                startSlideShow();
            }
        });

    function createDots() {
        dotsContainer.innerHTML = '';
        dots = [];
        slides.forEach((_, i) => {
            const dot = document.createElement('div');
            dot.className = 'dot';
            dot.addEventListener('click', () => {
                showSlide(i);
                resetInterval();
            });

            const dotAnimation = document.createElement('div');
            dotAnimation.className = 'dot-animation';
            dot.appendChild(dotAnimation);
            dotsContainer.appendChild(dot);
            dots.push(dot);
        });
        console.log('Created dots:', dots);
    }

    function showSlide(index) {
        slideIndex = index;
        if (slideIndex >= slides.length) {
            slideIndex = 0;
        }
        if (slideIndex < 0) {
            slideIndex = slides.length - 1;
        }

        slider.style.transform = `translateX(${-slideIndex * 100}%)`;

        dots.forEach((dot, i) => {
            dot.classList.remove('active');
            // Reset the animation by removing and re-adding the animation div
            const dotAnimation = dot.querySelector('.dot-animation');
            if (dotAnimation) {
                const newDotAnimation = dotAnimation.cloneNode(true);
                dot.replaceChild(newDotAnimation, dotAnimation);
            }
        });

        const activeDot = dots[slideIndex];
        if (activeDot) {
            activeDot.classList.add('active');
        }
    }

    function nextSlide() {
        showSlide(slideIndex + 1);
    }

    function prevSlide() {
        showSlide(slideIndex - 1);
    }

    function startSlideShow() {
        slideInterval = setInterval(nextSlide, 8000);
    }

    function resetInterval() {
        clearInterval(slideInterval);
        startSlideShow();
    }

    prevButton.addEventListener('click', () => {
        prevSlide();
        resetInterval();
    });

    nextButton.addEventListener('click', () => {
        nextSlide();
        resetInterval();
    });
});