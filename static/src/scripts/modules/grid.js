import Macy from 'macy';

const Grid = {
  init(element = document) {
    this.triggers = Array.from(element.querySelectorAll('[data-grid]'));

    if (this.triggers.length) {
      this.triggers.forEach(trigger => {
        Macy({
          container: trigger,
          columns: 1,
          trueOrder: true,
          margin: 30,
          waitForImages: true,
          mobileFirst: true,
          breakAt: {
            768: { columns: 2 },
            1080: { columns: 3 },
          }
        });
      });
    }
  },
};

export default Grid;
