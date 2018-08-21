// Delete User
$("#deauthBtn").on("click", e => {
    e.preventDefault();
    $.ajax({
        type: "PUT",
        url: "/srmsng/public/api.php/api/admin/deauthall",
        success: data => {
          console.log(data);
          toastr.options = {
            positionClass: "toast-bottom-center"
          };
          toastr.success("<span>See you again</span>");
          setTimeout(() => {
            window.location.reload();;
          }, 2000);
            
            
        }
    })
  });

  // Delete User
$("#deauthSelectBtn").on("click", e => {
    e.preventDefault();
    var inputs = document.getElementsByTagName("input");

    swal({
      title: 'Are you sure?',
      // text: "You won't be able to revert this!",
      type: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'Yes, delete it!'
    }).then((result) => {
      if (result.value) {
        for (var i = 0; i < inputs.length; i++) {
          if (inputs[i].type == "checkbox" && inputs[i].checked == true) {
            // inputs[i].checked = true;
            console.log(`Username: ${inputs[i].value}`);
            $.ajax({
              type: "PUT",
              data: "username=" + inputs[i].value,
              url: "/srmsng/public/api.php/api/admin/deauth",
              success: data => {
                console.log(data);
              },
              error: err => {
                swal({
                  // position: '',
                  type: 'error',
                  title: `${err}`,
                  showConfirmButton: false,
                  timer: 2000
                  }).then(() => {
                      window.location.reload();
                  })
              }
            });
          }
        }
        
        swal(
          'Deauthed!',
          // 'De',
          'success'
        ).then(() => {
          window.location.reload();
        })
      }
    })


    // const swalWithBootstrapButtons = swal.mixin({
    //   confirmButtonClass: 'btn btn-success',
    //   cancelButtonClass: 'btn btn-danger',
    //   buttonsStyling: false,
    // })
    
    // swalWithBootstrapButtons({
    //   title: 'Are you sure?',
    //   // text: "You won't be able to revert this!",
    //   type: 'warning',
    //   showCancelButton: true,
    //   confirmButtonText: 'Yes, good bye my friend!',
    //   cancelButtonText: 'No, cancel!',
    //   reverseButtons: true
    // }).then((result) => {
    //   if (result.value) {
    //     // var deleteConfirm = confirm("Are you sure you want to DeAuth the user(s)?");
    //     // if (deleteConfirm) {
          
    //     // }
    //     swalWithBootstrapButtons(
    //       'Deleted!',
    //       'Your file has been deleted.',
    //       'success'
    //     )
    //   } 
    // })

    
  });