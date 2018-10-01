<?php 
    require('../cookie_validate_admin.php');
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
    <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css" rel="stylesheet">
    <link href='https://fonts.googleapis.com/css?family=Roboto:400,700,300' rel='stylesheet' type='text/css'>
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.16/css/jquery.dataTables.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
    <link rel="stylesheet" href="/srmsng/public/css/style.css">
</head>
<body>
<?php include_once('../admin/admin_nav.php'); ?>
    <main class="container">
        <div class="input-group page-title">
            <h1 style="margin-bottom:0;">Add Asset</h1>
            <a class="btn btn-outline-secondary" href="./"> 
                Cancel
            </a>
        </div>
        <form id="add-form">
            <div class="form-group">
                <label><h4>SNG Code</h4></label>
                <input type="text" class="form-control" name="sng_code" placeholder="SNG Code" />
            </div>
            <div class="row">
                <div class="col">
                    <fieldset id="customer-choose">
                        <legend>Customer</legend>
                            <a href="#" style="float:right">
                                <i class="fa fa-plus"></i> Add New Customer
                            </a>
                            <div class="form-group">
                                <label class="required">Customer Name</label>
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
                                <input type="text" class="form-control sub-field" name="customer_no" placeholder="Customer Code" readonly/>
                            </div>
                            <div class="form-group">
                                <button class="btn btn-primary" type="button" id="customer-choose-button">Choose Customer</button>
                                <small class="form-text text-danger hidden" id="customer-warning">All fields must be filled.</small>
                            </div>
                    </fieldset>
                </div>
                <div class="col">
                    <fieldset id="location-choose"  class="disabled">
                        <legend>Location</legend>
                        <a href="#" style="float:right">
                            <i class="fa fa-plus"></i> Add New Location
                        </a>
                        <div class="form-group">
                            <label class="required">Site Name</label>
                            <div class="input-group">
                                <input type="text" class="form-control" id="location-search-field" name="sitename" placeholder="Site Name" autocomplete=off required/>
                                <div class="autofill-dropdown border" id="location-dropdown">
                                </div>
                                <div class="input-group-append">
                                    <button class="btn btn-primary" type="button" id="location-search"><i class="fa fa-search"></i></span>
                                </div>
                            </div>
                            <small class="form-text text-muted">Enter at least 3 characters and press enter to search.</small>
                        </div>
                        <div class="form-group">
                            <label>Location Code</label>
                            <input type="text" class="form-control sub-field" name="location_code" placeholder="Location Code" readonly/>
                        </div>
                        <div class="row">
                            <div class="col">
                                <div class="form-group">
                                    <label>House No.</label>
                                    <input type="text" class="form-control sub-field" name="house_no" placeholder="House No." readonly/>
                                </div>
                            </div>
                            <div class="col">
                                <div class="form-group">
                                    <label>Village No.</label>
                                    <input type="text" class="form-control sub-field" name="village_no" placeholder="Village No." readonly/>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Road</label>
                            <input type="text" class="form-control sub-field" name="road" placeholder="Road" readonly/>
                        </div>
                        <div class="row" class="disabled">
                            <div class="col">
                                <div class="form-group">
                                    <label>Sub-district</label>
                                    <input type="text" class="form-control sub-field" name="sub_district" placeholder="Sub-district" readonly/>
                                </div>
                            </div>
                            <div class="col">
                                <div class="form-group">
                                    <label>District</label>
                                    <input type="text" class="form-control sub-field" name="district" placeholder="District" readonly/>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col">
                                <div class="form-group">
                                    <label>Province</label>
                                    <input type="text" class="form-control sub-field" name="province" placeholder="Province" readonly/>
                                </div>
                            </div>
                            <div class="col">
                                <div class="form-group">
                                    <label>Postal Code</label>
                                    <input type="text" class="form-control sub-field" name="postal_code" placeholder="Postal Code" readonly/>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col">
                                <div class="form-group">
                                    <label>Region</label>
                                    <input type="text" class="form-control sub-field" name="region" placeholder="Region" readonly/>
                                </div>
                            </div>
                            <div class="col">
                                <div class="form-group">
                                    <label>Country</label>
                                    <input type="text" class="form-control sub-field" name="country" placeholder="Country" readonly/>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <button class="btn btn-primary" type="button" id="location-choose-button">Choose Location</button>
                            <small class="form-text text-danger hidden" id="location-warning">All fields must be filled.</small>
                        </div>
                    </fieldset>
                </div>
            </div>

            <div class="row disabled" id="main-form">
                <div class="col">
                    <fieldset id="sale-order-choose">
                        <legend>Sale Order</legend>
                        <a href="#" style="float:right">
                            <i class="fa fa-plus"></i> Add New Sale Order
                        </a>
                        <div class="form-group">
                            <label class="required">Sale Order</label>     
                            <div class="input-group">
                                <input type="text" class="form-control" id="sale-order-search-field" name="sale_order" placeholder="Sale Order" autocomplete="off" required/>
                                <div class="autofill-dropdown border" id="sale-order-dropdown">
                                </div>
                                <div class="input-group-append">
                                    <button class="btn btn-primary" type="button" id="sale-order-search"><i class="fa fa-search"></i></span>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Year</label> 
                                    <input type="text" class="form-control sub-field" name="since" placeholder="Year" readonly/>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Date Order</label>
                                    <input type="text" class="form-control sub-field" name="date_order" placeholder="Date Order" readonly/>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>PO Number</label> 
                                    <input type="text" class="form-control sub-field" name="po_number" placeholder="PO Number" readonly/>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>PO Date</label>
                                    <input type="text" class="form-control sub-field" name="po_date" placeholder="PO Date" readonly/>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>D/O Number</label>
                            <input type="text" class="form-control sub-field" name="do_number" placeholder="D/O No." readonly/>
                        </div>
                    </fieldset>

                    <fieldset id="item-choose">
                        <legend>Item</legend>
                        <a href="#" style="float:right">
                            <i class="fa fa-plus"></i> Add New Item
                        </a>
                        <div class="form-group">
                            <label class="required">Item Name</label>
                            <div class="input-group">
                            <input type="text" class="form-control" id="item-search-field" name="model" placeholder="(Require)" required/>
                                <div class="input-group-append">
                                    <button class="btn btn-primary" type="button" id="item-search"><i class="fa fa-search"></i></span>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Item Number</label>
                            <input type="text" class="form-control" name="itemnumber" placeholder="Item Number" readonly/>
                        </div>
                        <div class="form-group">
                            <label>Power (kVA)</label>
                            <input type="text" class="form-control" name="kva" placeholder="kVA" readonly/>
                        </div>
                    </fieldset>

                    <fieldset>
                        <div class="form-group">
                            <label class="required">Serial Number</label>
                            <input type="text" class="form-control required" name="serial" placeholder="(Require)" required/>
                        </div>
                        <div class="form-group">
                            <label>Installed by</label>
                            <input type="text" class="form-control" name="install_by" placeholder="Installed By"/>
                        </div>
                    </fieldset>
                </div>
                <div class="col">
                    <fieldset>
                        <legend>Battery</legend>
                        <div class="form-group">
                            <label>Battery Type</label>
                            <input type="text" class="form-control required" name="battery" placeholder="(Require)" required/>
                        </div>
                        <div class="form-group">
                            <label>Number of Batteries</label>
                            <input type="text" class="form-control" name="quantity" placeholder="Number of Batteries"/>
                        </div>
                        <div class="form-group">
                            <label>Battery Date</label>
                            <input type="text" class="form-control" name="battery_date" placeholder="Battery Date"/>
                        </div>
                    </fieldset>

                    <fieldset>
                        <legend>Warranty</legend>
                        <div class="row">
                            <div class="col">
                                <div class="form-group">
                                    <label>Start Warranty</label>
                                    <input type="text" class="form-control" name="startwarranty" placeholder="Start Warranty"/>
                                </div>
                            </div>
                            <div class="col">
                                <div class="form-group">
                                    <label>End Warranty</label>
                                    <input type="text" class="form-control" name="endwarranty" placeholder="End Warranty"/>
                                </div>
                            </div>
                        </div>
                    </fieldset>

                    <div class="form-group">
                        <label><h4>Type of Contract</h4></label>
                        <select name="toc" class="form-control">
                            <option value="Warranty">Warranty</option>
                            <option value="Contract">Contract</option>
                            <option value="Out of Contract">Out of Contract</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label><h4>Status</h4></label>
                        <select name="status" class="form-control">
                            <option value="Normal">Normal</option>
                            <option value="Defective">Defective</option>
                            <option value="Not Functioning">Not Functioning</option>
                            <option value="Inactive">Inactive</option>
                        </select>
                    </div>
                    <div class="form-group" style="margin-top:40px; text-align:right">
                        <a class="btn btn-outline-secondary" href="./"> 
                            Cancel
                        </a>
                        <button type="submit" class="btn btn-primary">Add Asset</button>
                    </div> 
                </div>
            </div>    
        </form>
    </main>
</body>
<script src="https://code.jquery.com/jquery-3.3.1.min.js" integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.0/js/bootstrap.min.js" integrity="sha384-uefMccjFJAIv6A+rW+L4AHf99KvxDjWSu1z9VI8SKNVmz4sk7buKt/6v9KI65qnm" crossorigin="anonymous"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
<script src="/srmsng/public/js/submit.js"></script>
<script src="/srmsng/public/js/add_asset.js"></script>
<script>
    $(function() {
        $('input[name="startwarranty"]').daterangepicker({
            timePicker: true,
            "timePicker24Hour": true,
            singleDatePicker: true,
            startDate: moment().startOf('hour'),
            endDate: moment().startOf('hour').add(32, 'hour'),
            locale: {
            format: 'DD/M/Y hh:mm'
            }
        });
        $('input[name="endwarranty"]').daterangepicker({
            timePicker: true,
            "timePicker24Hour": true,
            singleDatePicker: true,
            startDate: moment().startOf('hour'),
            endDate: moment().startOf('hour').add(32, 'hour'),
            locale: {
            format: 'DD/M/Y hh:mm'
            }
        });
        $(function() {
        $('input[name="cm_time"]').daterangepicker({
            timePicker: true,
            "timePicker24Hour": true,
            singleDatePicker: true,
            startDate: moment().startOf('hour'),
            endDate: moment().startOf('hour').add(32, 'hour'),
            locale: {
            format: 'DD/M/Y hh:mm'
            }
        });
        $('input[name="battery_date"]').daterangepicker({
            timePicker: true,
            "timePicker24Hour": true,
            singleDatePicker: true,
            startDate: moment().startOf('hour'),
            endDate: moment().startOf('hour').add(32, 'hour'),
            locale: {
            format: 'DD/M/Y hh:mm'
            }
        });
    });
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