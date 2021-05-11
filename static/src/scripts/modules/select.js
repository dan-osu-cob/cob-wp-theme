import $ from 'jquery';
import select2 from 'select2';

const Select = {
  element: '[data-select]',

  init() {
    // Hook up select2 to jQuery
    select2($);

    $(this.element).select2({
      minimumResultsForSearch: Infinity,
      placeholder: '- Select -',
    });
  },
};

export default Select;
