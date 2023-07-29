<a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

  
  <!-- Vendor JS Files -->
  <script src="assets/vendor/apexcharts/apexcharts.min.js"></script>
  <script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="assets/vendor/chart.js/chart.umd.js"></script>
  <script src="assets/vendor/echarts/echarts.min.js"></script>
  <script src="assets/vendor/quill/quill.min.js"></script>
  <script src="assets/vendor/simple-datatables/simple-datatables.js"></script>
  <script src="assets/vendor/tinymce/tinymce.min.js"></script>
  <script src="assets/vendor/php-email-form/validate.js"></script>
  <script src="assets/vendor/jquery/jquery-3.6.0.min.js"></script>

  <!-- Fir Date Time Picker -->
<script src="assets/vendor/bootstrap/js/moment.min.js"></script>
<script src="assets/vendor/bootstrap/js/bootstrap-datetimepicker.min.js"></script>

  <!-- Template Main JS File -->
  <script src="assets/js/main.js"></script>

  <!-- Custom Login Form JS -->
  <script>
  function votePresident(selectedCheckbox) {
    const checkboxes = document.querySelectorAll('input[type="checkbox"][name="vote-president"]');

    // Uncheck all checkboxes except the one that was clicked
    checkboxes.forEach((checkbox) => {
      if (checkbox !== selectedCheckbox) {
        checkbox.checked = false;
      }
    });
  }

  function voteMpMale(selectedCheckbox) {
    const checkboxes = document.querySelectorAll('input[type="checkbox"][name="vote-mp-male"]');

    // Uncheck all checkboxes except the one that was clicked
    checkboxes.forEach((checkbox) => {
      if (checkbox !== selectedCheckbox) {
        checkbox.checked = false;
      }
    });
  }

function voteMpFemale(selectedCheckbox) {
  const checkboxes = document.querySelectorAll('input[type="checkbox"][name="vote-mp-female"]');

  // Uncheck all checkboxes except the one that was clicked
  checkboxes.forEach((checkbox) => {
    if (checkbox !== selectedCheckbox) {
      checkbox.checked = false;
    }
  });
}
</script>

  <script>
    var loginForm = document.getElementById('login-form');
    var usernameInput = document.getElementById('username');
    var passwordInput = document.getElementById('password');
    var usernameErrorMsg = document.getElementById('username-error-msg');
    var passwordErrorMsg = document.getElementById('password-error-msg');

    loginForm.addEventListener('submit', function(event) {
        event.preventDefault();
        event.stopPropagation();

        // Reset error messages
        usernameErrorMsg.textContent = '';
        passwordErrorMsg.textContent = '';

        if (!loginForm.checkValidity()) {
            // Show custom error messages for unvalidated fields
            if (!usernameInput.checkValidity()) {
                if (usernameInput.validity.valueMissing) {
                    usernameErrorMsg.textContent = 'Please enter a username.';
                }
            }

            if (!passwordInput.checkValidity()) {
                if (passwordInput.validity.valueMissing) {
                    passwordErrorMsg.textContent = 'Please provide a password.';
                }
            }
        } else {
            // If form is valid, proceed with AJAX call to the server
            var formData = new FormData(loginForm);
            $.ajax({
                url: 'controllers/app.php?action=login',
                method: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                error: function(err) {
                    console.log(err);
                },
                success: function(resp) {
                    var response = JSON.parse(resp);
                    if (response.status === 'success') {
                        location.href = response.redirect_url;
                    } else {
                        // Show the error message received from the server
                        if (response.status === 'username') {
                          $('#login-form').prepend('<div class="alert alert-danger">'+response.message+'</div>');
                          $('#login-form button[type="button"]').removeAttr('disabled').html('login');
                          usernameInput.classList.add('is-invalid');
                        } else {
                          $('#login-form').prepend('<div class="alert alert-danger">'+response.message+'</div>');
                          $('#login-form button[type="button"]').removeAttr('disabled').html('login');
                          passwordInput.classList.add('is-invalid');
                        }
                    }
                }
            });
          }

        loginForm.classList.add('was-validated');
    }, false);
  </script>
  <script>
$('#add-election-form').submit(function(e){
		e.preventDefault()

    let isValid = true;
    $("#add-election-form").find("input, select, textarea").each(function () {
      const input = $(this);
      const value = input.val().trim();
      if (value === "") {
        isValid = false;
        input.addClass("is-invalid"); // Add a CSS class for invalid inputs
        input.siblings(".invalid-feedback").text("This field is required."); // Show error message
      } else {
        input.removeClass("is-invalid"); // Remove the CSS class if input is valid
        input.siblings(".invalid-feedback").text(""); // Clear error message
      }
    });

    if (isValid) {

      $.ajax({
        url:'controllers/app.php?action=add_election',
        method:'POST',
        data:$(this).serialize(),
        success:function(resp){
          var response = JSON.parse(resp);
          if (response.status === 'success') {
            location.href = response.redirect_url;
          }
          else if(resp==2){
            $('#add-election-form').prepend('<div class="alert alert-danger">'+resp+'</div>');

          }
          else {
            $('#add-election-form').prepend('<div class="alert alert-danger">'+response.message+'</div>');
          }
        }
      })
    }
	})
  // Real-time validation when the user enters the required field
  $("#add-election-form").find("input, select, textarea").on("input", function () {
    const input = $(this);
    const value = input.val().trim();
    const errorMessage = input.data("error-message");

    if (value === "") {
      input.addClass("is-invalid"); // Add a CSS class for invalid inputs
      input.siblings(".invalid-feedback").text(errorMessage); // Show error message
    } else {
      input.removeClass("is-invalid"); // Remove the CSS class if input is valid
      input.siblings(".invalid-feedback").text(""); // Clear error message
    }
  });
  
  </script>