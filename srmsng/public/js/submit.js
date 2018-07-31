// ============ LOGIN/LOGOUT ============ //

// Recover Form
$("#usrform").submit(function(e) {
  e.preventDefault();
  $.ajax({
    type: "POST",
    data: $("#usrform").serialize(),
    url: "/srmsng/public/index.php/login/recover",
    success: function(data) {
      console.log(data);
      if (data == "SUCCESS") {
        $("#myModal").modal("show");
      } else {
        $("#failedModal").modal("show");
      }
    }
  });
  return false;
});

// RESET PASSWORD
$("#reset-form").submit(function(e) {
  $("#loader").css("display", "block");
  e.preventDefault();
  $.ajax({
    type: "PUT",
    data: $("#reset-form").serialize(),
    url: "/srmsng/public/index.php/admin/recover/reset",
    success: data => {
      console.log(data);
      if (data == 0) {
        $("#new-password-field").addClass("is-invalid");
        $("#weak-password-field").focus();
        $("#weak-password-warning").css("display", "block");
        return false;
      } else {
        $("#loader").css("display", "none");
        window.location = "/srmsng/public/system/passwordreset?reset_success=true";
      }
    },
    error: err => {
      alert(err);
    }
  });
});

// Logout
$("#logoutBtn").on("click", function() {
  console.log("CLICKED");
  $.ajax({
    type: "POST",
    url: "/srmsng/public/index.php/logout",
    success: data => {
      console.log(data);
      window.location.href = "/srmsng/public/login";
    },
    error: err => {
      console.log("ERRORRRRRRRRRRRR");
      console.log(err);
    }
  }).then(res => {
    console.log("FROM THEN");
    console.log(res);
  });
  return false;
});

// ============ CUSTOMER ============ //

// Problem Request
$("#problem-submit").submit(function(e) {
  if ($("#phone-number-field").val().length == 10) {
    e.preventDefault();
    $.ajax({
      type: "POST",
      data: $("#problem-submit").serialize(),
      url: "/srmsng/public/index.php/api/customer/request",
      success: data => {
        $("#request-submit-alert").css("display", "block");
        $("#request-submit-alert").text("Your request has been sent.");
        $("#problem-submit")[0].reset();
        console.log(data);
      },
      error: err => {
        $("#request-submit-alert").css("display", "block");
        $("#request-submit-alert").text("An error occurred. Please try again.");
      }
    });
  } else {
    $("#phone-number-warning").attr("class", "form-text text-danger");
    $("#phone-number-field").focus();
  }
  return false;
});

function confirmRequest() {
  if (confirm("Are you sure you want to submit your request?")) {
    $("#problem-submit").submit();
  }
}

// ============ ADMIN ============ //

// Edit Asset
$("#edit-form").submit(function(e) {
  e.preventDefault();
  //var prepare_data = $("#edit-form").serializeArray();
  $.ajax({
    type: "PUT",
    url: "/srmsng/public/index.php/api/admin/updateasset",
    data: $("#edit-form").serialize(),
    success: data => {
      //console.log($("#edit-form").serialize());
      //console.log(data);
      window.location = "/srmsng/public/asset?edit_success=true";
    },
    error: err => {
      console.log("ERROR!");
      console.log(err);
    }
  });
  return false;
});

// Add Asset
$("#add-form").submit(function(e) {
  e.preventDefault();
  console.log($("#add-form").serialize());
  $.ajax({
    type: "POST",
    url: "/srmsng/public/index.php/api/admin/addasset",
    data: $("#add-form").serialize(),
    success: data => {
      console.log(data);
      window.location = "/srmsng/public/asset?add_success=true";
    },
    error: err => {
      console.log("ERROR");
      console.log(err);
    }
  });
  return false;
});

// Add Ticket
$("#add-ticket-form").submit(function(e) {
  $("#loader").css("display", "block");
  if ($("#phone-number-field").val().length == 10) {
    e.preventDefault();
    console.log($("#add-ticket-form").serializeArray());
    $.ajax({
      type: "POST",
      data: $("#add-ticket-form").serialize(),
      url: "/srmsng/public/index.php/api/admin/addticket",
      success: data => {
        console.log(data);
        if (data == 1) {
          $("#sng-code-ticket-field small").removeClass("hidden");
          window.scrollTo(0, 0);
        } else {
          $("#loader").css("display", "none");
          window.location = "/srmsng/public/ticket?add_success=true";
        }
      },
      error: err => {
        console(err);
      }
    });
  } else {
    $("#phone-number-warning").attr("class", "form-text text-danger");
    $("#phone-number-field").focus();
    return false;
  }
  return false;
});

// Update Ticket
$("#update-ticket-form").submit(function(e) {
  $("#loader").css("display", "block");
  if ($("#phone-number-field").val().length == 10) {
    e.preventDefault();
    $.ajax({
      type: "PUT",
      data: $("#update-ticket-form").serialize(),
      url: "/srmsng/public/index.php/api/admin/assignticket",
      success: data => {
        console.log(data);
        console.log($("#update-ticket-form").serialize());
        $("#loader").css("display", "none");
        window.location = "/srmsng/public/ticket?update_success=true";
      },
      error: err => {
        alert(err);
      }
    });
  } else {
    $("#phone-number-warning").attr("class", "form-text text-danger");
    $("#phone-number-field").focus();
    return false;
  }
});

// Add Service
$("#add-service-form").submit(function(e) {
  $("#loader").css("display", "block");
  if ($("#contact-number-field").val().length == 10 || !$("#contact-number-field").val()) {
    if ($("#alternate-number-field").val().length == 10 || !$("#alternate-number-field").val()) {
      e.preventDefault();
      var serialData = $("#add-service-form").serializeArray();
      console.log(serialData);
      var chk_fse = false;
      serialData.forEach(data => {
        if (data.name == "fse_code[]") {
          chk_fse = true;
        }
      });
      if (chk_fse) {
        $.ajax({
          type: "POST",
          data: $("#add-service-form").serialize(),
          url: "/srmsng/public/index.php/api/admin/addservice",
          success: function(result) {
            if (result == "SUCCESS") {
              if ($("#add-service-form").data("type") === "work") {
                window.location = "/srmsng/public/asset/work?add_success=true&sng_code=" + serialData[6]["value"];
              } else {
                window.location = "/srmsng/public/service?add_success=true";
              }
            } else {
              alert(result);
            }
          },
          error: err => {
            console.log(err);
          }
        });
      } else {
        alert("You must assign at least 1 FSE to this service.");
      }
    } else {
      $("#alternate-number-warning").attr("class", "form-text text-danger");
      $("#alternate-number-field").focus();
      return false;
    }
  } else {
    if ($("#alternate-number-field").val().length != 10) {
      $("#alternate-number-warning").attr("class", "form-text text-danger");
    }
    $("#contact-number-warning").attr("class", "form-text text-danger");
    $("#contact-number-field").focus();
    return false;
  }
  return false;
});

// Update Service
$("#update-service-form").submit(function(e) {
  $("#loader").css("display", "block");
  if ($("#contact-number-field").val().length == 10 || !$("#contact-number-field").val()) {
    if ($("#alternate-number-field").val().length == 10 || !$("#alternate-number-field").val()) {
      e.preventDefault();
      var serialData = $("#update-service-form").serializeArray();
      console.log(serialData);
      var chk_fse = false;
      serialData.forEach(data => {
        if (data.name == "fse_code[]") {
          chk_fse = true;
        }
      });
      if (chk_fse) {
        $.ajax({
          type: "POST",
          data: $("#update-service-form").serialize(),
          url: "/srmsng/public/index.php/api/admin/updateservice",
          success: function(result) {
            if (result == "SUCCESS") {
                window.location = "/srmsng/public/service?update_success=true";
            } else {
              alert(result);
            }
          },
          error: err => {
            alert(err);
            console.log(err);
          }
        });
      } else {
        alert("Atleast, You must assign this service to a FSE!");
      }
    } else {
      $("#alternate-number-warning").attr("class", "form-text text-danger");
      $("#alternate-number-field").focus();
      return false;
    }
  } else {
    if ($("#alternate-number-field").val().length != 10) {
      $("#alternate-number-warning").attr("class", "form-text text-danger");
    }
    $("#contact-number-warning").attr("class", "form-text text-danger");
    $("#contact-number-field").focus();
    return false;
  }
  return false;
});

// Add Maintenanace Plan
$("#add-maintenance-plan-form").submit(function(e) {
  $("#loader").css("display", "block");
  if ($("#contact-number-field").val().length == 10 || !$("#contact-number-field").val()) {
    if ($("#alternate-number-field").val().length == 10 || !$("#alternate-number-field").val()) {
      e.preventDefault();
      var serialData = $("#add-maintenance-plan-form").serializeArray();
      console.log(serialData);
      var chk_fse = false;
      serialData.forEach(data => {
        if (data.name == "fse_code[]") {
          chk_fse = true;
        }
      });
      if (chk_fse) {
        $.ajax({
          type: "POST",
          data: $("#add-maintenance-plan-form").serialize(),
          url: "/srmsng/public/index.php/api/admin/addplan",
          success: function(result) {
            if (result == "SUCCESS") {
              window.location = "/srmsng/public/plan?add_success=true";
            } else {
              alert(result);
            }
          },
          error: err => {
            console.log(err);
          }
        });
      } else {
        alert("Atleast, You must assign this maintenance plan to a FSE!");
      }
    } else {
      $("#alternate-number-warning").attr("class", "form-text text-danger");
      $("#alternate-number-field").focus();
      return false;
    }
  } else {
    if ($("#alternate-number-field").val().length != 10) {
      $("#alternate-number-warning").attr("class", "form-text text-danger");
    }
    $("#contact-number-warning").attr("class", "form-text text-danger");
    $("#contact-number-field").focus();
    return false;
  }
  return false;
});

// Update Maintenanace Plan
$("#update-maintenance-plan-form").submit(function(e) {
  $("#loader").css("display", "block");
  if ($("#contact-number-field").val().length == 10 || !$("#contact-number-field").val()) {
    if ($("#alternate-number-field").val().length == 10 || !$("#alternate-number-field").val()) {
      e.preventDefault();
      var serialData = $("#update-maintenance-plan-form").serializeArray();
      console.log(serialData);
      var chk_fse = false;
      serialData.forEach(data => {
        if (data.name == "fse_code[]") {
          chk_fse = true;
        }
      });
      if (chk_fse) {
        $.ajax({
          type: "POST",
          data: $("#update-maintenance-plan-form").serialize(),
          url: "/srmsng/public/index.php/api/admin/updateplan",
          success: function(result) {
            if (result == "SUCCESS") {
              window.location = "/srmsng/public/plan?update_success=true";
            } else {
              alert(result);
            }
          },
          error: err => {
            alert(err);
            console.log(err);
          }
        });
      } else {
        alert("Atleast, You must assign this maintenance plan to a FSE!");
      }
    } else {
      $("#alternate-number-warning").attr("class", "form-text text-danger");
      $("#alternate-number-field").focus();
      return false;
    }
  } else {
    if ($("#alternate-number-field").val().length != 10) {
      $("#alternate-number-warning").attr("class", "form-text text-danger");
    }
    $("#contact-number-warning").attr("class", "form-text text-danger");
    $("#contact-number-field").focus();
    return false;
  }
  return false;
});

// Add User
$("#add-account-form").submit(function(e) {
  console.log($("#add-account-form").serialize());
  e.preventDefault();
  $.ajax({
    type: "POST",
    data: $("#add-account-form").serialize(),
    url: "/srmsng/public/index.php/api/adduser",
    success: data => {
      console.log(data);
      if (data == 0) {
        $("#new-password-field").addClass("is-invalid");
        $("#weak-password-field").focus();
        $("#weak-password-warning").css("display", "block");
        return false;
      } else {
        window.location = "/srmsng/public/account?add_success=true";
      }
    },
    error: err => {
      alert(err);
    }
  });
});

// Delete User
$("#deleteBtn").on("click", e => {
  e.preventDefault();
  var inputs = document.getElementsByTagName("input");
  var deleteConfirm = confirm("Are you sure you want to delete the user(s)?");
  if (deleteConfirm) {
    for (var i = 0; i < inputs.length; i++) {
      if (inputs[i].type == "checkbox" && inputs[i].checked == true) {
        // inputs[i].checked = true;
        $.ajax({
          type: "DELETE",
          data: "username=" + inputs[i].value,
          url: "/srmsng/public/index.php/api/admin/delete",
          success: data => {
            window.location = "/srmsng/public/account?delete_success=true";
          },
          error: err => {
            alert(err);
          }
        });
      }
    }
  }
});

//Reset Password from USER
$("#reset-password-form").submit(function(e) {
  if ($("#new-password-field").val() == $("#confirm-newpassword-field").val()) {
    // $("#loader").css("display", "block");
    e.preventDefault();
    $.ajax({
      type: "PUT",
      data: $("#reset-password-form").serialize(),
      url: "/srmsng/public/index.php/api/customer/resetpassword",
      success: data => {
        //console.log(data);
        if (data == 1) {
          $("#old-password-field").addClass("is-invalid");
          $("#old-password-field").focus();
          $("#old-password-warning").css("display", "block");
        } else if (data == 2) {
          $("#new-password-field").addClass("is-invalid");
          $("#weak-password-field").focus();
          $("#weak-password-warning").css("display", "block");
        } else {
          // $("#loader").css("display", "none");
          window.location = "/srmsng/public/customer/passwordreset?reset_success=true";
        }
      },
      error: err => {
        alert(err.name + err.message);
      }
    });
  } else {
    $("#confirm-newpassword-field").addClass("is-invalid");
    $("#confirm-newpassword-field").focus();
    $("#new-password-warning").css("display", "block");
    return false;
  }
});

$("#add-news-form").submit(function(e) {
  // e.preventDefault();

  console.log($("#add-news-form").serialize());

  // $.ajax({
  //   type: "POST",

  // })
});
