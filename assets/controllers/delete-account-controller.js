import {Controller} from '@hotwired/stimulus';
import {average} from 'color.js'
import 'jquery'
import Chart from 'chart.js/auto';
import {getRelativePosition} from 'chart.js/helpers';

require('../js/plugins/ajax_link');
require('../js/plugins/rating');
require('../js/plugins/copy_to_clipboard');

export default class extends Controller {
  connect() {

    function toggleMapperNameField() {
      const keepRadio = $('#keep_songs');
      const mapperNameField = $('#mapper_name_field');
      const mapperNameInput = $('#mapper_name');

      if (keepRadio.is(':checked')) {
        mapperNameField.show();
        mapperNameInput.prop('required', true);
      } else {
        mapperNameField.hide();
        mapperNameInput.removeProp('required', true);
      }
    }

    $(function () {
      const deleteRadio = $('#delete_songs');
      const keepRadio = $('#keep_songs');


      deleteRadio.on('change', toggleMapperNameField);
      keepRadio.on('change', toggleMapperNameField);
      keepRadio.trigger('change', toggleMapperNameField);
    });
  }

  disconnect() {
    $("#main").attr('style', " background: transparent");
  }

  back() {
    // history.back();// Swup instance
    //  return false;
  }
}
