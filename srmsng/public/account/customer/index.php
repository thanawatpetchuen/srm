<?php 
    require($_SERVER['DOCUMENT_ROOT'].'/srmsng/public/cookie_validate_admin.php');
    // echo json_encode($_SESSION);

?>
<html>
<head>
<meta http-equiv="Content-type" content="text/html"; charset="utf-8"/>
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
    <?php include_once($_SERVER['DOCUMENT_ROOT'].'/srmsng/public/admin/admin_nav.php'); ?>
    <main class="container">
        <div class="alert alert-success" id="add-success">
            Customer added
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="alert alert-success" id="update-success">
            Customer updated
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="input-group page-title">
            <h1 style="margin-bottom:0;">Customers</h1>
            <a href="#" class="btn btn-primary" data-target="#add-customer-popup" data-toggle="modal">
                <i class="fa fa-plus"></i> Add Customer
            </a>
        </div>
        <table id="supertable">
            <thead>
                <th>Customer Number</th>
                <th>Customer Thai Name</th>
                <th>Customer Eng Name</th>
                <th>Account Group</th>
                <th>Sale Team</th>
            	<th>Product Sale</th>
                <th>Service Sale</th>
                <th>Tax ID</th>
            	<th>Primary Contact</th>
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
                                        Customer Number
                                    </label>
                                    <input type="text" name="customer_no" class="form-control" placeholder="Enter Customer Number" id="customer_no" readonly>
                                </div>
                                <div class="form-group">
                                    <label for="customer_name">
                                        Customer Name
                                    </label>
                                    <input type="text" name="customer_name" class="form-control" placeholder="Enter Customer Name" id="customer_name" required>
                                </div>
                                <div class="form-group">
                                    <label for="customer_eng_name">
                                        Customer English Name
                                    </label>
                                    <input type="text" placeholder="Enter Customer English Name" name="customer_eng_name" id="customer_eng_name" class="form-control" required>
                                </div>
                                <div class="form-group">
                                    <label for="taxid">
                                        Tax ID
                                    </label>
                                    <input type="text" class="form-control" placeholder="Enter Tax ID" name="taxid" id="edit-taxid" maxlength="13" required>
                                    <small class="form-text text-muted" id="edit-taxid-warning">Enter 13 digits only</small>
                                </div>
                                <div class="form-group">
                                    <label for="account_group">
                                        Account Group
                                    </label>
                                    <select name="account_group" class="form-control">
                                        <option>Broadcast</option>
                                        <option>Corporate</option>
                                        <option>Education</option>
                                        <option>Factory</option>
                                        <option>Financial Institution</option>
                                        <option>Food and Beverage</option>
                                        <option>Government/Enterprise</option>
                                        <option>Hospital</option>
                                        <option>Hotel</option>
                                        <option>Individual</option>
                                        <option>Industry</option>
                                        <option>Logistic</option>
                                        <option>Oil and Gas</option>
                                        <option>Retail</option>
                                        <option>Synergize Group</option>
                                        <option>System Integrator</option>
                                        <option>Telecom</option>
                                        <option>Other</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="sale_team">
                                        Sale Team
                                    </label>
                                    <input type="text" placeholder="Enter Sale Team" name="sale_team" class="form-control" id="sale_team" required>
                                </div>
                                <div class="form-group">
                                    <label for="account_owner">
                                        Product Sale
                                    </label>
                                    <input type="text" placeholder="Enter Product Sale" name="product_sale" id="product_sale" class="form-control" required>
                                </div>
                                <div class="form-group">
                                    <label for="service_sale">
                                        Service Sale
                                    </label>
                                    <input type="text" placeholder="Enter Service Sale" name="service_sale" id="service_sale" class="form-control" required>
                                </div>
                                <div class="form-group">
                                    <label for="primary_contact">
                                        Primary Contact
                                    </label>
                                    <input type="text" placeholder="Enter Primary Contact" name="primary_contact" id="primary_contact" class="form-control" required>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">Cancel</button>
                                <button type="submit" class="btn btn-primary" id="addUserBtn">Edit Customer</button>
                            </div> 
                        </form>
                    </div>
                </div>
            </div>

            <div class="modal" tabindex="-1" role="dialog" id="add-customer-popup">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Add Customer</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </div>
                    <form id="add-customer-form" accept-charset="UTF-8">
                        <div class="modal-body">
                            <div class="form-group">
                                <label for="customer_name">
                                    Customer Number
                                </label>
                                <input type="text" name="customer_no" class="form-control" placeholder="Enter Customer Number" id="customer_no" required>
                            </div>
                            <div class="form-group">
                                <label for="customer_name">
                                    Customer Thai Name
                                </label>
                                <input type="text" name="customer_name" class="form-control" placeholder="Enter Customer Thai Name" id="customer_name" required>
                            </div>
                            <div class="form-group">
                                <label for="customer_eng_name">
                                    Customer English Name
                                </label>
                                <input type="text" placeholder="Enter Customer English Name" name="customer_eng_name" id="customer_eng_name" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label for="taxid">
                                    Tax ID
                                </label>
                                <input type="text" class="form-control" placeholder="Enter Tax ID" name="taxid" id="add-taxid" maxlength="13" pattern="[0-9]{13}" required>
                                <small class="form-text text-muted" id="add-taxid-warning">Enter 13 digits only</small>
                            </div>
                            <div class="form-group">
                                <label for="account_group">
                                    Account Group
                                </label>
                                <select name="account_group" class="form-control">
                                    <option>Broadcast</option>
                                    <option>Corporate</option>
                                    <option>Education</option>
                                    <option>Factory</option>
                                    <option>Financial Institution</option>
                                    <option>Food and Beverage</option>
                                    <option>Government/Enterprise</option>
                                    <option>Hospital</option>
                                    <option>Hotel</option>
                                    <option>Individual</option>
                                    <option>Industry</option>
                                    <option>Logistic</option>
                                    <option>Oil and Gas</option>
                                    <option>Retail</option>
                                    <option>Synergize Group</option>
                                    <option>System Integrator</option>
                                    <option>Telecom</option>
                                    <option>Other</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="sale_team">
                                    Sale Team
                                </label>
                                <input type="text" placeholder="Enter Sale Team" name="sale_team" class="form-control" id="sale_team" required>
                            </div>
                            <div class="form-group">
                                <label for="account_owner">
                                    Product Sale
                                </label>
                                <input type="text" placeholder="Enter Product Sale" name="product_sale" id="product_sale" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label for="service_sale">
                                    Service Sale
                                </label>
                                <input type="text" placeholder="Enter Service Sale" name="service_sale" id="service_sale" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label for="primary_contact">
                                    Primary Contact
                                </label>
                                <input type="text" placeholder="Enter Primary Contact" name="primary_contact" id="primary_contact" class="form-control" required>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-primary" id="addUserBtn">Add Customer</button>
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
        console.log("READY");
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

            var add_modal = url.searchParams.get("add_modal")
            if (add_modal == 'true') {
                $('#add-customer-popup').modal('show');
            }

            console.log(<?php echo json_encode($_SESSION)?>);
        });
        window.addEventListener("beforeunload", function (e) {       
            
            let remember = "<?php echo $_SESSION['remember']?>";
    
            if(remember == "off"){
                console.log("UNDLOAD");
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