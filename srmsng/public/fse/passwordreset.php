<?php
    require($_SERVER['DOCUMENT_ROOT'].'/srmsng/public/cookie_validate_fse.php');
?>
<html>
<head>
<meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="/favicon.ico">

    <title>Synergize Provide Service</title>

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.0/css/bootstrap.min.css" integrity="sha384-9gVQ4dYFwwWSjIDZnLEWnxCjeSWFphJiwGPXr1jddIhOegiu1FwO5qRGvFXOdJZ4" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.16/css/jquery.dataTables.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <link rel="stylesheet" href="/srmsng/public/css/style.css">

    <!--     Fonts and icons     -->
    <link href="http://maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css" rel="stylesheet">
    <link href='http://fonts.googleapis.com/css?family=Roboto:400,700,300' rel='stylesheet' type='text/css'>


    <style>
        #popup {
            display:none;
        }
    </style>
</head>
<body>
    <?php include_once('fse_nav.php'); ?>
    <main class="container narrow">
        <div class="alert alert-success" id="reset-success">
            Your password has been reset.
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="input-group page-title">
            <h1 style="margin-bottom:0;">Reset Password</h1>
        </div>
        <div>
        <form id="reset-password-form">
            <div class="form-group">
                <label>Old Password</label>
                <input type="password" class="form-control" id="old-password-field" name="old_password" placeholder="Old Password"/>
                <small class="form-text text-danger" style="display: none;" id="old-password-warning">Wrong password</small>
            </div>
            <div class="form-group">
                <label>New Password</label>
                <input type="password" class="form-control" id="new-password-field" name="new_password" placeholder="New Password"/>
                <small class="form-text text-danger" style="display: none;" id="weak-password-warning">The password must contain uppercase and lowercase letters, numbers, and have at least 8 characters.</small>
            </div>
            <div class="form-group">
                <label>Confirm New Password</label>
                <input type="password" class="form-control" id="confirm-newpassword-field" name="confirm_new_password" placeholder="Confirm New Password"/>
                <small class="form-text text-danger" style="display: none;" id="new-password-warning">Passwords do not match.</small>
            </div> 
            <div class="form-group">
                <button type="submit" class="btn btn-primary" id="resetPwd">Submit</button>
            </div>
        </form>
    </main>


  
</body>
    <script src="https://code.jquery.com/jquery-3.3.1.min.js" integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.0/umd/popper.min.js" integrity="sha384-cs/chFZiN24E4KMATLdqdvsezGxaGsi4hLGOzlXwp5UZB1LY//20VyM2taTB4QvJ" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.0/js/bootstrap.min.js" integrity="sha384-uefMccjFJAIv6A+rW+L4AHf99KvxDjWSu1z9VI8SKNVmz4sk7buKt/6v9KI65qnm" crossorigin="anonymous"></script>
    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <script src="/srmsng/public/js/submit.js"></script>
    <script>
        $(document).ready( function () {
            var url_string = window.location.href;
            var url = new URL(url_string);
            var reset_success = url.searchParams.get("reset_success");
            if (reset_success == 'true') {
                $('#reset-success').css('display','block');
            }
        });
        window.addEventListener("beforeunload", function (e) {       
            
            let remember = "<?php echo $_SESSION['remember']?>";
    
            if(remember == "off"){
                $.ajax({
                    type: "POST",
                    url: '/srmsng/public/index.php/logout',
                    // async: false,/         
                    success: data => {
                        console.log(data);
                    },
                    error: err => {
                        console.log(err);
                    }
                });
                return;
            }
        }); 
    </script>
    
</html>