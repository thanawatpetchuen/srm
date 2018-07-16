<?php 
    require($_SERVER['DOCUMENT_ROOT'].'/srmsng/public/cookie_validate_admin.php');
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
    <link href="http://maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css" rel="stylesheet">
    <link href='http://fonts.googleapis.com/css?family=Roboto:400,700,300' rel='stylesheet' type='text/css'>
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.16/css/jquery.dataTables.css">
    <link rel="stylesheet" href="/srmsng/public/css/style.css">
</head>
<body>
    <?php include_once('../admin/admin_nav.php'); ?>
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
        <div class="input-group page-title">
            <h1 style="margin-bottom:0;">Accounts</h1>
            <button type="button" class="btn btn-primary" data-target="#add-popup" data-toggle="modal">
                <i class="fa fa-plus"></i> Add Account
            </button>
        </div>
            <table id="supertable">
                    <thead>
                        <th id="acc_type">Account Type</th>
                        <th id="acc_type">Account Name</th>
                        <th id="acc_name">Username</th>
                        <th id="acc_status">Status</th>
                        <th id="acc_lock">Locked</th>
                        <th id="acc_login">Last Login</th>
                        <th id="acc_action">Action</th>
                    </thead>
                    <tbody id="maintable">
                
                    </tbody>
            </table>

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
                                <a href="customer?add_modal=true" style="float:right" target="_blank">
                                    <i class="fa fa-plus"></i> Add New Customer
                                </a>
                                <div class="form-group">
                                    <label>Customer Name</label>
                                    <div class="input-group">
                                        <input type="text" class="form-control" id="customer-search-field" placeholder="Customer Name" name="customer_name" autocomplete=off required/>
                                        <div class="autofill-dropdown border" id="customer-dropdown">
                                        </div>
                                        <div class="input-group-append">
                                            <button class="btn btn-primary" type="button" id="customer-search"><i class="fa fa-search"></i></span>
                                        </div>
                                    </div> 
                                    <small class="form-text text-muted">Enter at least 3 characters and press enter to search.</small>
                                </div>
                                <div class="form-group">
                                    <label>Customer Code</label>
                                    <div class="input-group">
                                        <input type="text" class="form-control" id="customer-code-search-field" name="account_no" placeholder="Customer Code" required/>
                                        <div class="autofill-dropdown border" id="customer-code-dropdown">
                                        </div>
                                        <div class="input-group-append">
                                            <button class="btn btn-primary" type="button" id="customer-code-search"><i class="fa fa-search"></i></span>
                                        </div>
                                    </div> 
                                    <small class="form-text text-muted">Enter at least 3 characters and press enter to search.</small>
                                </div>
                                <div class="form-group">
                                    <label for="username">
                                        Username
                                    </label>
                                    <input type="text" placeholder="Enter Username" name="username" class="form-control" required>
                                </div>
                                <div class="form-group">
                                    <label for="password">
                                        Password
                                    </label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <button class="btn btn-primary" type="button" onClick="generate()">Generate</button>
                                        </div>
                                        <input 
                                            type="text" 
                                            class="form-control" 
                                            placeholder="Password" 
                                            name="password" 
                                            id="new-password-field">
                                    </div>
                                    <small class="form-text text-danger" style="display: none;" id="weak-password-warning">The password must contain uppercase and lowercase letters, numbers, and have at least 8 characters.</small>
                                </div>
                                <div class="form-group">
                                    <label for="accout_type">
                                        Account Type
                                    </label>
                                    <select name="account_type" class="form-control" id="acc_type">
                                        <option value="USER">User</option>
                                        <option value="FSE">FSE</option>
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
    </main>
</body>
    <script src="https://code.jquery.com/jquery-3.3.1.min.js" integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.0/umd/popper.min.js" integrity="sha384-cs/chFZiN24E4KMATLdqdvsezGxaGsi4hLGOzlXwp5UZB1LY//20VyM2taTB4QvJ" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.0/js/bootstrap.min.js" integrity="sha384-uefMccjFJAIv6A+rW+L4AHf99KvxDjWSu1z9VI8SKNVmz4sk7buKt/6v9KI65qnm" crossorigin="anonymous"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/v/dt/dt-1.10.16/fc-3.2.4/r-2.2.1/datatables.min.js"></script>
    <script src="/srmsng/public/js/table_setup.js"></script>
    <script src="/srmsng/public/js/fetch_ajax.js"></script>
    <script src="/srmsng/public/js/fetch_account.js"></script>
    <script src="/srmsng/public/js/submit.js"></script>
    <script src="/srmsng/vendor/dmauro-Keypress/keypress.js"></script>
    <script src="/srmsng/public/js/random.js"></script>
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
            
            var listener = new window.keypress.Listener();
            listener.sequence_combo("s u p e r a d m i n 1", function() {
                // console.log("You pressed shift and s");
                window.location = "/srmsng/public/superadmin";
            });
            
            // Search customers
            $("#customer-search-field").on("keydown", function() {
                // Press enter to search
                if (event.keyCode == 13) {
                $("#customer-search").trigger("click");
                }
                // Clear data when the user types
                $("#customer-choose .sub-field, #customer-code-search-field").val("");
                document.getElementById("customer-dropdown").innerHTML = "";
            });
            // Search Customer Code
            $("#customer-code-search-field").on("keydown", function() {
                // Press enter to search
                if (event.keyCode == 13) {
                $("#customer-code-search").trigger("click");
                }
                // Clear data when the user types
                $("#customer-choose .sub-field, #customer-search-field").val("");
                document.getElementById("customer-code-dropdown").innerHTML = "";
            });

            // Begin searching for customer
            $("#customer-search").on("click", function() {
                if ($("#customer-search-field").val().length >= 3) {
                // Add data to dropdown and display
                fetch("/srmsng/public/index.php/api/admin/customername?customer_name=" + $("#customer-search-field").val())
                    .then(resp => {
                    return resp.json();
                    })
                    .then(data_json => {
                    // clear dropdown
                    document.getElementById("customer-dropdown").innerHTML =
                        '<span class="autofill-item-default">No Data Found</span>';

                    data_json.forEach(element => {
                        var item = document.createElement("span");
                        item.setAttribute("class", "autofill-item");
                        item.innerHTML = element["customer_name"];
                        item.onclick = function() {
                        $("input[name='customer_name']").val(element["customer_name"]);
                        $("#customer-dropdown")
                            .attr("tabindex", -1)
                            .focusout();
                        $("input[name='account_no']").val(element["customer_no"]);
                        $("#customer-no-add").val(element["customer_no"]);

                        // enable edit button
                        $("#customer-edit-button").removeClass("disabled");
                        };
                        document.getElementById("customer-dropdown").appendChild(item);
                    });
                    });

                $("#customer-dropdown").addClass("show");
                $("#customer-dropdown")
                    .attr("tabindex", -1)
                    .focus();
                }
            });
            // Begin searching for customer code
            $("#customer-code-search").on("click", function() {
                if ($("#customer-code-search-field").val().length >= 3) {
                // Add data to dropdown and display
                fetch("/srmsng/public/index.php/api/admin/customercode?customer_code=" + $("#customer-code-search-field").val())
                    .then(resp => {
                    return resp.json();
                    })
                    .then(data_json => {
                    // clear dropdown
                    document.getElementById("customer-code-dropdown").innerHTML =
                        '<span class="autofill-item-default">No Data Found</span>';

                    data_json.forEach(element => {
                        var item = document.createElement("span");
                        item.setAttribute("class", "autofill-item");
                        item.innerHTML = element["customer_name"];
                        item.onclick = function() {
                        $("input[name='account_no']").val(element["customer_no"]);
                        $("#customer-code-dropdown")
                            .attr("tabindex", -1)
                            .focusout();
                        $("input[name='customer_name']").val(element["customer_name"]);
                        $("#customer-no-add").val(element["customer_no"]);
                        // enable edit button
                        $("#customer-edit-button").removeClass("disabled");
                        };
                        document.getElementById("customer-code-dropdown").appendChild(item);
                    });
                    });

                $("#customer-code-dropdown").addClass("show");
                $("#customer-code-dropdown")
                    .attr("tabindex", -1)
                    .focus();
                }
            });
            $("#customer-dropdown, #customer-code-dropdown").on("focusout", function() {
                $(this).removeClass("show");
            });
        });
        window.addEventListener("beforeunload", function (e) {       
            
            let remember = "<?php echo $_SESSION['remember']?>";
    
            if(remember == "off"){
                console.log("UNDLOAD");
                $.ajax({
                    type: "POST",
                    url: '/srmsng/public/index.php/logout',       
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