//Khusus Carousel
class JSCarousel {
  constructor({ carouselSelector, slideSelector, enablePagination = true }) {
    this.currentSlideIndex = 0;
    this.carousel = document.querySelector(carouselSelector);
    this.slides = this.carousel?.querySelectorAll(slideSelector) || [];
    this.enablePagination = enablePagination;
    this.prevBtn = null;
    this.nextBtn = null;
    this.paginationContainer = null;

    if (!this.carousel) {
      console.error("Specify a valid selector for the carousel.");
      return null;
    }

    if (!this.slides.length) {
      console.error("Specify a valid selector for slides.");
      return null;
    }
  }

  // Utility function to create DOM elements
  createElement(tag, attributes = {}, children = []) {
    const element = document.createElement(tag);

    // Set attributes
    Object.entries(attributes).forEach(([key, value]) => {
      element.setAttribute(key, value);
    });

    // Add children
    if (typeof children === "string") {
      element.textContent = children;
    } else {
      children.forEach((child) => {
        if (typeof child === "string") {
          element.appendChild(document.createTextNode(child));
        } else {
          element.appendChild(child);
        }
      });
    }

    return element;
  }

  // Initialize carousel structure
  initStructure() {
    this.carousel.setAttribute("tabindex", "0");

    // Create carousel inner container
    const carouselInner = this.createElement("div", {
      class: "carousel-inner",
    });
    this.carousel.insertBefore(carouselInner, this.slides[0]);

    // Move slides to inner container
    this.slides.forEach((slide) => carouselInner.appendChild(slide));

    // Create navigation buttons
    this.prevBtn = this.createElement(
      "btn",
      {
        class: "carousel-btn carousel-btn--prev-next carousel-btn--prev",
        "aria-label": "Previous Slide",
      },
      "<"
    );

    this.nextBtn = this.createElement(
      "btn",
      {
        class: "carousel-btn carousel-btn--prev-next carousel-btn--next",
        "aria-label": "Next Slide",
      },
      ">"
    );

    carouselInner.appendChild(this.prevBtn);
    carouselInner.appendChild(this.nextBtn);

    // Create pagination if enabled
    if (this.enablePagination) {
      this.paginationContainer = this.createElement("nav", {
        class: "carousel-pagination",
        role: "tablist",
      });

      this.carousel.appendChild(this.paginationContainer);

      // Add pagination buttons
      this.slides.forEach((_, index) => {
        const paginationBtn = this.createElement(
          "btn",
          {
            class: `carousel-btn caroursel-btn--${index + 1}`,
            role: "tab",
          },
          `${index + 1}`
        );

        if (index === 0) {
          paginationBtn.classList.add("carousel-btn--active");
          paginationBtn.setAttribute("aria-selected", "true");
        }

        paginationBtn.addEventListener("click", () => this.goToSlide(index));
        this.paginationContainer.appendChild(paginationBtn);
      });
    }

    // Set initial slide positions
    this.updateSlidePositions();
  }

  // Update slide positions based on current index
  updateSlidePositions() {
    this.slides.forEach((slide, index) => {
      slide.style.transform = `translateX(${
        100 * (index - this.currentSlideIndex)
      }%)`;
    });
  }

  // Update pagination buttons state
  updatePagination() {
    if (!this.enablePagination) return;

    const paginationBtns =
      this.paginationContainer.querySelectorAll(".carousel-btn");
    paginationBtns.forEach((btn, index) => {
      if (index === this.currentSlideIndex) {
        btn.classList.add("carousel-btn--active");
        btn.setAttribute("aria-selected", "true");
      } else {
        btn.classList.remove("carousel-btn--active");
        btn.removeAttribute("aria-selected");
      }
    });
  }

  // Navigate to specific slide
  goToSlide(index) {
    this.currentSlideIndex = index;
    this.updateSlidePositions();
    this.updatePagination();
  }

  // Navigate to next slide
  nextSlide() {
    this.currentSlideIndex = (this.currentSlideIndex + 1) % this.slides.length;
    this.updateSlidePositions();
    this.updatePagination();
  }

  // Navigate to previous slide
  prevSlide() {
    this.currentSlideIndex =
      (this.currentSlideIndex - 1 + this.slides.length) % this.slides.length;
    this.updateSlidePositions();
    this.updatePagination();
  }

  // Set up event listeners
  setupEventListeners() {
    this.prevBtn.addEventListener("click", () => this.prevSlide());
    this.nextBtn.addEventListener("click", () => this.nextSlide());
  }

  // Initialize carousel
  create() {
    this.initStructure();
    this.setupEventListeners();
  }

  // Clean up event listeners
  destroy() {
    this.prevBtn.removeEventListener("click", () => this.prevSlide());
    this.nextBtn.removeEventListener("click", () => this.nextSlide());

    if (this.enablePagination) {
      const paginationBtns =
        this.paginationContainer.querySelectorAll(".carousel-btn");
      paginationBtns.forEach((btn, index) => {
        btn.removeEventListener("click", () => this.goToSlide(index));
      });
    }
  }
}

// Initialize carousel
const carousel1 = new JSCarousel({
  carouselSelector: "#carousel-1",
  slideSelector: ".slide",
});

carousel1.create();

// Clean up on page unload
window.addEventListener("pagehide", () => {
  if (carousel1) {
    carousel1.destroy();
  }
});
