// Delete User
$("#deauthBtn").on("click", e => {
    e.preventDefault();
    $.ajax({
        type: "PUT",
        url: "/srmsng/public/api.php/api/admin/deauthall",
        success: data => {
            console.log(data);
            window.location.reload();
        }
    })
  });

  // Delete User
$("#deauthSelectBtn").on("click", e => {
    e.preventDefault();
    var inputs = document.getElementsByTagName("input");
    var deleteConfirm = confirm("Are you sure you want to DeAuth the user(s)?");
    if (deleteConfirm) {
      for (var i = 0; i < inputs.length; i++) {
        if (inputs[i].type == "checkbox" && inputs[i].checked == true) {
          // inputs[i].checked = true;
          $.ajax({
            type: "PUT",
            data: "username=" + inputs[i].value,
            url: "/srmsng/public/api.php/api/admin/deauth",
            success: data => {
              
            },
            error: err => {
              alert(err);
            }
          });
        }
      }
      window.location.reload();
    }
  });