import Lightpick from 'lightpick';

export default function () {
  const bp576 = window.matchMedia('(min-width: 576px)');
  const cls = document.querySelectorAll('.js-calendar');
  const expression = /(Mac|iPhone|iPod|iPad)/i;

  const params = new URLSearchParams(window.location.search);
  const date_range = params.get('date_range');
  var date_start = null;
  var date_end = null;

  if (date_range) {
    let date_range_array = date_range.split('-');
    let date_start_str = date_range_array[0].trim();
    let date_end_str = date_range_array[1].trim();
    let date_start_array = date_start_str.split('.');
    let date_end_array = date_end_str.split('.');
    date_start = new Date(
      date_start_array[2],
      date_start_array[1]-1, // why!  i don't know)
      date_start_array[0]
    );
    date_end = new Date(
      date_end_array[2],
      date_end_array[1]-1, // why!  i don't know)
      date_end_array[0]
    );
  }

  if (cls) {
    let picker;
    const checkCalendarPosition = bp => {
      if (bp.matches) {
        picker && picker.reloadOptions({
          orientation: 'right'
        });
      } else {
        picker && picker.reloadOptions({
          orientation: 'left'
        });
      }
    }

    cls.forEach(calendar => {
      picker = new Lightpick({
        field: calendar,
        singleDate: false,
        format: 'DD.MM.YYYY',
        hoveringTooltip: false,
        onOpen() {
          calendar.parentElement.classList.add('opened');
        },
        onClose() {
          calendar.parentElement.classList.remove('opened');
          document.getElementById("order_form").submit();
        },
      });

      if (date_start && date_end) {
        picker.setDateRange(date_start, date_end);
      }
    });

    if (expression.test(window.navigator)) {
      bp576.addEventListener('change', checkCalendarPosition);
    } else {
      bp576.onchange = checkCalendarPosition;
    }
    checkCalendarPosition(bp576);
  }
}