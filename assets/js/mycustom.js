// Ensure the DOM is fully loaded
document.addEventListener("DOMContentLoaded", function () {
  // Set custom date format and time picker
  const dateFormat = 'ddd DD-MMM-YYYY';
  const yearFormat = 'YYYY';
  const today = moment().startOf('day');

  // Initialize the DateTimePicker for 'year' input field
  $('#year').datetimepicker({
    format: yearFormat,
    useCurrent: true,
    minDate: today
  });

  // Initialize the DateTimePicker for 'starttime' input field
  $('#starttime').datetimepicker({
    format: dateFormat,
    useCurrent: true,
    minDate: today
  });

  // Initialize the DateTimePicker for 'endtime' input field
  $('#endtime').datetimepicker({
    format: dateFormat,
    useCurrent: false,
    minDate: today
  });

  // Update 'endtime' picker's minimum date based on 'starttime' picker's value
  $("#starttime").on("dp.change", function (e) {
    $('#endtime').data("DateTimePicker").minDate(e.date);
  });

  // Update 'starttime' picker's maximum date based on 'endtime' picker's value
  $("#endtime").on("dp.change", function (e) {
    $('#starttime').data("DateTimePicker").maxDate(e.date);
  });

  $(document).ready(function () {
    $('[data-toggle="tooltip"]').tooltip();
  });
});



