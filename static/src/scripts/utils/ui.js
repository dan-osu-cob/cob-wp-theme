import toggle from '../modules/toggle';
import scroller from '../modules/scroller';
import sticky from '../modules/sticky';
import grid from '../modules/grid';
import select from '../modules/select';
import actions from '../modules/actions';

const Ui = {
  init(element) {
    toggle.init(element);
    scroller.init(element);
    sticky.init(element);
    grid.init(element);
    select.init(element);
    actions.init(element);
  },
};

export default Ui;
