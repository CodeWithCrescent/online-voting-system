// Ensure the DOM is fully loaded
document.addEventListener("DOMContentLoaded", function () {
  // Set custom date format and time picker
  const dateFormat = 'dddd, MMMM D';
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
});

$(document).ready(function() {
  $('[data-toggle="tooltip"]').tooltip();
});

const openModalButtons = document.querySelectorAll('.open-modal');

openModalButtons.forEach(button => {
  button.addEventListener('click', function (event) {
    event.preventDefault();
    const itemId = button.dataset.id;

    // Send an AJAX request to the server to get the specific data for itemId
    fetch('app.php?election_id=' + itemId)
      .then(response => response.json())
      .then(data => {
        // Update the modal content with the specific data
        if (data.error) {
          console.error(data.error);
          return;
        }

        const modalTitle = document.querySelector('#addElection .modal-title');
        modalTitle.textContent = data.title;

        const modalBody = document.querySelector('#addElection .modal-body');
        modalBody.innerHTML = `
          <p>Year: ${data.year}</p>
          <p>Voters: ${data.voters}</p>
          <p>Start Time: ${data.starttime}</p>
          <p>End Time: ${data.endtime}</p>
          <p>Description: ${data.description}</p>
        `;
      })
      .catch(error => {
        console.error('Error fetching data:', error);
      });
  });
});

