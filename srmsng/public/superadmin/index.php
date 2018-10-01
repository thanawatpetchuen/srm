<?php 
    require($_SERVER['DOCUMENT_ROOT'].'/srmsng/public/cookie_validate_superadmin.php');
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
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.0.13/css/all.css" integrity="sha384-DNOHZ68U8hZfKXOrtjWvjxusGo9WQnrNx2sqG0tfsghAvtVlRW3tvkXWZh58N9jp" crossorigin="anonymous">
    <link href='https://fonts.googleapis.com/css?family=Roboto:400,700,300' rel='stylesheet' type='text/css'>
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.16/css/jquery.dataTables.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <link rel="stylesheet" href="/srmsng/public/css/style.css">
</head>
<body>
    <?php include_once('../admin/superadmin_nav.php'); ?>
    <main class="container">
        <div class="alert alert-success" id="unlock-success">
            The account has been unlocked.
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="alert alert-danger" id="lock-success">
            The account has been locked.
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="alert alert-success" id="add-success">
            Account added
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="alert alert-danger" id="delete-success">
            Account(s) deleted
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="input-group page-title">
            <h1 style="margin-bottom:0;">Accounts</h1>
            <button type="button" class="btn btn-primary" data-target="#add-popup" data-toggle="modal">
                <i class="fa fa-plus"></i> Add Account
            </button>
        </div>
            <table id="supertable">
                    <thead>
                        <th id="select">Select</th>
                        <!-- <th id="acc_no">Account No.</th> -->
                        <th id="acc_type">Account Type</th>
                        <th id="acc_name">Account Name</th>
                        <th id="acc_username">Username</th>
                        <th id="acc_status">Status</th>
                        <th id="acc_lock">Locked</th>
                        <th id="acc_login">Last Login</th>
                        <th id="acc_ip">IP Address</th>
                        <th id="acc_action">Action</th>
                    </thead>
                    <tbody id="maintable">
                
                    </tbody>
            </table>
            <button class="btn btn-secondary" id="deleteBtn">
                <i class="fa fa-trash"></i> <span>Delete Selected</span>
            </button>
            <button class="btn btn-secondary" id="deauthSelectBtn">
                <i class="fa fa-skull"></i> <span>De-Authenticate Selected</span>
            </button>
            <button class="btn btn-danger" id="deauthBtn">
                <i class="fa fa-skull"></i> <span>De-Authenticate ALL</span>
            </button>

            <div class="modal" tabindex="-1" role="dialog" id="add-popup">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Add Account</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </div>
                        <form method="post" id="add-account-form">
                            <div class="modal-body">
                                <div class="form-group">
                                    <label for="account_no">
                                        <b>Account Number</b>
                                    </label>
                                    <input type="text" name="account_no" class="form-control" placeholder="Enter Account Number" id="accountno" required>
                                </div>
                                <div class="form-group">
                                    <label for="username">
                                        <b>Username</b>
                                    </label>
                                    <input type="text" placeholder="Enter Username" name="username" class="form-control" required>
                                </div>
                                <div class="form-group">
                                    <label for="password">
                                        <b>Password</b>
                                    </label>
                                    <input type="password" class="form-control" placeholder="Enter Password" name="password" id="passwordFields" required>
                                </div>
                                <div class="form-group">
                                    <label for="accout_type">
                                        <b>Account Type</b>
                                    </label>
                                    <select name="account_type" class="form-control" id="acc_type">
                                        <option value="USER">User</option>
                                        <option value="ADMIN">Admin</option>
                                    </select>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">Cancel</button>
                                <button type="submit" class="btn btn-primary" id="addUserBtn">Add Account</button>
                            </div> 
                        </form>
                    </div>
                </div>
            </div>
        <!-- <div class="form-container">
            
        </div> -->
    </main>
</body>
    <script src="https://code.jquery.com/jquery-3.3.1.min.js" integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.0/umd/popper.min.js" integrity="sha384-cs/chFZiN24E4KMATLdqdvsezGxaGsi4hLGOzlXwp5UZB1LY//20VyM2taTB4QvJ" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.0/js/bootstrap.min.js" integrity="sha384-uefMccjFJAIv6A+rW+L4AHf99KvxDjWSu1z9VI8SKNVmz4sk7buKt/6v9KI65qnm" crossorigin="anonymous"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/v/dt/dt-1.10.16/fc-3.2.4/r-2.2.1/datatables.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@7.26.11/dist/sweetalert2.all.min.js"></script>
    <script src="/srmsng/public/js/table_setup.js"></script>
    <script src="/srmsng/public/js/fetch_ajax.js"></script>
    <script src="/srmsng/public/js/fetch_account_admin.js"></script>
    <script src="/srmsng/public/js/submit.js"></script>
    <script src="/srmsng/public/js/superadmin.js"></script>
    <script>
        $(document).ready( function () {
            var url_string = window.location.href;
            var url = new URL(url_string);
            var unlock_success = url.searchParams.get("unlock_success");
            var lock_success = url.searchParams.get("lock_success")
            var add_success = url.searchParams.get("add_success")
            var delete_success = url.searchParams.get("delete_success")

            if (unlock_success == 'true') {
                $('#unlock-success').css('display','block');
            }
            if (lock_success == 'true') {
                $('#lock-success').css('display','block');
            }
            if (add_success == 'true') {
                $('#add-success').css('display','block');
            }
            if (delete_success == 'true') {
                $('#delete-success').css('display','block');
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