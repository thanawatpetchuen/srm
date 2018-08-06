<?php 
    require('../cookie_validate_admin.php');
    // echo json_encode($_SESSION);

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
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/dt/dt-1.10.16/fc-3.2.4/r-2.2.1/datatables.min.css"/>
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
    <link rel="stylesheet" href="/srmsng/public/css/style.css">
</head>
<body>
    <?php include_once('../admin/admin_nav.php'); ?>
    <main class="container">
        <div class="alert alert-success" id="add-success">
            Ticket added
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="alert alert-success" id="update-success">
            Ticket updated
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="input-group page-title">
            <h1 style="margin-bottom:0;">Customers</h1>
        </div>
        <table id="supertable">
            <thead>
                <th>No.</th>
                <th>Customer Number</th>
                <th>Customer Name</th>
                <th>Customer Eng Name</th>
                <th>Account Group</th>
                <th>Sale Team</th>
            	<th>Primary Contact</th>
            	<th>Account Owner</th>
                <th>Service Sale</th>
                <th>Tax ID</th>
                <th>Action</th>
            </thead>
            <tbody id="maintable">
         
            </tbody>
        </table>

         <div class="modal" tabindex="-1" role="dialog" id="edit-customer-popup">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Edit Customer</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </div>
                        <form method="post" id="edit-customer-form">
                            <div class="modal-body">
                                <div class="form-group">
                                    <label for="customer_name">
                                        <b>Customer Name</b>
                                    </label>
                                    <input type="text" name="customer_name" class="form-control" placeholder="Enter Customer Name" id="customer_name" required>
                                </div>
                                <div class="form-group">
                                    <label for="customer_eng_name">
                                        <b>Customer English Name</b>
                                    </label>
                                    <input type="text" placeholder="Enter Customer English Name" name="customer_eng_name" id="customer_eng_name" class="form-control" required>
                                </div>
                                <div class="form-group">
                                    <label for="taxid">
                                        <b>Tax ID</b>
                                    </label>
                                    <input type="text" class="form-control" placeholder="Enter Tax ID" name="taxid" id="taxid" required>
                                </div>
                                <div class="form-group">
                                    <label for="account_group">
                                        <b>Account Group</b>
                                    </label>
                                    <input type="text" class="form-control" placeholder="Enter Account Group" name="account_group" id="account_group" required>
                                </div>
                                <div class="form-group">
                                    <label for="sale_team">
                                        <b>Sale Team</b>
                                    </label>
                                    <input type="text" placeholder="Enter Sale Team" name="sale_team" class="form-control" id="sale_team" required>
                                </div>
                                <div class="form-group">
                                    <label for="account_owner">
                                        <b>Account Owner</b>
                                    </label>
                                    <input type="text" placeholder="Enter Account Owner" name="account_owner" id="account_owner" class="form-control" required>
                                </div>
                                <div class="form-group">
                                    <label for="service_sale">
                                        <b>Service Sale</b>
                                    </label>
                                    <input type="text" placeholder="Enter Service Sale" name="service_sale" id="service_sale" class="form-control" required>
                                </div>
                                <div class="form-group">
                                    <label for="primary_contact">
                                        <b>Primary Contact</b>
                                    </label>
                                    <input type="text" placeholder="Enter Primary Contact" name="primary_contact" id="primary_contact" class="form-control" required>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">Cancel</button>
                                <button type="submit" class="btn btn-primary" id="addUserBtn">Confirm Edit</button>
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
    <script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
	<!-- <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.js"></script> -->
    <script type="text/javascript" src="https://cdn.datatables.net/v/dt/dt-1.10.16/fc-3.2.4/r-2.2.1/datatables.min.js"></script>
    <script src="/srmsng/public/js/table_setup.js"></script>
    <script src="/srmsng/public/js/fetch_ajax.js"></script>
    <script src="/srmsng/public/js/fetch_customer.js"></script>
    <script src="/srmsng/public/js/submit.js"></script>
    <script src="/srmsng/public/js/onclose.js"></script>
    <script>
        $(document).ready( function () {
            var url_string = window.location.href;
            var url = new URL(url_string);
            var add_success = url.searchParams.get("add_success");
            if (add_success == 'true') {
                $('#add-success').css('display','block');
            }
            var update_success = url.searchParams.get("update_success")
            if (update_success == 'true') {
                $('#update-success').css('display','block');
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