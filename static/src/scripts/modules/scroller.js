const Scroller = {
  triggers: [],
  offset: 78,

  init(element = document) {
    const triggers = element.querySelectorAll('[data-scroll], a[href^="#"]');

    if (triggers.length) {
      this.triggers = triggers;
    }

    if (this.triggers.length) {
      [].forEach.call(this.triggers, trigger => trigger.addEventListener('click', this.handleScroll.bind(this)));
    }
  },

  handleScroll(ev) {
    const trigger = ev.currentTarget;
    let target = trigger.dataset.scroll || trigger.getAttribute('href');

    if (target && target.length > 1) {
      // Remove everything before the "#"
      target = target.substring(target.indexOf('#'));

      // Scroll to target
      if (this.scrollTo(target)) {
        // Prevent page jumping
        ev.preventDefault();
      }
    }
  },

  scrollTo(target, offset = 0) {
    const element = document.querySelector(target);

    if (element) {
      window.scrollTo({
        behavior: 'smooth',
        'top': this.getElementOffset(element).top - this.offset - offset,
      });

      // Close panels
      document.dispatchEvent(new CustomEvent('dropdown:close'));
      document.dispatchEvent(new CustomEvent('panels:close'));
    }

    return element;
  },

  getElementOffset(element) {
    const rect = element.getBoundingClientRect();
    const scrollLeft = window.pageXOffset || document.documentElement.scrollLeft;
    const scrollTop = window.pageYOffset || document.documentElement.scrollTop;

    return {
      top: rect.top + scrollTop,
      left: rect.left + scrollLeft,
    };
  },
};

export default Scroller;
