
document.addEventListener('DOMContentLoaded', () => {

/**
   * Initialize AOS (Animate On Scroll)
   */
AOS.init({
    duration: 1000, // Animation duration
    easing: 'ease-in-out', // Animation easing
    once: true, // Whether animation should happen only once
    mirror: false // Whether elements should animate out while scrolling past them
});

/**
   * Initialize GLightbox
   */
const glightbox = GLightbox({
     selector: '.glightbox' // Add this class to links you want to open in lightbox
});

/**
   * Initialize Swiper for Team Section
   */
new Swiper('.team-swiper', {
    speed: 600,
      loop: true, // Set to false if you don't want infinite loop
    autoplay: {
        delay: 5000,
        disableOnInteraction: false
    },
      slidesPerView: 'auto', // Let Swiper determine based on slide CSS width
    pagination: {
        el: '.swiper-pagination',
        type: 'bullets',
        clickable: true
    },
    navigation: {
        nextEl: '.swiper-button-next',
        prevEl: '.swiper-button-prev',
    },
    breakpoints: {
        // when window width is >= 320px
        320: {
        slidesPerView: 1,
        spaceBetween: 20
        },
        // when window width is >= 768px
        768: {
        slidesPerView: 2,
        spaceBetween: 30
        },
        // when window width is >= 992px
        992: {
          slidesPerView: 3, // Show 3 team members on larger screens
        spaceBetween: 40
        },
         // when window width is >= 1200px
        1200: {
          slidesPerView: 4, // Show 4 team members on xl screens
        spaceBetween: 40
        }
    }
    });

/**
   * Initialize Slick Carousel for News Section
   */
$('.education-soul-carousel').slick({
    dots: true,
    arrows: true,
      infinite: true, // Consider setting to false if few items
    speed: 500,
    slidesToShow: 2,
    slidesToScroll: 1,
    autoplay: true,
    autoplaySpeed: 4000,
      prevArrow: '.left-arrow', // Use the span classes defined in HTML
    nextArrow: '.right-arrow',
    responsive: [
        {
              breakpoint: 992, // Medium devices (tablets, less than 992px)
            settings: {
                slidesToShow: 2,
                slidesToScroll: 1
            }
        },
        {
              breakpoint: 768, // Small devices (landscape phones, less than 768px)
            settings: {
                slidesToShow: 1,
                slidesToScroll: 1,
                  arrows: false // Hide arrows on small screens as defined in original config
            }
        }
    ]
});


/**
   * Preloader
   */
  const preloader = document.querySelector('#preloader');
  if (preloader) {
    window.addEventListener('load', () => {
      preloader.style.opacity = '0'; // Fade out
      setTimeout(() => {
         preloader.remove();
      }, 600); // Remove after fade transition (matches CSS transition duration)
    });
  }

  /**
   * Sticky Header on Scroll
   */
  const header = document.querySelector('#header');
  if (header) {
    const headerScrolled = () => {
      if (window.scrollY > 100) {
        header.classList.add('scrolled');
      } else {
        header.classList.remove('scrolled');
      }
    }
    window.addEventListener('load', headerScrolled);
    document.addEventListener('scroll', headerScrolled);
  }

   /**
   * Scroll Top Button
   */
  const scrollTop = document.querySelector('.scroll-top');
  if (scrollTop) {
    const toggleScrollTop = function() {
      window.scrollY > 100 ? scrollTop.classList.add('active') : scrollTop.classList.remove('active');
    }
    window.addEventListener('load', toggleScrollTop);
    document.addEventListener('scroll', toggleScrollTop);
    scrollTop.addEventListener('click', (e) => {
      e.preventDefault();
      window.scrollTo({
        top: 0,
        behavior: 'smooth'
      });
    });
  }

  /**
   * Smooth Scroll for Navbar Links & ScrollTo buttons
   */
  const navLinks = document.querySelectorAll('.navbar-nav .nav-link[href^="#"], .scrollto[href^="#"]');
  navLinks.forEach(link => {
    link.addEventListener('click', function (e) {
      e.preventDefault();
      let targetId = this.getAttribute('href');
      let targetElement = document.querySelector(targetId);

      if (targetElement) {
        let header = document.querySelector('#header');
        let headerOffset = header.offsetHeight;

        if (targetId === '#hero-static') {
          headerOffset = 0;
        }

        let elementPosition = targetElement.offsetTop;
        let offsetPosition = elementPosition - headerOffset;

        window.scrollTo({
          top: offsetPosition,
          behavior: 'smooth'
        });

        // Close mobile nav menu if open
        let navbarToggler = document.querySelector('.navbar-toggler');
        let navbarCollapse = document.querySelector('#navbarNav');
        if (navbarToggler && !navbarToggler.classList.contains('collapsed') && navbarCollapse.classList.contains('show')) {
          var bsCollapse = new bootstrap.Collapse(navbarCollapse, {
            toggle: false
          });
          bsCollapse.hide();
        }

        // Update active state
        navLinks.forEach((link) => link.classList.remove('active'));
        this.classList.add('active');

        if (this.classList.contains('dropdown-item')) {
          this.closest('.nav-item.dropdown').querySelector('.nav-link.dropdown-toggle').classList.add('active');
        }
      }
    });
  });

  /**
   * Activate Nav Link on Scroll
   */
  let sections = document.querySelectorAll('section[id]');
  window.addEventListener('scroll', () => {
    let scrollY = window.pageYOffset;
    let headerOffset = document.querySelector('#header').offsetHeight + 1;

    sections.forEach(current => {
      const sectionHeight = current.offsetHeight;
      const sectionTop = current.offsetTop - headerOffset;
      let sectionId = current.getAttribute('id');
      let correspondingLink = document.querySelector('.navbar-nav .nav-link[href="#' + sectionId + '"]');

      if (correspondingLink) {
        if (scrollY >= sectionTop && scrollY <= sectionTop + sectionHeight) {
          correspondingLink.classList.add('active');
          if (correspondingLink.closest('.dropdown-menu')) {
            correspondingLink.closest('.nav-item.dropdown').querySelector('.nav-link').classList.add('active');
          }
        } else {
          correspondingLink.classList.remove('active');
          if (correspondingLink.closest('.dropdown-menu')) {
            let parentDropdownLink = correspondingLink.closest('.nav-item.dropdown').querySelector('.nav-link');
            let otherActive = correspondingLink.closest('.dropdown-menu').querySelector('.dropdown-item.active');
            if (!otherActive) {
              parentDropdownLink.classList.remove('active');
            }
          }
        }
      }
    });

    // Special case for the top of the page (Home link)
    let homeLink = document.querySelector('.navbar-nav .nav-link[href="#hero-static"]');
    if (homeLink) {
      if (scrollY < sections[0].offsetTop - headerOffset) {
        document.querySelectorAll('.navbar-nav .nav-link.active').forEach(l => l.classList.remove('active'));
        homeLink.classList.add('active');
      }
    }
  });

  // Update Footer Year Dynamically
  const footerYear = document.getElementById('footer-year');
  if (footerYear) {
    footerYear.textContent = new Date().getFullYear();
  }

}); // End DOMContentLoaded
