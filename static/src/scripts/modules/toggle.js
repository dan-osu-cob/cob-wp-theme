const Toggle = {
  init(element = document) {
    this.triggers = Array.from(element.querySelectorAll('[data-toggle]'));

    if (this.triggers.length) {
      this.triggers.forEach(trigger => {
        trigger.addEventListener('click', this.handleToggle.bind(this));
      });
    }

    document.addEventListener('panels:close', e => this.closeToggles(e.detail ? e.detail.excludes : null), false);
    document.addEventListener('toggle:close', e => this.closeToggles(e.detail ? e.detail.excludes : null), false);
  },

  handleToggle(ev) {
    ev.preventDefault();
    var trigger = ev.currentTarget.dataset.toggle;

    // Close other dropdowns
    this.closeToggles(trigger);

    document.body.classList.toggle(`has-${trigger}-open`);
  },

  closeToggles(excludes) {
    this.triggers.forEach(trigger => {
      trigger = trigger.dataset.toggle;

      if ((!excludes || !excludes.includes(trigger)) && document.body.classList.contains(`has-${trigger}-open`)) {
        document.body.classList.remove(`has-${trigger}-open`);
      }
    });
  },
};

export default Toggle;
