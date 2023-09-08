<a href="#" id="hide" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

<!-- Vendor JS Files -->
<script src="assets/vendor/apexcharts/apexcharts.min.js"></script>
<script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="assets/vendor/chart.js/chart.umd.js"></script>
<script src="assets/vendor/echarts/echarts.min.js"></script>
<script src="assets/vendor/quill/quill.min.js"></script>
<!-- <script src="assets/vendor/simple-datatables/simple-datatables.js"></script> -->
<script src="assets/vendor/tinymce/tinymce.min.js"></script>
<script src="assets/vendor/php-email-form/validate.js"></script>
<script src="assets/vendor/jquery/jquery-3.6.0.min.js"></script>
<script src="assets/vendor/toastr/toastr.min.js"></script>
<!-- <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script> -->
<script src="assets/vendor/datatables/dataTables.js"></script>
<script src="assets/vendor/datatables/bootstrap5.min.js"></script>
<script src="assets/vendor/easy-pie-chart/jquery.easypiechart.min.js"></script>

<!-- For Date Time Picker -->
<script src="assets/vendor/bootstrap/js/moment.min.js"></script>
<script src="assets/vendor/bootstrap/js/bootstrap-datetimepicker.min.js"></script>

<!-- Template Main JS File -->
<script src="assets/js/main.js"></script>

<script>
  function showLoadingOverlay() {
    var loadingOverlay = $("<div id='loadingOverlay' class='d-flex justify-content-center align-items-center' style='position: fixed; top: 0; left: 0; width: 100%; height: 100%; background-color: #ffffff82; z-index: 9999;'>" +
      "<div class='spinner-border' role='status' style='width: 4rem; height: 4rem; color: #012970;'>" +
      "<span class='sr-only'>Loading...</span>" +
      "</div>" +
      "</div>");

    $(document.body).append(loadingOverlay);
  }

  function hideLoadingOverlay() {
    $('#loadingOverlay').remove();
  }
</script>

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

      var formData = new FormData(loginForm);
      $.ajax({
        url: 'controllers/app.php?action=login',
        method: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        beforeSend: function() {
          showLoadingOverlay();
        },
        error: function(err) {
          console.log(err);
        },
        success: function(resp) {
          var response = JSON.parse(resp);
          if (response.status === 'success') {
            location.href = response.redirect_url;
          } else {
            hideLoadingOverlay();
            // Show the error message received from the server
            if (response.status === 'username') {
              $('#login-form').prepend('<div class="alert alert-danger">' + response.message + '</div>');
              $('#login-form button[type="button"]').removeAttr('disabled').html('login');
              usernameInput.classList.add('is-invalid');
            } else {
              $('#login-form').prepend('<div class="alert alert-danger">' + response.message + '</div>');
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
  $(document).on('submit', '#change-password-form', function(e) {
    e.preventDefault();

    var formData = $(this).serialize();

    $.ajax({
      type: 'POST',
      url: 'controllers/app.php?action=change_password',
      data: formData,
      dataType: 'json',
      beforeSend: function() {
        showLoadingOverlay();
      },
      success: function(response) {
        console.log(response);
        if (response.status === 'success') {
          toastr.success(response.message);
          $('#change-password-form')[0].reset();
          setTimeout(function() {
            location.reload();
          }, 1000);
        } else if (response.status === 'error') {
          hideLoadingOverlay();
          $('#change-password-form').prepend('<div class="alert alert-danger">' + response.message + '</div>');
        } else {
          $('#change-password-form').prepend('<div class="alert alert-danger">Unknown error occurred.</div>');
        }
      },
      error: function(xhr, status, error) {
        // Handle AJAX errors, if any
        console.error(error);
        $('#change-password-form').prepend('<div class="alert alert-danger">An error occurred during the request.</div>');
      }
    });
  });
</script>

<script>
  $(document).ready(function() {
    $(".reset").click(function(e) {
      e.preventDefault();

      var userId = $(this).data("id");
      var actionName = $(this).data("name");

      var confirmReset = confirm("Are you sure you want to " + actionName + " this user's password?");

      if (confirmReset) {
        $.ajax({
          type: 'POST',
          url: 'controllers/app.php?action=reset_password',
          data: {
            user_id: userId
          },
          dataType: 'json',
          beforeSend: function() {
            showLoadingOverlay();
          },
          complete: function() {
            hideLoadingOverlay();
          },
          success: function(response) {
            if (response.status === "success") {
              toastr.success(response.message);
            } else {
              toastr.error(response.message);
            }
          },
          error: function() {
            toastr.error("An error occurred while processing the request.");
          }
        });
      }
    });
  });
</script>


<script>
  $('#add-election-form').submit(function(e) {
    e.preventDefault();

    let isValid = true;
    $("#add-election-form").find("input, select, textarea").each(function() {
      const input = $(this);
      const value = input.val().trim();
      const errorMessage = input.data("error-message");

      if (value === "") {
        isValid = false;
        input.addClass("is-invalid");
        input.siblings(".invalid-feedback").text(errorMessage);
      } else {
        input.removeClass("is-invalid");
        input.siblings(".invalid-feedback").text("");
      }
    });

    if (isValid) {
      const starttime = $('#starttime').val();
      const endtime = $('#endtime').val();

      const startTimestamp = moment.utc(starttime).format('YYYY-MM-DD HH:mm').valueOf();
      const endTimestamp = moment.utc(endtime).format('YYYY-MM-DD HH:mm').valueOf();

      $.ajax({
        url: 'controllers/app.php?action=add_election',
        method: 'POST',
        data: {
          title: $('#title').val(),
          year: $('#year').val(),
          voters: $('#voters').val(),
          starttime: startTimestamp,
          endtime: endTimestamp,
          description: $('#description').val()
        },
        dataType: 'json',
        beforeSend: function() {
          showLoadingOverlay();
        },
        success: function(response) {
          if (response.status === 'success') {
            location.href = response.redirect_url;
          } else if (resp == 2) {
            hideLoadingOverlay();
            $('#add-election-form').prepend('<div class="alert alert-danger">' + resp + '</div>');
          } else {
            hideLoadingOverlay();
            $('#add-election-form').prepend('<div class="alert alert-danger">' + response.message + '</div>');
          }
        }
      });
    }
  });

  // Real-time validation when the user enters the required field
  $("#add-election-form").find("input, select, textarea").on("input", function() {
    const input = $(this);
    const value = input.val().trim();
    const errorMessage = input.data("error-message");

    if (value === "") {
      input.addClass("is-invalid");
      input.siblings(".invalid-feedback").text(errorMessage);
    } else {
      input.removeClass("is-invalid");
      input.siblings(".invalid-feedback").text("");
    }
  });
</script>

<!-- Edit Election -->
<script>
  $('.edit-election-modal').click(function() {
    editElection("Edit Election", 'edit_election.php?id=' + $(this).attr('data-id'))
  })

  window.editElection = function($title = '', $url = '') {
    $.ajax({
      url: $url,
      error: err => {
        console.log()
        alert("An error occured")
      },
      beforeSend: function() {
        showLoadingOverlay();
      },
      success: function(resp) {
        if (resp) {
          hideLoadingOverlay();
          $('#editElection .modal-title').html($title)
          $('#editElection .modal-body').html(resp)
          $('#editElection').modal('show')
        }
      }
    })
  }

  // jQuery AJAX form submission
  $(document).on('submit', '#update-election-form', function(e) {
    e.preventDefault();

    let isValid = true;
    $("#update-election-form").find("input, select, textarea").each(function() {
      const input = $(this);
      const value = input.val().trim();
      const errorMessage = input.data("error-message");

      if (value === "") {
        isValid = false;
        input.addClass("is-invalid");
        input.siblings(".invalid-feedback").text(errorMessage);
      } else {
        input.removeClass("is-invalid");
        input.siblings(".invalid-feedback").text("");
      }
    });

    if (isValid) {

      var formData = $(this).serialize();

      $.ajax({
        type: 'POST',
        url: 'controllers/app.php?action=update_election',
        data: formData,
        dataType: 'json',
        beforeSend: function() {
          showLoadingOverlay();
        },
        success: function(response) {
          console.log(response);
          if (response.status === 'success') {
            location.href = response.redirect_url;
          } else if (response.status === 'error') {
            hideLoadingOverlay();
            $('#update-election-form').prepend('<div class="alert alert-danger">' + response.message + '</div>');
          } else {
            hideLoadingOverlay();
            $('#update-election-form').prepend('<div class="alert alert-danger">Unknown error occurred.</div>');
          }
        },
        error: function(xhr, status, error) {
          // Handle AJAX errors
          console.error(error);
          hideLoadingOverlay();
          $('#update-election-form').prepend('<div class="alert alert-danger">An error occurred during the request.</div>');
        }
      });
    }
  })
  // Real-time validation when the user enters the required field
  $("#update-election-form").find("input, select, textarea").on("input", function() {
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

<script>
  $('.open-modal').click(function() {
    viewElection("Election Overview", 'view_election.php?id=' + $(this).attr('data-id'))
  })
</script>

<script>
  window.viewElection = function($title = '', $url = '') {
    $.ajax({
      url: $url,
      error: err => {
        console.log()
        alert("An error occured")
      },
      beforeSend: function() {
        showLoadingOverlay();
      },
      success: function(resp) {
        if (resp) {
          hideLoadingOverlay();
          $('#viewElection .modal-title').html($title)
          $('#viewElection .modal-body').html(resp)
          $('#viewElection').modal('show')
        }
      }
    })
  }
</script>

<!-- JavaScript code to handle the delete election confirmation -->
<script>
  $(document).on('click', '.delete-election', function(e) {
    e.preventDefault();

    var electionId = $(this).data('id');
    var electionTitle = $(this).data('name');

    var confirmed = confirm('Are you sure you want to delete ' + electionTitle + '?');

    // If the user confirms, proceed with deletion
    if (confirmed) {
      $.ajax({
        type: 'POST',
        url: 'controllers/app.php?action=delete_election',
        data: {
          election_id: electionId
        },
        dataType: 'json',
        beforeSend: function() {
          showLoadingOverlay();
        },
        success: function(response) {
          console.log(response);
          if (response.status === 'success') {
            // location.href = response.redirect_url;
            toastr.success(response.message);
            setTimeout(function() {
              location.reload();
            }, 1500);
          } else {
            toastr.error(response.message);
          }
        },
        error: function(xhr, status, error) {
          // Handle AJAX errors, if any
          console.error(error);
          hideLoadingOverlay();
          toastr.error('An error occurred during the request.');
        }
      });
    } else {
      // User canceled the deletion
      toastr.info('Deletion canceled.');
    }
  });
</script>

<!-- JavaScript code to handle the status confirmation -->
<script>
  $(document).on('click', '.election-status', function(e) {
    e.preventDefault();

    var electionId = $(this).data('id');
    var status = $(this).data('status');
    var statusName = $(this).data('name');

    var confirmed = confirm('Are you sure you want to ' + statusName + ' this Election?');

    // If the user confirms, proceed with change election status
    if (confirmed) {
      $.ajax({
        type: 'POST',
        url: 'controllers/app.php?action=election_status',
        data: {
          election_id: electionId,
          status: status
        },
        dataType: 'json',
        beforeSend: function() {
          showLoadingOverlay();
        },
        success: function(response) {
          console.log(response);
          if (response.status === 'success') {
            toastr.success(response.message);
            setTimeout(function() {
              location.reload();
            }, 1000);
          } else {
            toastr.error(response.message);
          }
        },
        error: function(xhr, status, error) {
          // Handle AJAX errors, if any
          console.error(error);
          hideLoadingOverlay();
          toastr.error('An error occurred during the request.');
        }
      });
    } else {
      // User canceled to change status
      toastr.info('Status change canceled.');
    }
  });
</script>

<!-- Add Category -->
<script>
  $('.add-category').click(function() {
    addCategory("Add Category", 'add_category.php?election_id=' + $(this).attr('data-id'))
  })

  window.addCategory = function($title = '', $url = '') {
    $.ajax({
      url: $url,
      error: err => {
        console.log()
        alert("An error occured")
      },
      beforeSend: function() {
        showLoadingOverlay();
      },
      success: function(resp) {
        if (resp) {
          hideLoadingOverlay();
          $('#addCategory .modal-title').html($title)
          $('#addCategory .modal-body').html(resp)
          $('#addCategory').modal('show')
        }
      }
    })
  }
</script>

<script>
  // fetch and display categories based on the selected election_id
  function fetchCategories(electionId) {
    $.ajax({
      url: 'fetch_categories.php',
      type: 'GET',
      data: {
        election_id: electionId
      },
      dataType: 'json',
      beforeSend: function() {
        showLoadingOverlay();
      },
      complete: function() {
        hideLoadingOverlay();
      },
      success: function(data) {
        $('#categoriesTableBody').empty();

        $.each(data, function(index, category) {
          var row = '<tr>' +
            '<td>' + (index + 1) + '</td>' +
            '<td>' + category.name + '</td>' +
            '<td class="text-center">' +
            '<a href="#" class="btn btn-primary btn-sm edit-category" data-id="' + category.id + '" data-name="' + category.name + '">' +
            '<i class="bi bi-pencil"></i> Edit' +
            '</a>' +
            '<a href="#" class="btn btn-danger btn-sm ms-3 category-delete" data-id="' + category.id + '" data-name="' + category.name + '">' +
            '<i class="bi bi-trash"></i> Delete' +
            '</a>' +
            '</td>' +
            '</tr>';

          $('#categoriesTableBody').append(row);
        });

        var btn = '<div>' +
          '<a href="controllers/export_excel.php?action=export_categories&election_id=' + electionId + '" class="btn btn-primary col-4 offset-4">EXPORT EXCEL</a>' +
          '</div>';

        // $('.section').append(btn);

        // Show the table or hide it
        if (data.length > 0) {
          $('#categoriesTable').show();
        } else {
          $('#categoriesTable').hide();
        }
      },
      error: function(xhr, status, error) {
        console.log('Error: ' + error);
      }
    });
  }

  $('#election').on('change', function() {
    var selectedElectionId = $(this).val();
    if (selectedElectionId !== '') {
      fetchCategories(selectedElectionId);
    } else {
      $('#categoriesTable').hide();
    }
  });
</script>
<script>
  // Function to enable or disable the "Add New" button based on the selected election_id
  function updateAddButtonState() {
    var selectedElectionId = $('#election').val();
    var addButton = $('.add-category');

    if (selectedElectionId !== '') {
      addButton.removeClass('disabled');
      addButton.attr('data-id', selectedElectionId);
    } else {
      addButton.addClass('disabled');
      addButton.attr('data-id', '');
    }
  }

  // Event listener for select element to update the "Add New" button state
  $('#election').on('change', function() {
    updateAddButtonState();
  });
  0
  // Initial call to update the "Add New" button state on page load
  updateAddButtonState();
</script>




<!-- Edit Category -->
<script>
  $(document).on('click', '.edit-category', function(e) {
    editCategory("Edit Category", 'edit_category.php?id=' + $(this).attr('data-id'))
  })

  window.editCategory = function($title = '', $url = '') {
    // start_load()
    $.ajax({
      url: $url,
      error: err => {
        console.log()
        alert("An error occured")
      },
      beforeSend: function() {
        showLoadingOverlay();
      },
      complete: function() {
        hideLoadingOverlay();
      },
      success: function(resp) {
        if (resp) {
          $('#editCategory .modal-title').html($title)
          $('#editCategory .modal-body').html(resp)
          $('#editCategory').modal('show')
          // end_load()
        }
      }
    })
  }
</script>

<script>
  // jQuery AJAX form submission
  $(document).on('submit', '#update-category-form', function(e) {
    e.preventDefault();

    var formData = $(this).serialize();

    $.ajax({
      type: 'POST',
      url: 'controllers/app.php?action=update_category',
      data: formData,
      dataType: 'json',
      beforeSend: function() {
        showLoadingOverlay();
      },
      success: function(response) {
        console.log(response);
        if (response.status === 'success') {
          location.href = response.redirect_url;
        } else if (response.status === 'error') {
          hideLoadingOverlay();
          $('#update-category-form').prepend('<div class="alert alert-danger">' + response.message + '</div>');
        } else {
          hideLoadingOverlay();
          $('#update-category-form').prepend('<div class="alert alert-danger">Unknown error occurred.</div>');
        }
      },
      error: function(xhr, status, error) {
        // Handle AJAX errors, if any
        console.error(error);
        hideLoadingOverlay();
        $('#update-category-form').prepend('<div class="alert alert-danger">An error occurred during the request.</div>');
      }
    });
  });
</script>

<!-- JavaScript code to handle the delete confirmation -->
<script>
  $(document).on('click', '.category-delete', function(e) {
    e.preventDefault();

    var categoryId = $(this).data('id');
    var categoryName = $(this).data('name');

    var confirmed = confirm('Are you sure you want to delete ' + categoryName + ' Category?');

    if (confirmed) {
      $.ajax({
        type: 'POST',
        url: 'controllers/app.php?action=delete_category',
        data: {
          category_id: categoryId
        },
        dataType: 'json',
        beforeSend: function() {
          showLoadingOverlay();
        },
        success: function(response) {
          console.log(response);
          if (response.status === 'success') {
            toastr.success(response.message);
            setTimeout(function() {
              location.reload();
            }, 1500);
          } else {
            toastr.error(response.message);
          }
        },
        error: function(xhr, status, error) {
          // Handle AJAX errors, if any
          console.error(error);
          hideLoadingOverlay();
          toastr.error('An error occurred during the request.');
        }
      });
    } else {
      // User canceled the deletion
      toastr.info('Deletion canceled.');
    }
  });
</script>

<script>
  $(document).ready(function() {
    // When a dropdown link is clicked
    $(".dropdown-item").on("click", function() {
      var modalId = $(this).data("modal"); // Get the modal ID from the data-modal attribute

      // Open the corresponding modal
      $(modalId).modal("show");
    });
  });
</script>

<!-- Add Candidate -->
<script>
  $('.add-candidate').click(function() {
    addCandidate("Add Candidate", 'add_candidate.php?election_id=' + $(this).attr('data-id'))
  })

  window.addCandidate = function($title = '', $url = '') {
    $.ajax({
      url: $url,
      error: err => {
        console.log()
        alert("An error occured")
      },
      beforeSend: function() {
        showLoadingOverlay();
      },
      success: function(resp) {
        if (resp) {
          hideLoadingOverlay();
          $('#addCandidate .modal-title').html($title)
          $('#addCandidate .modal-body').html(resp)
          $('#addCandidate').modal('show')
        }
      }
    })
  }
</script>

<!-- Edit Candidate -->
<script>
  $('.edit-candidate').click(function() {
    editCandidate("Edit Candidate", 'edit_candidate.php?id=' + $(this).attr('data-id'))
  })

  window.editCandidate = function($title = '', $url = '') {
    $.ajax({
      url: $url,
      error: err => {
        console.log()
        alert("An error occured")
      },
      beforeSend: function() {
        showLoadingOverlay();
      },
      success: function(resp) {
        if (resp) {
          hideLoadingOverlay();
          $('#editCandidate .modal-title').html($title)
          $('#editCandidate .modal-body').html(resp)
          $('#editCandidate').modal('show')
        }
      }
    })
  }
</script>

<script>
  // jQuery AJAX form submission
  $(document).on('submit', '#update-candidate-form', function(e) {
    e.preventDefault();

    var formData = $(this).serialize();

    $.ajax({
      type: 'POST',
      url: 'controllers/app.php?action=update_candidate',
      data: formData,
      dataType: 'json',
      beforeSend: function() {
        showLoadingOverlay();
      },
      success: function(response) {
        console.log(response);
        if (response.status === 'success') {
          location.href = response.redirect_url;
          // $('#update-candidate-form').prepend('<div class="alert alert-success">' + response.redirect_url + '</div>');
        } else if (response.status === 'error') {
          hideLoadingOverlay();
          $('#update-candidate-form').prepend('<div class="alert alert-danger">' + response.message + '</div>');
        } else {
          hideLoadingOverlay();
          $('#update-candidate-form').prepend('<div class="alert alert-danger">Unknown error occurred.</div>');
        }
      },
      error: function(xhr, status, error) {
        // Handle AJAX errors, if any
        console.error(error);
        hideLoadingOverlay();
        $('#update-candidate-form').prepend('<div class="alert alert-danger">An error occurred during the request.</div>');
      }
    });
  });
</script>

<script>
  $(document).on('click', '.delete-candidate', function(e) {
    e.preventDefault();

    var candidateId = $(this).data('id');
    var candidateName = $(this).data('name');
    var candidateCategory = $(this).data('category');

    var confirmed = confirm('Are you sure you want to delete ' + candidateName + ' from ' + candidateCategory + '?');

    // If the user confirms, proceed with deletion
    if (confirmed) {
      $.ajax({
        type: 'POST',
        url: 'controllers/app.php?action=delete_candidate',
        data: {
          candidate_id: candidateId
        },
        dataType: 'json',
        beforeSend: function() {
          showLoadingOverlay();
        },
        success: function(response) {
          console.log(response);
          if (response.status === 'success') {
            toastr.success(response.message);
            setTimeout(function() {
              location.reload();
            }, 1500);
          } else {
            toastr.error(response.message);
          }
        },
        error: function(xhr, status, error) {
          // Handle AJAX errors
          console.error(error);
          hideLoadingOverlay();
          toastr.error('An error occurred during the request.');
        }
      });
    } else {
      // User canceled the deletion
      toastr.info('Deletion canceled.');
    }
  });
</script>

<script>
  // jQuery AJAX form submission
  $(document).on('submit', '#vote-form', function(e) {
    e.preventDefault();

    // Create an array to store the selected vote data
    var selectedCandidates = [];

    $('.vote-radio:checked').each(function() {
      var candidate_id = $(this).data('id');
      var category_id = $(this).data('category');
      var election_id = $(this).data('election');

      selectedCandidates.push({
        candidate_id: candidate_id,
        category_id: category_id,
        election_id: election_id
      });
    });

    // Check if any candidate are selected
    if (selectedCandidates.length === 0) {
      $('#vote-form').prepend('<div class="alert alert-danger">Please select at least one candidate.</div>');
      return;
    }

    // Prepare data for Ajax request
    var requestData = {
      selectedCandidates: selectedCandidates
    };

    $.ajax({
      type: 'POST',
      url: 'controllers/app.php?action=vote',
      data: requestData,
      dataType: 'json',
      beforeSend: function() {
        showLoadingOverlay();
      },
      success: function(response) {
        console.log(response);
        if (response.status === 'success') {
          toastr.success(response.message);
          setTimeout(function() {
            location.reload();
          }, 1000);
          $('#vote-form')[0].reset();
        } else if (response.status === 'error') {
          hideLoadingOverlay();
          $('#vote-form').prepend('<div class="alert alert-danger">' + response.message + '</div>');
        } else {
          hideLoadingOverlay();
          $('#vote-form').prepend('<div class="alert alert-danger">Unknown error occurred.</div>');
        }
      },
      error: function(xhr, status, error) {
        // Handle AJAX errors, if any
        console.error(error);
        hideLoadingOverlay();
        $('#vote-form').prepend('<div class="alert alert-danger">An error occurred during the request.</div>');
      }
    });
  });
</script>

<!-- *********** PROFILE ************ -->
<script>
  // jQuery AJAX form submission
  $(document).on('submit', '#edit-profile', function(e) {
    e.preventDefault();

    var formData = new FormData($("#edit-profile")[0]);

    $.ajax({
      url: 'controllers/app.php?action=update_profile',
      type: 'POST',
      data: formData,
      processData: false,
      contentType: false,
      dataType: 'json',
      beforeSend: function() {
        showLoadingOverlay();
      },
      success: function(response) {
        console.log(response);
        if (response.status === 'success') {
          toastr.success(response.message);
          setTimeout(function() {
            location.reload();
          }, 1000);
        } else if (response.status === 'error') {
          hideLoadingOverlay();
          $('#edit-profile').prepend('<div class="alert alert-danger">' + response.message + '</div>');
        } else {
          hideLoadingOverlay();
          $('#edit-profile').prepend('<div class="alert alert-danger">Unknown error occurred.</div>');
        }
      },
      error: function(xhr, status, error) {
        // Handle AJAX errors, if any
        console.error(error);
        hideLoadingOverlay();
        $('#edit-profile').prepend('<div class="alert alert-danger">An error occurred during the request. ' + xhr.responseText + '</div>');
      }
    });
  });
</script>


<script>
  var endTime = new Date("<?php echo $endtime; ?>");
  var electionId = <?php echo $election_id; ?>;

  var countdownInterval = setInterval(function() {
    var now = new Date();
    var timeLeft = endTime - now;

    if (timeLeft <= 0) {
      clearInterval(countdownInterval);
      document.getElementById("countdown").textContent = "Voting has ended.";

      $.ajax({
        type: 'POST',
        url: 'controllers/app.php?action=vote_status',
        data: {
          election_id: electionId
        },
        dataType: 'json',
        beforeSend: function() {
          showLoadingOverlay();
        },
        success: function(response) {
          console.log(response);
          if (response.status === 'success') {
            toastr.success('Voting time has ended.');

            // Check if the current URL is already 'index.php?page=results'
            if (window.location.href.indexOf('index.php?page=results') === -1) {
              setTimeout(function() {
                window.location.href = 'index.php?page=results';
              }, 2000);
            } else {
              hideLoadingOverlay();
            }
          } else {
            hideLoadingOverlay();
            console.error('Error updating election status:', response.message);
          }
        },
        error: function(xhr, status, error) {
          // Handle AJAX errors, if any
          console.error(error);
          hideLoadingOverlay();
          f
          console.error('An error occurred during the request.');
        }
      });
    } else {
      var days = Math.floor(timeLeft / (1000 * 60 * 60 * 24));
      var hours = Math.floor((timeLeft % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
      var minutes = Math.floor((timeLeft % (1000 * 60 * 60)) / (1000 * 60));
      var seconds = Math.floor((timeLeft % (1000 * 60)) / 1000);

      document.getElementById("countdown").textContent = days + "d " + hours + "h " + minutes + "m " + seconds + "s";
    }
  }, 1000);
</script>

<!-- USER -->
<script>
  new DataTable('#users-table', {
    "info": false,
    "responsive": true,
    "lengthChange": false,
    "order": [
      [2, "asc"]
    ],
    "buttons": ["excel", "pdf", "print"]
  });
</script>

<script>
  $(document).on('click', '.user-type', function(e) {
    e.preventDefault();

    var userId = $(this).data('id');
    var type = $(this).data('type');
    var roleName = $(this).data('name');

    var confirmed = confirm('Are you sure you want to make this candidate as ' + roleName + '?');

    // If the user confirms, proceed with change candidate status
    if (confirmed) {
      $.ajax({
        type: 'POST',
        url: 'controllers/app.php?action=user_type',
        data: {
          user_id: userId,
          type: type
        },
        dataType: 'json',
        beforeSend: function() {
          showLoadingOverlay();
        },
        success: function(response) {
          console.log(response);
          if (response.status === 'success') {
            toastr.success(response.message);
            setTimeout(function() {
              location.reload();
            }, 1000);
          } else {
            hideLoadingOverlay();
            toastr.error(response.message);
          }
        },
        error: function(xhr, status, error) {
          // Handle AJAX errors, if any
          console.error(error);
          hideLoadingOverlay();
          toastr.error('An error occurred during the request.');
        }
      });
    } else {
      // User canceled to change status
      toastr.info('User type change canceled.');
    }
  });
</script>

<!-- ELECTION REPORTS -->
<script>
  $(document).ready(function() {
    $(".export_results").click(function(e) {
      e.preventDefault();

      var electionId = $(this).data("electionId");
      // var userId = $(this).data("id");
      // var actionName = $(this).data("name");

      // var confirmReset = confirm("Confirm to Generate Excel");

      // if (confirmReset) {
      $.ajax({
        type: 'POST',
        url: 'controllers/app.php?action=export_results&election_id'.electionId,
        // data: {
        //   user_id: userId
        // },
        dataType: 'json',
        beforeSend: function() {
          showLoadingOverlay();
        },
        complete: function() {
          hideLoadingOverlay();
        },
        success: function(response) {
          if (response.status === "success") {
            toastr.success(response.message);
          } else {
            toastr.error(response.message);
          }
        },
        error: function(err) {
          console.log(err);
          toastr.error("An error occurred while processing the request.");
        }
      });
      // }
    });
  });
</script>

<script>
  $(document).on('click', '.download-report', function(e) {
    e.preventDefault();

    var reportId = $(this).data('id');

    $.ajax({
      type: 'POST',
      url: 'controllers/app.php?action=download_report',
      data: {
        election_id: reportId
      },
      dataType: 'json',
      beforeSend: function() {
        showLoadingOverlay();
      },
      success: function(response) {
        console.log(response);
        if (response.status === 'success') {
          toastr.success(response.message);
          setTimeout(function() {
            location.reload();
          }, 1000);
        } else {
          hideLoadingOverlay();
          toastr.error(response.message);
        }
      },
      error: function(xhr, status, error) {
        // Handle AJAX errors, if any
        console.error(error);
        hideLoadingOverlay();
        toastr.error('An error occurred during the request.');
      }
    });
  });
</script>

<script>
  $(document).on('click', '.delete-report', function(e) {
    e.preventDefault();

    var reportId = $(this).data('id');
    var reportTitle = $(this).data('name');

    var confirmed = confirm('Are you sure you want to delete ' + reportTitle + ' report ?');

    if (confirmed) {
      $.ajax({
        type: 'POST',
        url: 'controllers/app.php?action=delete_report',
        data: {
          election_id: reportId
        },
        dataType: 'json',
        beforeSend: function() {
          showLoadingOverlay();
        },
        success: function(response) {
          console.log(response);
          if (response.status === 'success') {
            // location.href = response.redirect_url;
            toastr.success(response.message);
            setTimeout(function() {
              location.reload();
            }, 1500);
          } else {
            toastr.error(response.message);
            hideLoadingOverlay();
          }
        },
        error: function(xhr, status, error) {
          // Handle AJAX errors, if any
          console.error(error);
          hideLoadingOverlay();
          toastr.error('An error occurred during the request.');
        }
      });
    } else {
      // User canceled the deletion
      toastr.info('Deletion canceled.');
    }
  });
</script>