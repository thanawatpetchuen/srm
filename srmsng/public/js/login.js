$("#loginForm").submit(function(e) {
  console.log("CALl");
  e.preventDefault();
  ajaxCall();
  return false;
});

function ajaxCall(token = null){
  // alert(token);
  // console.log(token);
  if(token != null){
    console.log("THIS IS TOKEN "+token);
    // console.log($("#loginForm").serialize()+"&token="+token);
    $.ajax({
      type: "POST",
      data: "token="+token,
      url: "/srmsng/public/index.php/logout/forcelogout",
      dataType: "JSON",
      success: data => {
        console.log("SUCCESS  with TOKEN");
        console.log(data);
        // dataj = data.json();
        if (data.statuscode == "111" || data.statuscode == "112") {
          window.location.href = data.description;
        } else {
          $(".login-window").addClass("animated shake");
          setTimeout(function() {
            $(".login-window").removeClass("shake");
          }, 1000);
          if (data.attempt == undefined) {
            $("#login-error .alert-content").text(data.description);
            $("#login-error").css("display", "block");
            // alert(data.token);
            if(token != null || data.token != undefined){
              // ajax
              console.log("HERE");
              // ajaxCall(data.token);
            }
            
          } else {
            var invalid = document.getElementById("invalid-feedback");
            $("#passwordField").addClass("is-invalid");
            if(data.statuscode == "101" && data.attempt == "0"){
              invalid.innerHTML = data.description;
            }else{
              invalid.innerHTML = data.description + " Remaining: " + data.attempt;
            }
          }
        }
      },
      error: function(err) {
        console.log(err);
      }
    });
  }else{
    // console.log($("#loginForm").serialize());
    $.ajax({
      type: "POST",
      data: $("#loginForm").serialize(),
      url: "/srmsng/public/index.php/login",
      dataType: "JSON",
      success: data => {
        // console.log("SUCCESS");
        // console.log(data);
        // dataj = data.json();
        if (data.statuscode == "111" || data.statuscode == "112") {
          console.log("PASS");
          window.location.href = data.description;
        } else {
          $(".login-window").addClass("animated shake");
          setTimeout(function() {
            $(".login-window").removeClass("shake");
          }, 1000);
          if (data.attempt == undefined) {
            
            // alert(data.token);
            if(token != null || data.token != undefined){
              // ajax
              // console.log("FROM FIRST");
              // console.log(data.token);
              if(confirm(data.description)){
                // console.log(document.cookie);
                ajaxCall(data.token);
              }
            }else{
              $("#login-error .alert-content").text(data.description);
              $("#login-error").css("display", "block");
            }
            
          } else {
            var invalid = document.getElementById("invalid-feedback");
            $("#passwordField").addClass("is-invalid");
            if(data.statuscode == "101" && data.attempt == "0"){
              invalid.innerHTML = data.description;
            }else{
              invalid.innerHTML = data.description + " Remaining: " + data.attempt;
            }
          }
        }
      },
      error: function(err) {
        console.log(err);
      }
    });
  }
}

function callTest(token){
  alert(token);
}