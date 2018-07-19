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
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
    <link rel="stylesheet" href="/srmsng/public/css/style.css">
</head>
<body>
<?php include_once('../admin/admin_nav.php'); ?>
    <main class="container">
        <div class="input-group page-title">
            <h1 style="margin-bottom:0;">Edit Asset</h1>
            <a class="btn btn-outline-secondary" href="./"> 
                Cancel
            </a>
        </div>
        <form id="edit-form">
            <div class="form-group">
                <label><h4>SNG Code</h4></label>
                <input type="text" class="form-control" name="sng_code" placeholder="SNG Code" readonly required/>
            </div>
            <div class="row">
                <div class="col">
                    <fieldset id="customer-choose">
                        <legend>Customer</legend>
                            <a href="#" style="float:right" id="add_customer" data-target="#add-customer-popup" data-toggle="modal">
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
                                <label class="required">Customer Code</label>
                                <div class="input-group">
                                    <input type="text" class="form-control" id="customer-code-search-field" name="customer_no" placeholder="Customer Code" required/>
                                    <div class="autofill-dropdown border" id="customer-code-dropdown">
                                    </div>
                                    <div class="input-group-append">
                                        <button class="btn btn-primary" type="button" id="customer-code-search"><i class="fa fa-search"></i></span>
                                    </div>
                                </div> 
                                <small class="form-text text-muted">Enter at least 3 characters and press enter to search.</small>
                            </div>
                            <div class="form-group">
                                <button class="btn btn-primary" type="button" id="customer-choose-button">Choose Customer</button>
                                <button class="btn btn-outline-secondary" type="button" id="customer-edit-button" data-target="#add-customer-popup" data-toggle="modal">Edit Customer</button>
                                <small class="form-text text-danger hidden" id="customer-warning">All fields must be filled.</small>
                            </div>
                    </fieldset>
                </div>
                <div class="col">
                    <fieldset id="location-choose">
                        <legend>Location</legend>
                        <a href="#" style="float:right" id="add_location" data-target="#add-location-popup" data-toggle="modal">
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
                            <label class="required">Location Code</label>
                            <div class="input-group">
                                <input type="text" class="form-control" name="location_code" id="location-code-search-field" placeholder="Location Code" required/>
                                <div class="autofill-dropdown border" id="location-code-dropdown">
                                </div>
                                <div class="input-group-append">
                                    <button class="btn btn-primary" type="button" id="location-code-search"><i class="fa fa-search"></i></span>
                                </div>
                            </div> 
                            <small class="form-text text-muted">Enter at least 3 characters and press enter to search.</small>
                        </div>
                        <div class="row">
                            <div class="col">
                                <div class="form-group">
                                    <label>House No.</label>
                                    <input type="text" class="form-control sub-field" name="house_no" id="house-no-field" placeholder="House No." readonly/>
                                </div>
                            </div>
                            <div class="col">
                                <div class="form-group">
                                    <label>Village No.</label>
                                    <input type="text" class="form-control sub-field" name="village_no" id="village-no-field" placeholder="Village No." readonly/>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Soi</label>
                            <input type="text" class="form-control sub-field" name="soi" id="soi-field" placeholder="Soi" readonly/>
                        </div>
                        <div class="form-group">
                            <label>Road</label>
                            <input type="text" class="form-control sub-field" name="road" id="road-field" placeholder="Road" readonly/>
                        </div>
                        <div class="row" class="disabled">
                            <div class="col">
                                <div class="form-group">
                                    <label>Sub-district</label>
                                    <input type="text" class="form-control sub-field" name="sub_district" id="sub-district-field" placeholder="Sub-district" readonly/>
                                </div>
                            </div>
                            <div class="col">
                                <div class="form-group">
                                    <label>District</label>
                                    <input type="text" class="form-control sub-field" name="district" id="district-field" placeholder="District" readonly/>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col">
                                <div class="form-group">
                                    <label>Province</label>
                                    <input type="text" class="form-control sub-field" name="province" id="province-field" placeholder="Province" readonly/>
                                </div>
                            </div>
                            <div class="col">
                                <div class="form-group">
                                    <label>Postal Code</label>
                                    <input type="text" class="form-control sub-field" name="postal_code" id="postal-field" placeholder="Postal Code" readonly/>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col">
                                <div class="form-group">
                                    <label>Region</label>
                                    <input type="text" class="form-control sub-field" name="region" id="region-field" placeholder="Region" readonly/>
                                </div>
                            </div>
                            <div class="col">
                                <div class="form-group">
                                    <label>Country</label>
                                    <input type="text" class="form-control sub-field" name="country" id="country-field" placeholder="Country" readonly/>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Store Phone</label>
                            <input type="text" class="form-control clear" id="store-phone-field" name="store_phone" placeholder="Store Phone" readonly/>
                        </div>
                        <div class="form-group">
                            <label for="store_phone">
                                Latitude
                            </label>
                            <input type="text" placeholder="Site Latitude" name="lat" id="latitude" class="form-control sub-field" required readonly>
                        </div>
                        <div class="form-group">
                            <label for="store_phone">
                                Longitude
                            </label>
                            <input type="text" placeholder="Site Longitude" name="lon" id="longitude" class="form-control sub-field" required readonly>
                        </div>
                        <div class="form-group">
                            <button class="btn btn-outline-secondary" type="button" id="location-edit-button" data-target="#add-location-popup" data-toggle="modal">Edit Location</button>
                            <small class="form-text text-danger hidden" id="location-warning">All fields must be filled.</small>
                        </div>
                    </fieldset>
                </div>
            </div>

            <div class="row" id="main-form">
                <div class="col">
                    <fieldset id="sale-order-choose">
                        <legend>Sale Order</legend>
                        <a href="#" style="float:right" id="add_sale_order" data-target="#add-sale-order-popup" data-toggle="modal">
                            <i class="fa fa-plus"></i> Add New Sale Order
                        </a>
                        <div class="form-group">
                            <label class="required">Sale Order</label>     
                            <div class="input-group">
                                <input type="text" class="form-control" id="sale-order-search-field" name="sale_order_no" placeholder="Sale Order" autocomplete="off" required/>
                                <div class="autofill-dropdown border" id="sale-order-dropdown">
                                </div>
                                <div class="input-group-append">
                                    <button class="btn btn-primary" type="button" id="sale-order-search"><i class="fa fa-search"></i></span>
                                </div>
                            </div>
                            <small class="form-text text-muted">Enter at least 3 characters and press enter to search.</small>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Year</label> 
                                    <input type="text" class="form-control sub-field" name="since" id="year-field" placeholder="Year" readonly required/>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Date Order</label>
                                    <input type="text" class="form-control sub-field" name="date_order" id="date-order-field" placeholder="Date Order" readonly required/>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>PO Number</label> 
                                    <input type="text" class="form-control sub-field" name="po_number" id="po-number-field" placeholder="PO Number" readonly required/>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>PO Date</label>
                                    <input type="text" class="form-control sub-field" name="po_date" id="po-date-field" placeholder="PO Date" readonly required/>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>D/O Number</label>
                            <input type="text" class="form-control sub-field" name="do_number" id="do-no-field" placeholder="D/O No." readonly required/>
                        </div>
                        <div class="form-group">
                            <button class="btn btn-outline-secondary" type="button" id="sale-order-edit-button" data-target="#add-sale-order-popup" data-toggle="modal">Edit Sale Order</button>
                        </div>
                    </fieldset>

                    <fieldset id="item-choose">
                        <legend>Item</legend>
                        <a href="#" style="float:right" data-target="#add-item-popup" data-toggle="modal">
                            <i class="fa fa-plus"></i> Add New Item
                        </a>
                        <div class="form-group">
                            <label class="required">Item Name</label>
                            <div class="input-group">
                                <input type="text" class="form-control" id="item-search-field" name="model" placeholder="Item Name" required autocomplete="true" required/>
                                <div class="autofill-dropdown border" id="item-dropdown">
                                </div>
                                <div class="input-group-append">
                                    <button class="btn btn-primary" type="button" id="item-search"><i class="fa fa-search"></i></span>
                                </div>
                            </div>
                            <small class="form-text text-muted">Enter at least 3 characters and press enter to search.</small>
                        </div>
                        <div class="form-group">
                            <label class="required">Item Number</label>
                            <div class="input-group">
                                <input type="text" class="form-control" name="itemnumber" id="item-number-search-field" placeholder="Item Number" required/>
                                <div class="autofill-dropdown border" id="item-number-dropdown">
                                </div>
                                <div class="input-group-append">
                                    <button class="btn btn-primary" type="button" id="item-number-search"><i class="fa fa-search"></i></span>
                                </div>
                            </div> 
                            <small class="form-text text-muted">Enter at least 3 characters and press enter to search.</small>
                        </div>
                        
                        <div class="form-group">
                            <label>Rate (kVA)</label>
                            <input type="text" class="form-control sub-field" name="power" id="power-field" placeholder="Rate" readonly required/>
                        </div>
                        <div class="form-group">
                            <button class="btn btn-outline-secondary" type="button" id="item-edit-button" data-target="#add-item-popup" data-toggle="modal">Edit Item</button>
                        </div>
                    </fieldset>

                    <fieldset>
                        <div class="form-group">
                            <label class="required">Serial Number</label>
                            <input type="text" class="form-control" name="serial" placeholder="Serial Number" id="serial-no-field" required/>
                        </div>
                        <div class="form-group">
                            <label>Installed by</label>
                            <select class="form-control" id="fse-dropdown" id="fse-field" name="fse_code">
                                <option value="0">-- Select Field Service Engineer --</option>
                            </select>
                        </div>
                    </fieldset>

                    <fieldset>
                        <legend>Contact</legend>
                        <div class="form-group">
                            <label>Contact Name</label>
                            <input type="text" class="form-control" name="contactname" placeholder="Contact Name" required/>
                        </div>
                        <div class="form-group">
                            <label>Contact Number</label>
                            <input type="text" class="form-control" name="contactnumber" placeholder="Contact Number" required/>
                        </div>
                    </fieldset>
                </div>
                <div class="col">
                    <fieldset>
                        <legend>Battery</legend>
                        <div class="form-group">
                            <label class="required">Battery Specification</label>
                            <select class="form-control" id="battery-dropdown" name="battery">
                                <option value="0">-- Select Battery Specification --</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Number of Batteries</label>
                            <input type="text" class="form-control" name="quantity" placeholder="Number of Batteries"/>
                        </div>
                        <div class="form-group">
                            <label>Battery Installation Date</label>
                            <input type="text" class="form-control" name="battery_date" placeholder="Battery Installation Date"/>
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
                    <fieldset>
                        <legend>Service Level Agreement</legend>
                        <div class="form-group">
                            <label>Condition</label>
                            <select name="sla_condition" class="form-control">
                                <option value="5x8">5x8</option>
                                <option value="7x24">7x24</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Respond on Site (hrs)</label>
                            <input type="text" class="form-control" name="sla_response" placeholder="Respond on Site"/>
                        </div>
                        <div class="form-group">
                            <label>Recovery Time (hrs)</label>
                            <input type="text" class="form-control" name="sla_recovery" placeholder="Recovery Time"/>
                        </div>
                    </fieldset>
                    <fieldset>
                        <legend>Preventive Maintenance</legend>
                        <div class="row">
                            <div class="col">
                                <div class="form-group">
                                    <label>PM/Year</label>
                                    <input type="text" class="form-control" name="pmyear" placeholder="PM/Year"/>
                                </div>
                            </div>
                            <div class="col">
                                <div class="form-group">
                                    <label>Next PM Date</label>
                                    <input type="text" class="form-control" name="nextpm" placeholder="Next PM Date"/>
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
                            <option value="Trade">Trade</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label><h4>Status</h4></label>
                        <select name="ups_status" class="form-control">
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
                        <button type="submit" class="btn btn-primary">Edit Asset</button>
                    </div> 
                </div>
            </div>    
        </form>


    <!--================== Modal ==================-->
        <div class="modal" tabindex="-1" role="dialog" id="add-customer-popup">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Add Customer</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </div>
                    <form id="add-customer-form">
                        <div class="modal-body">
                            <div class="form-group">
                                <label>Customer Number</label>
                                <input type="text" class="form-control" id="customer-no-field" name="customer_no" placeholder="Customer No."/>
                            </div>
                            <div class="form-group">
                                <label>Customer Name</label>
                                <input type="text" class="form-control" name="customer_name" placeholder="Customer Thai Name"/>
                            </div>
                            <div class="form-group">
                                <label>Customer English Name</label>
                                <input type="text" class="form-control" name="customer_eng_name" placeholder="Customer English Name"/>
                            </div>
                            <div class="form-group">
                                <label>Tax ID</label>
                                <input type="text" class="form-control" id="tax-id-field" name="taxid" placeholder="Tax ID"/>
                            </div>
                            <div class="form-group">
                                <label>Account Group</label>
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
                                <label>Sale Team</label>
                                <input type="text" class="form-control" name="sale_team" placeholder="Sale Team"/>
                            </div>
                            <div class="form-group">
                                <label>Product Sale</label>
                                <input type="text" class="form-control" id="product-sale-field" name="product_sale" placeholder="Product Sale"/>
                            </div>
                            <div class="form-group">
                                <label>Service Sale</label>
                                <input type="text" class="form-control" name="service_sale" placeholder="Service Sale"/>
                            </div>
                            <div class="form-group">
                                <label>Primary Contact</label>
                                <input type="text" class="form-control" id="primary-contact-field" name="primary_contact" placeholder="Primary Contact"/>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-primary">Submit</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="modal" tabindex="-1" role="dialog" id="add-location-popup">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Add New Location</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </div>
                    <form id="add-location-form">
                        <div class="modal-body">
                            <div class="form-group">
                                <label>Location Code</label>
                                <input type="text" class="form-control clear" id="location-code-field" name="location_code" placeholder="Location Code"/>
                            </div>
                            <div class="form-group">
                                <label>Site Name</label>
                                <input type="text" class="form-control clear" id="site-name-field" name="sitename" placeholder="Site Name"/>
                            </div>
                            <div class="form-group">
                                <label>House Number</label>
                                <input type="text" class="form-control clear" id="house-no-field" name="house_no" placeholder="House No."/>
                            </div>
                            <div class="form-group">
                                <label for="village_no">
                                    Village Number
                                </label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">หมู่</span>
                                    </div>
                                    <input type="text" placeholder="Enter Village Number" name="village_no" class="form-control clear" id="village_no">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="account_owner">
                                    Soi
                                </label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">ซอย</span>
                                    </div>
                                    <input type="text" placeholder="Soi" name="soi" id="soi" class="form-control clear">
                                </div>  
                            </div>
                            <div class="form-group">
                                <label for="account_owner">
                                    Road
                                </label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">ถนน</span>
                                    </div>
                                    <input type="text" placeholder="Enter Road" name="road" id="road" class="form-control clear">
                                </div>  
                            </div>
                            <div class="form-group">
                                <label for="sub_district">
                                    Sub District
                                </label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">ตำบล/แขวง</span>
                                    </div>
                                    <input type="text" placeholder="Enter Sub District" name="sub_district" id="sub_district" class="form-control clear">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="district">
                                    District
                                </label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">อำเภอ/เขต</span>
                                    </div>
                                    <input type="text" placeholder="Enter District" name="district" id="district" class="form-control clear">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="province">
                                    Province
                                </label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">จังหวัด</span>
                                    </div>
                                    <input type="text" placeholder="Enter Province" name="province" id="province" class="form-control clear">
                                </div>
                            </div>
                            <div class="form-group">
                                <label>Postal code</label>
                                <input type="text" class="form-control clear" id="postal-code-field" name="postal_code" placeholder="Postal Code"/>
                            </div>
                            <div class="form-group">
                                <label>Region</label>
                                <select name="region" id="region" class="form-control">
                                    <option value="กรุงเทพมหานคร">กรุงเทพมหานคร</option>
                                    <option value="กลาง">กลาง</option>
                                    <option value="ตะวันออกเฉียงเหนือ">ตะวันออกเฉียงเหนือ</option>
                                    <option value="ตะวันออก">ตะวันออก</option>
                                    <option value="เหนือ">เหนือ</option>
                                    <option value="ใต้">ใต้</option>
                                    <option value="ตะวันตก">ตะวันตก</option>
                                    <option value="อื่นๆ">อื่นๆ</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Country</label>
                                <input type="text" class="form-control clear" id="country-field" name="country" placeholder="Country" value="ไทย"/>
                            </div>
                            <div class="form-group">
                                <label>Store Phone</label>
                                <input type="text" class="form-control clear" id="store-phone-field" name="store_phone" placeholder="Store Phone"/>
                            </div>
                            <fieldset>
                                <div class="form-group">
                                    <label for="store_phone">
                                        Latitude
                                    </label>
                                    <input type="text" placeholder="Site Latitude" name="lat" id="latitude" class="form-control" required>
                                </div>
                                <div class="form-group">
                                    <label for="store_phone">
                                        Longitude
                                    </label>
                                    <input type="text" placeholder="Site Longitude" name="lon" id="longitude" class="form-control" required>
                                </div>
                                <button type="button" class="btn btn-primary" onClick="openMap()">Find on Map</button>
                            </fieldset>
                            <div class="form-group">
                                <label>Customer Number</label>
                                <input type="text" class="form-control" id="customer-no-add" name="customer_no" placeholder="Customer Number" readonly/>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-primary">Submit</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="modal" tabindex="-1" role="dialog" id="add-sale-order-popup">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Add Sale Order</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </div>
                    <form id="add-sale-order-form">
                        <div class="modal-body">
                            <div class="form-group">
                                <label>Sale Order Number</label>
                                <input type="text" class="form-control" id="sale-order-no-field" name="sale_order_no" placeholder="Sale Order No."/>
                            </div>
                            <div class="form-group">
                                <label>Date Order</label>
                                <input type="text" class="form-control" id="date-order-field" name="date_order" placeholder="Date Ordered"/>
                            </div>
                            <div class="form-group">
                                <label>Since</label>
                                <select list="birth_year"  class="form-control" id="since-field" name="since" autocomplete="off">
                                    <datalist id="birth_year">
                                        <?php 
                                        $right_now = getdate();
                                        $this_year = $right_now['year'];
                                        $start_year = 2008;
                                        while ($start_year <= $this_year) {
                                            echo "<option value='" . $this_year . "'>{$this_year}</option>";
                                            $this_year--;
                                        }
                                        ?>
                                    </datalist>
                                </select>
                                <!-- <input type="text" class="form-control" id="since-field" name="since"/> -->
                            </div>
                            <div class="form-group">
                                <label>PO Number</label>
                                <input type="text" class="form-control" id="po-number-field" name="po_number" placeholder="PO Number"/>
                            </div>
                            <div class="form-group">
                                <label>PO Date</label>
                                <input type="text" class="form-control" id="po-date-field" name="po_date" placeholder="PO Date"/>
                            </div>
                            <div class="form-group">
                                <label>D/O Number</label>
                                <input type="text" class="form-control" id="do-number-field" name="do_number" placeholder="D/O Number"/>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-primary">Submit</button>
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
                    <form id="add-item-form">
                        <div class="modal-body">
                            <div class="form-group">
                                <label>Item Number</label>
                                <input type="text" class="form-control" id="item-no-field" name="itemnumber" placeholder="Item Number"/>
                            </div>
                            <div class="form-group">
                                <label>Model</label>
                                <input type="text" class="form-control" id="model-field" name="model" placeholder="Item Name"/>
                            </div>
                            <div class="form-group">
                                <label>Rate</label>
                                <input type="text" class="form-control" id="power-field" name="power" placeholder="Rate"/>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-primary">Submit</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="modal" role="dialog" id="map-modal">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header" style="display:block; padding-bottom:0;">
                        <h5 class="modal-title">Mark Location on Map</h5>
                        <div class="form-group" style="margin-top:15px">
                            <input type="text" placeholder="Search" name="map_search" id="map-search-field" class="form-control">
                        </div>
                    </div>
                    <div id="map" style="width:100%; height:500px"></div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary" data-dismiss="modal" data-toggle="modal" data-target="#add-location-popup">Cancel</button>
                        <button type="submit" class="btn btn-primary" id="mark-location">Mark</button>
                    </div>
                    <input type="hidden" id="from-type" disabled/>
                </div>
            </div>
        </div>

        <!-- End Modal -->
    </main>
</body>
<script src="https://code.jquery.com/jquery-3.3.1.min.js" integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.0/js/bootstrap.min.js" integrity="sha384-uefMccjFJAIv6A+rW+L4AHf99KvxDjWSu1z9VI8SKNVmz4sk7buKt/6v9KI65qnm" crossorigin="anonymous"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
<script src="/srmsng/public/js/submit.js"></script>
<script src="/srmsng/public/js/add_asset.js"></script>
<script src="/srmsng/public/js/map_places.js"></script>
<script
    src="https://maps.googleapis.com/maps/api/js?key=AIzaSyC8NC0ToCiqmrDLjvOu9H74ZjeWtgQkU7E&libraries=places&callback=initMap">
    </script>
<script>
    // Initialize Time/Date Picker
    $(function() {
        $('input[name="startwarranty"], input[name="endwarranty"], input[name="battery_date"], input[name="nextpm"], #add-sale-order-popup input[name="po_date"], #add-sale-order-popup input[name="date_order"]').daterangepicker({
            timePicker: true,
            "timePicker24Hour": true,
            singleDatePicker: true,
            startDate: moment().startOf('hour'),
            endDate: moment().startOf('hour').add(32, 'hour'),
            locale: {
                format: 'Y-MM-DD H:mm:ss'
            }
        });

        $('input[name="startwarranty"]').on("apply.daterangepicker", (start) => {
            var val = $('input[name="startwarranty"]').val();
            var mm = moment(val, "DD/M/Y H:mm").add(2, 'years');
            // mm.add(2, "year");
            // console.log(mm);
            $('input[name="endwarranty"]').data('daterangepicker').setStartDate(mm);
        })
    });

    // Remember Me
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
    function fetchDataFromSNGCode() {
        var url_string = window.location.href;
        var url = new URL(url_string);
        var sng_code = url.searchParams.get("sng_code");

        fetch("/srmsng/public/index.php/api/admin/getasset?sng_code=" + sng_code)
        .then(res => {
            // console.log(res);
            return res.json();
        })
        .then(data => {
            console.log(data);
            for (var value in data[0]) {
                $(".form-control[name='" + value + "']").val(data[0][value]);
            }
            $(".form-control[name='toc']").val(data[0]['typeofcontract']);
            // Lat lng
            $("#location-choose .sub-field[name='lat']").val(data[0]["latitude"]);
            $("#location-choose .sub-field[name='lon']").val(data[0]["longitude"]);
        });
    }
    // Map
    function openMap() {
        $('#add-location-popup .close').trigger('click');

        $("#map-modal").modal("toggle");
        
        var lat = $('input[name="lat"]').val();
        var lng = $('input[name="lon"]').val();
        initMarker(lat,lng);
    }
    $(document).ready( function() {
        fetchDataFromSNGCode();
        
        // Map
        initMap();
        $("#mark-location").on("click", function() {
            $("#map-modal").modal("toggle");
            $("#add-location-popup").modal("toggle");
        });

        // Reset Add Location lat and lng
        $('#add-location-button').on('click', function() {
            $('#add-location-popup input[name="latitude"]').val('');
            $('#add-location-popup input[name="longitude"]').val('');
        });
    });
</script>