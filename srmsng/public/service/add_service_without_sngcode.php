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
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.1.0/css/all.css" integrity="sha384-lKuwvrZot6UHsBSfcMvOkWwlCMgc0TaWr+30HWe3a4ltaBwTZhyTEggF5tJv8tbt" crossorigin="anonymous">
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
            <h1 style="margin-bottom:0;">Add Service (without sng-code)</h1>
        </div>

        <form id="add-service-form"  data-type="add">
            <div class="row">
                <div class="col">
                    <fieldset>
                        <legend>Service Details</legend>
                        <div class="form-group">
                            <label>Title</label>
                            <input type="text" class="form-control" name="title" placeholder="Service Title" maxlength="127" required/>
                        </div>
                        <div class="form-group">
                            <label>Description</label>
                            <textarea type="text" class="form-control" name="description" placeholder="Service Description" maxlength="255"></textarea>
                            <small class="form-text text-muted">Maximum: 255 Characters</small>
                        </div>
                        <div class="form-group">
                            <label>Work Class</label>
                            <select name="work_class" class="form-control" required>
                                <option value="">-- Select Work Class --</option>
                                <option>Preventive Maintenance</option>
                                <option>Building Preventive Maintenance - UOB</option>
                                <option>Battery Replacement</option>
                                <option>Install</option>
                                <option>Standby</option>
                                <option>Survey</option>
                                <option>Others</option>
                            </select>
                        </div>
                    </fieldset>
                </div>

                <div class="col">
                    <fieldset>
                        <legend>Contact Information</legend>
                        <div class="form-group">
                            <label>Contact Name</label>
                            <input type="text" class="form-control" name="contact_name" placeholder="Customer Name"/>
                        </div>
                        <div class="form-group">
                            <label>Phone Number</label>
                            <input type="text" class="form-control" name="contact_number" placeholder="Phone Number" maxlength="10" size="10" id="contact-number-field"/>
                            <small class="form-text text-muted" id="contact-number-warning">Mobile phone number only (10 digits)</small>
                        </div>
                        <div class="form-group">
                            <label>Alternate Number</label>
                            <input type="text" class="form-control" name="alternate_number" placeholder="Phone Number" maxlength="10" size="10" id="alternate-number-field"/>
                            <small class="form-text text-muted" id="alternate-number-warning">Mobile phone number only (10 digits)</small>
                        </div>
                    </fieldset>
                </div>
            </div>

            <div class="row">
                <?php /* //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////// */ ?>
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
                                    <button class="btn btn-primary" type="button" id="customer-search" /><i class="fa fa-search"></i></span>
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
                                    <button class="btn btn-primary" type="button" id="customer-code-search" /><i class="fa fa-search"></i></span>
                                </div>
                            </div>
                            <small class="form-text text-muted">Enter at least 3 characters and press enter to search.</small>
                        </div>
                        <div class="form-group">
                            <button class="btn btn-primary" type="button" id="customer-choose-button">Choose Customer</button>
                            <button class="btn btn-outline-secondary disabled" type="button" id="customer-edit-button" data-target="#add-customer-popup" data-toggle="modal">Edit Customer</button>
                            <small class="form-text text-danger hidden" id="customer-warning">All fields must be filled.</small>
                        </div>
                    </fieldset>
                </div>
                <div class="col">
                    <fieldset id="location-choose"  class="disabled">
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
                                    <button class="btn btn-primary" type="button" id="location-search" /><i class="fa fa-search"></i></span>
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
                                    <button class="btn btn-primary" type="button" id="location-code-search" /><i class="fa fa-search"></i></span>
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
                            <button class="btn btn-outline-secondary disabled" type="button" id="location-edit-button" data-target="#add-location-popup" data-toggle="modal" />Edit Location</button>
                            <small class="form-text text-danger hidden" id="location-warning">All fields must be filled.</small>
                        </div>
                    </fieldset>
                </div>
                <?php /* //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////// */ ?>
            </div>

            <fieldset>
                <legend>Assign Service</legend>
                <div class="form-group" id="fse-fieldset">
                    <label>Assign Field Service Engineer</label>
                    <div class="select-multiple" id="fse-dropdown"></div>
                    <label>Assign Field Service Engineer Leader:</label>

                    <div id="selected-fse">
                        <span class="selected-fse-none">None</span>
                    </div>
                </div>
                <div class="form-group">
                    <label>Due Date</label>
                    <input type="text" class="form-control" name="due_date" placeholder="YYYY-MM-DD" id="due_date" autocomplete="off" required/>
                </div>
                <div class="form-group">
                    <label>Job Status</label>
                    <select name="job_status" class="form-control" required>
                        <option value="">-- Select Job Status --</option>
                        <option value="Closed">Closed</option>
                        <option value="Pending">Pending</option>
                        <option value="Work in Progress">Work in Progress</option>
                        <option value="Acknowledged">Acknowledged</option>
                        <option value="Assigned">Assigned</option>
                        <option value="Fixed by Phone">Fixed by Phone</option>
                    </select>
                </div>
            </fieldset>

            <div class="form-group" style="margin-top:40px; display: flex; align-items: center; justify-content: flex-end; text-align:right ">
                <i class="fas fa-spinner fa-spin text-center" style="font-size:22px; display: none" id="loader"></i>
                <a class="btn btn-outline-secondary" href="/srmsng/public/service"  style="margin-left: 10px">
                    Cancel
                </a>
                <button type="submit" class="btn btn-primary" style="margin-left: 5px">Add Service</button>
            </div>
        </form>
        <!--================== Modal ==================-->
        <div class="modal" tabindex="-1" role="dialog" id="add-customer-popup">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Add Customer</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close" />
                        <span aria-hidden="true">&times;</span>
                    </div>
                    <form id="add-customer-form">
                        <div class="modal-body">
                            <div class="form-group">
                                <label>Customer Number</label>
                                <input type="text" class="form-control" id="customer-no-field" name="customer_no" placeholder="Customer No." required/>
                            </div>
                            <div class="form-group">
                                <label>Customer Name</label>
                                <input type="text" class="form-control" name="customer_name" placeholder="Customer Thai Name" required/>
                            </div>
                            <div class="form-group">
                                <label>Customer English Name</label>
                                <input type="text" class="form-control" name="customer_eng_name" placeholder="Customer English Name" required/>
                            </div>
                            <div class="form-group">
                                <label>Tax ID</label>
                                <input type="text" class="form-control" id="tax-id-field" name="taxid" placeholder="Tax ID" required/>
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
                            <button type="button" class="btn btn-outline-secondary" data-dismiss="modal" />Cancel</button>
                            <button type="submit" class="btn btn-primary" />Submit</button>
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
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close" />
                        <span aria-hidden="true">&times;</span>
                    </div>
                    <form id="add-location-form">
                        <div class="modal-body">
                            <div class="form-group">
                                <label>Location Code</label>
                                <input type="text" class="form-control clear" id="location-code-field" name="location_code" placeholder="Location Code" required/>
                            </div>
                            <div class="form-group">
                                <label>Site Name</label>
                                <input type="text" class="form-control clear" id="site-name-field" name="sitename" placeholder="Site Name" required/>
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
                                    <input type="text" placeholder="Enter Province" name="province" id="province" class="form-control clear" required>
                                </div>
                            </div>
                            <div class="form-group">
                                <label>Postal code</label>
                                <input type="text" class="form-control clear" id="postal-code-field" name="postal_code" placeholder="Postal Code" required/>
                            </div>
                            <div class="form-group">
                                <label>Region</label>
                                <select name="region" id="region" class="form-control ">
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
                                <input type="text" class="form-control clear" id="country-field" name="country" placeholder="Country" value="ไทย" required/>
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
                                <button type="button" class="btn btn-primary" onClick="openMap()" />Find on Map</button>
                            </fieldset>
                            <div class="form-group">
                                <label>Customer Number</label>
                                <input type="text" class="form-control" id="customer-no-add" name="customer_no" placeholder="Customer Number" readonly/>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-outline-secondary" data-dismiss="modal" />Cancel</button>
                            <button type="submit" class="btn btn-primary" />Submit</button>
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
                        <button type="button" class="btn btn-outline-secondary" data-dismiss="modal" data-toggle="modal" data-target="#add-location-popup" />Cancel</button>
                        <button type="submit" class="btn btn-primary" id="mark-location" />Mark</button>
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
<script src="/srmsng/public/js/customer_location_field.js"></script>
<script src="/srmsng/public/js/map_places.js"></script>
<script src="/srmsng/public/js/submit.js"></script>
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyC8NC0ToCiqmrDLjvOu9H74ZjeWtgQkU7E&libraries=places&callback=initMap"></script>
<script>
// Initialize Date Range Pickers
$(function() {
    $('input[name="due_date"]').daterangepicker({
        timePicker: true,
        "timePicker24Hour": true,
        singleDatePicker: true,
        startDate: moment().startOf('hour'),
        endDate: moment().startOf('hour').add(32, 'hour'),
        locale: {
            format: 'Y-MM-DD H:mm:ss'
        }
    });
});

function fetchDropdowns(codeParam, valueParam, dropdownID, url) {
    // fetch dropdowns
    $(dropdownID).val(0);
    fetch(url)
    .then(resp => {
        return resp.json();
    })
    .then(data_json => {
        data_json.forEach(element => {
            if (element[codeParam] != 0) {
                var option = document.createElement('option');
                option.setAttribute('value',element[codeParam]);
                option.innerHTML = element[valueParam];
                document.getElementById(dropdownID).appendChild(option);
            }
        })
    })
}

$(document).ready(function() {
    // fetch fse
    fetch('/srmsng/public/index.php/api/admin/getfse')
    .then(resp => {
        return resp.json();
    })
    .then(data_json => {
        data_json.forEach(element => {
            if (element['fse_code'] != 0) {
                var item = document.createElement('span');
                item.setAttribute('class','select-multiple-item');

                var checkbox = document.createElement('input');
                checkbox.setAttribute('type','checkbox');
                checkbox.setAttribute('value',element['fse_code']);
                checkbox.setAttribute('name','fse_code[]');
                checkbox.onclick = function() {
                    var fselabel = document.createElement('span');
                    var radio_leader = document.createElement('input');
                    radio_leader.setAttribute("type", "radio");
                    radio_leader.setAttribute("name", "leader");
                    radio_leader.setAttribute("value", element['fse_code']);
                    radio_leader.required = true;
                    fselabel.appendChild(radio_leader);
                    fselabel.setAttribute('id',element['fse_code']);
                    fselabel.appendChild(document.createTextNode(element['engname']));
                    if (!checkbox.checked) {
                        document.getElementById(element['fse_code']).outerHTML = '';
                    } else {
                        document.getElementById('selected-fse').appendChild(fselabel);
                    }
                }
                item.appendChild(checkbox);
                item.appendChild(document.createTextNode(' ' + element['engname']));
                document.getElementById('fse-dropdown').appendChild(item);
            }
        });
    });
})

function openMap() {
    $('#add-location-popup .close').trigger('click');

    $("#map-modal").modal("toggle");

    var lat = $('#add-location-form input[name="lat"]').val();
    var lng = $('#add-location-form input[name="lon"]').val();
    initMarker(lat,lng);
}

</script>
