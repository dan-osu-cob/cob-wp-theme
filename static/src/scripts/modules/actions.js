const axios = require('axios');

const Actions = {
  triggers: [],

  init(element = document) {
    const triggers = element.querySelectorAll('[data-action]');

    if (triggers.length) {
      this.triggers = triggers;
    }

    if (this.triggers.length) {
      [].forEach.call(this.triggers, trigger => {
        const event = trigger.dataset.actionEvent;

        if (event === 'load') {
          window.addEventListener(event, ev => this.handleEvent(ev, trigger));
        } else {
          trigger.addEventListener(event, ev => this.handleEvent(ev));
        }
      });
    }
  },

  handleEvent(ev, trigger) {
    const element = trigger || ev.currentTarget;

    if (element.getAttribute('action') === '#') {
      ev.preventDefault();
      ev.stopPropagation();
    }

    this.runAction(element, element.dataset);
  },

  runAction(trigger, data) {
    const formData = new FormData(trigger.hasAttribute('action') ? trigger : document.createElement('form'));

    // Enable loading indicator
    trigger.classList.add('is-loading');

    // Extend formData with trigger data attributes
    Object.keys(data).forEach(key => {
      if (key === 'actionData') {
        const actionData = JSON.parse(data[key]);

        Object.keys(actionData).forEach(dataKey => {
          formData.append(dataKey, actionData[dataKey]);
        });
      } else {
        formData.append(key, data[key]);
      }
    });

    if (trigger.hasAttribute('name')) {
      const option = trigger.options[trigger.selectedIndex];

      if (option && typeof option.value !== 'undefined') {
        data.value = option.dataset.value;
        formData.append('value', option.value);
      }
    }

    axios
      .post(document.body.dataset.ajaxSource, formData, {
        processData: false,
        contentType: 'multipart/form-data',
      })

      // Run action callback
      .then(response => {
        if (this.callbacks[data.action]) {
          setTimeout(() => this.callbacks[data.action](trigger, response, data), 250);
        }
      })

      // Log errors
      .catch(response => {
        if (this.callbacks[data.action]) {
          setTimeout(() => this.callbacks[data.action](trigger, response, data), 500);
        }
      })

      // Disable loading indicator
      .then(() => setTimeout(() => trigger.classList.remove('is-loading'), 250));
  },

  handlers: {
    contact: (trigger, { data }, { actionSalted }) => {
      const element = document.querySelector(`[data-action-salted=${actionSalted}]`);
      const content = element.querySelector('[data-action-content]');
      const message = element.querySelector('[data-action-message]');

      if (message) {
        message.classList.add('is-visible');
        message.dataset.status = data.success ? 'success' : 'error';
        message.innerHTML = data.data.replace(new RegExp('\\\\"', 'g'), '"');

        if (data.success) {
          content.style.display = 'none';
        }
      }
    },
  },

  callbacks: {
    contact: (...args) => Actions.handlers.contact(...args),
  },
};

export default Actions;
