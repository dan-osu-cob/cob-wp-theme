const Panels = {
  init() {
    document.addEventListener('keyup', this.keyListener.bind(this), false);
    document.addEventListener('click', this.clickListener.bind(this), false);
    document.addEventListener('touchend', this.clickListener.bind(this), false);
  },

  keyListener(ev) {
    if (ev.keyCode === 27) {
      document.dispatchEvent(new CustomEvent('panels:close'));
    }
  },

  clickListener(ev) {
    const target = [ev.target];
    const path = ev.path || (ev.composedPath && ev.composedPath());

    if (path) {
      if (!this.listenerMatcher(path, 'dropdown')) {
        document.dispatchEvent(new CustomEvent('dropdown:close'));
      }
    }

    if (target) {
      if (this.listenerMatcher(target, /popup($|\s)/)) {
        document.dispatchEvent(new CustomEvent('popup:close'));
      }
    }
  },

  listenerMatcher(path, keyword) {
    return path.some(element => {
      let isRegExp = keyword instanceof RegExp;
      let matchClass = (element instanceof HTMLElement && element.className && (isRegExp ? keyword.test(element.className) : element.className.includes(keyword)));
      let matchDataset = (element.dataset && element.dataset[keyword]);

      return isRegExp ? matchClass : matchClass || matchDataset;
    });
  },
};

export default Panels;
