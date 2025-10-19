document.addEventListener('DOMContentLoaded', () => {
    const slider = document.querySelector('.slider');
    const dotsContainer = document.querySelector('.dots-container');
    const prevButton = document.querySelector('.prev');
    const nextButton = document.querySelector('.next');
    let slideIndex = 0;
    let slides = [];
    let dots = [];
    let slideInterval;
    if (!slider || !dotsContainer || !prevButton || !nextButton) return;

    const controller = new AbortController();
    const timeoutId = setTimeout(() => controller.abort(), 8000);
    fetch('get_images.php', { cache: 'no-store', signal: controller.signal })
        .then(response => { if (!response.ok) throw new Error('bad status'); return response.json(); })
        .then(images => {
            clearTimeout(timeoutId);
            const list = Array.isArray(images) ? images.filter(Boolean) : [];
            if (!list.length) { useFallback(); return; }
            slides = list.map(image => {
                const slide = document.createElement('div');
                slide.className = 'slide';
                const img = document.createElement('img');
                img.src = `rotating/${image}`;
                img.loading = 'lazy';
                img.decoding = 'async';
                img.alt = 'Banner image';
                img.onerror = () => { img.src = 'images/banner.jpg'; };
                slide.appendChild(img);
                return slide;
            });
            if (slides.length > 0) {
                slides.forEach(slide => slider.appendChild(slide));
                createDots();
                showSlide(slideIndex);
                startSlideShow();
            } else {
                useFallback();
            }
        })
        .catch(() => { clearTimeout(timeoutId); useFallback(); });

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
    }

    function showSlide(index) {
        if (!slides.length) return;
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

    function useFallback() {
        const slide = document.createElement('div');
        slide.className = 'slide';
        const img = document.createElement('img');
        img.src = 'images/banner.jpg';
        img.loading = 'lazy';
        img.decoding = 'async';
        img.alt = 'Banner image';
        slide.appendChild(img);
        slides = [slide];
        slider.appendChild(slide);
        createDots();
        showSlide(0);
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