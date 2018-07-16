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
            Item added
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="alert alert-success" id="update-success">
            Item updated
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="input-group page-title">
            <h1 style="margin-bottom:0;">Items</h1>
            <a href="#" class="btn btn-primary" data-target="#add-item-popup" data-toggle="modal">
                <i class="fa fa-plus"></i> Add item
            </a>
        </div>
        <table id="supertable">
            <thead>
                <th>Item Number</th>
                <th>Model</th>
            	<th>Rate (kVA)</th>
                <th>Item Class</th>
                <th>Category</th>
                <th>Lot</th>
            	<th>Serial</th>
                <th>Warranty</th>
                <th>Created On</th>
                <th>Action</th>
            </thead>
            <tbody id="maintable">
         
            </tbody>
        </table>

         <div class="modal" tabindex="-1" role="dialog" id="edit-item-popup">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Edit Item</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </div>
                        <form method="post" id="edit-item-form">
                            <div class="modal-body">
                                <div class="form-group">
                                    <label for="itemnumber">
                                        Item Number
                                    </label>
                                    <input type="text" name="itemnumber" class="form-control" placeholder="Enter Item Number" id="item_no" readonly>
                                </div>
                                <div class="form-group">
                                    <label for="model">
                                        Model
                                    </label>
                                    <input type="text" name="model" class="form-control" placeholder="Enter Model Name" id="model" required>
                                </div>
                                <div class="form-group">
                                    <label for="item_class">
                                        Item Class
                                    </label>
                                    <select class="form-control" name="item_class" id="item_class">
                                        <option>Non-stock</option>
                                        <option>Stock</option>
                                        <option>Service</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="category">
                                        Category
                                    </label>
                                    <select class="form-control" name="category" id="category">
                                        <option>ACCESSORIES</option>
                                        <option>CONSUMABLES P&S</option>
                                        <option>CONSUMABLESOFFICE</option>
                                        <option>HARDWARE</option>
                                        <option>OTHER</option>
                                        <option>SERVICES</option>
                                        <option>SOFTWARE</option>
                                        <option>UNKNOWN</option>
                                    </select>
                                </div>
                                <label>Lot</label>
                                <div class="form-group">
                                    <div class="custom-control-inline">
                                        <input type="radio" name="is_lot" value="Y" id="is_lot_yes">
                                        <label class="radio">
                                            Yes
                                        </label>
                                    </div>
                                    <div class="custom-control-inline">
                                        <input type="radio" name="is_lot" value="N"  id="is_lot_no">
                                        <label class="radio">
                                            No
                                        </label>
                                    </div>
                                </div>
                                <label for="is_serial">Serial</label>
                                <div class="form-group">
                                    <div class="custom-control-inline">
                                        <input type="radio" name="is_serial" value="Y" id="is_serial_yes">
                                        <label class="radio">
                                            Yes
                                        </label>
                                    </div>
                                    <div class="custom-control-inline">
                                        <input type="radio" name="is_serial" value="N" id="is_serial_no">
                                        <label class="radio">
                                            No
                                        </label>
                                    </div>
                                </div>
                                <label for="is_warranty">Warranty</label>
                                <div class="form-group">
                                    <div class="custom-control-inline">
                                        <input type="radio" name="is_warranty" value="Y" id="is_warranty_yes">
                                        <label class="radio">
                                            Yes
                                        </label>
                                    </div>
                                    <div class="custom-control-inline">
                                        <input type="radio" name="is_warranty" value="N" id="is_warranty_no">
                                        <label class="radio">
                                            No
                                        </label>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="power">
                                        Power
                                    </label>
                                    <input type="text" placeholder="Enter Power" name="power" id="power" class="form-control">
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">Cancel</button>
                                <button type="submit" class="btn btn-primary" id="editItembtn">Edit Item</button>
                            </div> 
                        </form>
                    </div>
                </div>
            </div>

            <div class="modal" tabindex="-1" role="dialog" id="add-item-popup">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Add Item</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </div>
                    <form id="add-item-form" accept-charset="UTF-8">
                        <div class="modal-body">
                            <div class="form-group">
                                <label for="itemnumber">
                                   Item Number
                                </label>
                                <input type="text" name="itemnumber" class="form-control" placeholder="Enter Item Number" id="itemnumber" required>
                            </div>
                            <div class="form-group">
                                <label for="model">
                                    Model
                                </label>
                                <input type="text" name="model" class="form-control" placeholder="Enter Model Name" id="model" required>
                            </div>
                            <div class="form-group">
                                <label for="item_class">
                                    Item Class
                                </label>
                                <select class="form-control" name="item_class" id="item_class">
                                    <option>Non-stock</option>
                                    <option>Stock</option>
                                    <option>Service</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="category">
                                    Category
                                </label>
                                <select class="form-control" name="category">
                                    <option>ACCESSORIES</option>
                                    <option>CONSUMABLES P&S</option>
                                    <option>CONSUMABLESOFFICE</option>
                                    <option>HARDWARE</option>
                                    <option>OTHER</option>
                                    <option>SERVICES</option>
                                    <option>SOFTWARE</option>
                                    <option>UNKNOWN</option>
                                </select>
                            </div>
                            <label>Lot</label>
                            <div class="form-group">
                                <div class="custom-control-inline">
                                    <input type="radio" name="is_lot" value="Y">
                                    <label class="radio">
                                        Yes
                                    </label>
                                </div>
                                <div class="custom-control-inline">
                                    <input type="radio" name="is_lot" value="N">
                                    <label class="radio">
                                        No
                                    </label>
                                </div>
                            </div>
                            <label for="is_serial">Serial</label>
                            <div class="form-group">
                                <div class="custom-control-inline">
                                    <input type="radio" name="is_serial" value="Y">
                                    <label class="radio">
                                        Yes
                                    </label>
                                </div>
                                <div class="custom-control-inline">
                                    <input type="radio" name="is_serial" value="N">
                                    <label class="radio">
                                        No
                                    </label>
                                </div>
                            </div>
                            <label for="is_warranty">Warranty</label>
                            <div class="form-group">
                                <div class="custom-control-inline">
                                    <input type="radio" name="is_warranty" value="Y">
                                    <label class="radio">
                                        Yes
                                    </label>
                                </div>
                                <div class="custom-control-inline">
                                    <input type="radio" name="is_warranty" value="N">
                                    <label class="radio">
                                        No
                                    </label>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="power">
                                    Power
                                </label>
                                <input type="text" placeholder="Enter Power" name="power" id="power" class="form-control">
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-primary" id="addItemBtn">Add Item</button>
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
    <script src="/srmsng/public/js/fetch_item.js"></script>
    <script src="/srmsng/public/js/submit.js"></script>
    <script src="/srmsng/public/js/onclose.js"></script>
    <script>
        console.log("READY");
        // console.log(String()+"<= This is String");

        //     console.log("NULL");
        // }else{
        //     console.log("NOT NULL");
        // }
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