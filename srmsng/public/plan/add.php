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
            <h1 style="margin-bottom:0;">Add Maintenance Plan</h1>
        </div>

        <form id="add-maintenance-plan-form">
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
                                <option>Preventive Maintenance</option>
                                <option>Building Preventive Maintenance - UOB</option>
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
                <div class="col">
                    <fieldset>
                        <legend>SNG Code</legend>
                        <div class="form-group" id="asset_array">
                            <div class="form-group">
                                <input type="text" class="form-control" name="asset[]" placeholder="SNG-Code" onfocusout="get_asset_location_and_customer(this.value);get_asset_warranty(this.value);" required/>
                            </div>
                        </div>
                        <button type="button" class="btn btn-light btn btn-block" id="add_asset"><i class="fa fa-plus"></i> Add more asset</button>
                    </fieldset>
                </div>
                <div class="col">
                    <fieldset>
                        <legend>Asset Location</legend>
                        <div class="form-group">
                            <div class="form-group">
                                <label>Site Name</label>
                                <input type="text" class="form-control" name="sitename" placeholder="Site Name" disabled/>
                            </div>
                            <div class="form-group">
                                <label>Location Code</label>
                                <input type="text" class="form-control" name="location_code" placeholder="Location Code" readonly=''/>
                            </div>
                        </div>
                    </fieldset>
                </div>
                <div class="col">
                    <fieldset>
                        <legend>Asset Owner</legend>
                        <div class="form-group">
                            <div class="form-group">
                                <label>Customer Name</label>
                                <input type="text" class="form-control" name="customer_name" placeholder="Customer Name" disabled/>
                            </div>
                            <div class="form-group">
                                <label>Customer Code</label>
                                <input type="text" class="form-control" name="customer_no" placeholder="Customer Code" disabled/>
                            </div>
                        </div>
                    </fieldset>
                </div>
            </div>
            <div class="row">
                <div class="col">
                    <fieldset id="sale-order-choose">
                        <legend>Sale Order</legend>
                        <div class="form-group">
                            <label>Sale Order</label>
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
                                    <input type="text" class="form-control sub-field" name="since" id="year-field" placeholder="Year" readonly/>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Date Order</label>
                                    <input type="text" class="form-control sub-field" name="date_order" id="date-order-field" placeholder="Date Order" readonly/>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>PO Number</label>
                                    <input type="text" class="form-control sub-field" name="po_number" id="po-number-field" placeholder="PO Number" readonly/>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>PO Date</label>
                                    <input type="text" class="form-control sub-field" name="po_date" id="po-date-field" placeholder="PO Date" readonly/>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>D/O Number</label>
                            <input type="text" class="form-control sub-field" name="do_number" id="do-no-field" placeholder="D/O No." readonly/>
                        </div>
                    </fieldset>
                </div>
            </div>
            <div class="row">
                <!-- /* //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////// */ -->
                <div class="col">
                    <fieldset>
                        <legend>Plan Dates Properties</legend>
                        <div class="form-group">
                            <label>Start Date</label>
                            <input type="text" class="form-control" name="start_date" placeholder="YYYY-MM-DD H:mm:ss" id="start_date" autocomplete="off" required/>
                        </div>
                        <div class="form-group">
                            <label>Year Count</label>
                            <input type="number" min="1" class="form-control" name="year_count" placeholder="Year Count" id="year_count" autocomplete="off" required/>
                        </div>
                        <div class="form-group">
                            <label>Times per Year</label>
                            <input type="number" min="1" class="form-control" name="times_per_year" placeholder="Times per Year" id="times_per_year" autocomplete="off" required/>
                        </div>
                        <button type="button" class="btn btn-light btn btn-block" id="generate_dates"><i class="fa fa-calculator"></i> Generate Dates</button>
                    </fieldset>
                </div>
                <!-- /* //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////// */ -->
                <div class="col">
                    <fieldset>
                        <!-- start_date, end_date, year_count, times_per_year -->
                        <legend>Asset Warranty</legend>
                        <div class="form-group">
                            <div class="form-group">
                                <label>Type of Contract</label>
                                <input type="text" class="form-control" name="warranty_typeofcontract" placeholder="Type of Contract" disabled/>
                            </div>
                            <div class="form-group">
                                <label>Start Date</label>
                                <input type="text" class="form-control" name="warranty_start_date" placeholder="YYYY-MM-DD H:mm:ss" disabled/>
                            </div>
                            <div class="form-group">
                                <label>End Date</label>
                                <input type="text" class="form-control" name="warranty_end_date" placeholder="YYYY-MM-DD H:mm:ss" disabled/>
                            </div>
                            <div class="form-group">
                                <label>Year Count</label>
                                <input type="number" class="form-control" name="warranty_year_count" placeholder="Year Count" disabled/>
                            </div>
                            <div class="form-group">
                                <label>Times per Year</label>
                                <input type="number" class="form-control" name="warranty_times_per_year" placeholder="Times per Year" disabled/>
                            </div>
                        </div>
                    </fieldset>
                </div>
                <!-- /* //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////// */ -->
                <div class="col">
                    <fieldset>
                        <legend>Plan Dates</legend>
                        <div class="form-group" id="plan_date_array">
                            <div class="form-group">
                                <input type="text" class="form-control" name="plan_date[]" placeholder="YYYY-MM-DD H:mm:ss" id="plan_date" autocomplete="off" required/>
                            </div>
                        </div>
                        <button type="button" class="btn btn-light btn btn-block" id="add_date"><i class="fa fa-plus"></i> Add more date</button>
                    </fieldset>
                </div>
                <!-- /* //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////// */ -->
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
    </main>
</body>
<script src="https://code.jquery.com/jquery-3.3.1.min.js" integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.0/js/bootstrap.min.js" integrity="sha384-uefMccjFJAIv6A+rW+L4AHf99KvxDjWSu1z9VI8SKNVmz4sk7buKt/6v9KI65qnm" crossorigin="anonymous"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
<script src="/srmsng/public/js/asset_location_check.js"></script>
<script src="/srmsng/public/js/asset_warranty_check.js"></script>
<script src="/srmsng/public/js/submit.js"></script>
<script>

// Initialize Date Range Pickers
init_daterangepicker('input[name="plan_date[]"],input[name="start_date"]');
function init_daterangepicker(input_string) {
    $(function() {
        $(input_string).daterangepicker({
            timePicker: true,
            "timePicker24Hour": true,
            singleDatePicker: true,
            //startDate: moment().startOf('hour'),
            //endDate: moment().startOf('hour').add(32, 'hour'),
            locale: {
                format: 'Y-MM-DD H:mm:ss'
            }
        });
    });
}

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

$("#add_asset").on("click", function() {
    var asset = document.createElement('input');
    asset.setAttribute("type", "text");
    asset.setAttribute("class", "form-control");
    asset.setAttribute("name", "asset[]");
    asset.setAttribute("placeholder", "SNG-Code");
    asset.setAttribute("onfocusout", "check_asset_warranty(this);check_asset_location_code(this);");
    var asset_form = document.createElement('div');
    asset_form.setAttribute("class", "form-group");
    asset_form.appendChild(asset);
    document.getElementById('asset_array').appendChild(asset_form);
});

$("#generate_dates").on("click", function() {
    start_date = document.getElementById('start_date').value;
    year_count = document.getElementById('year_count').value;
    times_per_year = document.getElementById('times_per_year').value;
    generate_avalable = true;
    if (times_per_year == '') {
        $("#times_per_year").addClass("is-invalid");
        $("#times_per_year").focus();
        generate_avalable = false;
    } else {
        $("#times_per_year").removeClass("is-invalid");
    }
    if (year_count == '') {
        $("#year_count").addClass("is-invalid");
        $("#year_count").focus();
        generate_avalable = false;
    } else {
        $("#year_count").removeClass("is-invalid");
    }
    if (start_date == '') {
        $("#start_date").addClass("is-invalid");
        $("#start_date").focus();
        generate_avalable = false;
    } else {
        $("#start_date").removeClass("is-invalid");
    }
    if (generate_avalable) {
        dates = generate_plan_dates(start_date, year_count, times_per_year);
    }
});

function generate_plan_dates(start_date, year_count, times_per_year) {
    reset_plan_dates();
    document.getElementById('plan_date').value = start_date;    //First plan date = Start date
    day_coverage = 365 * year_count;
    period = 365 / times_per_year;
    count = year_count * times_per_year;
    start_date = new Date(start_date);
    for (i = 1; i < count; i++) {
        date = new Date(start_date);
        date.setDate(date.getDate() + (period * i));
        date.setHours(start_date.getHours());
        date.setMinutes(start_date.getMinutes());
        date.setSeconds(start_date.getSeconds());
        var plan_date = document.createElement('input');
        plan_date.setAttribute("type", "text");
        plan_date.setAttribute("class", "form-control");
        plan_date.setAttribute("name", "plan_date[]");
        plan_date.setAttribute("placeholder", "YYYY-MM-DD H:mm:ss");
        plan_date.setAttribute("id", "plan_date");
        plan_date.setAttribute("autocomplete", "off");
        plan_date.value = date.toModString();
        var plan_date_form = document.createElement('div');
        plan_date_form.setAttribute("class", "form-group");
        plan_date_form.appendChild(plan_date);
        document.getElementById('plan_date_array').appendChild(plan_date_form);
    }
    init_daterangepicker('input[name="plan_date[]"]');
}

function reset_plan_dates() {
    plan_date_array = document.getElementById('plan_date_array');
    plan_date_array.innerHTML = '<div class="form-group"><input type="text" class="form-control" name="plan_date[]" placeholder="YYYY-MM-DD H:mm:ss" id="plan_date" autocomplete="off" required/></div>';
    init_daterangepicker('input[name="plan_date[]"]');
}

Date.prototype.toModString = function() {
    pad = function(num) {
        var norm = Math.floor(Math.abs(num));
        return (norm < 10 ? '0' : '') + norm;
    };
    return this.getFullYear() +
        '-' + pad(this.getMonth() + 1) +
        '-' + pad(this.getDate()) +
        ' ' + pad(this.getHours()) +
        ':' + pad(this.getMinutes()) +
        ':' + pad(this.getSeconds());
}

$("#add_date").on("click", function() {
    var plan_date = document.createElement('input');
    plan_date.setAttribute("type", "text");
    plan_date.setAttribute("class", "form-control");
    plan_date.setAttribute("name", "plan_date[]");
    plan_date.setAttribute("placeholder", "YYYY-MM-DD H:mm:ss");
    plan_date.setAttribute("id", "plan_date");
    plan_date.setAttribute("autocomplete", "off");
    var plan_date_form = document.createElement('div');
    plan_date_form.setAttribute("class", "form-group");
    plan_date_form.appendChild(plan_date);
    document.getElementById('plan_date_array').appendChild(plan_date_form);
    init_daterangepicker('input[name="plan_date[]"]');
});

$(window).keydown(function(event) {
  if (event.keyCode == 13) {
    event.preventDefault();
    return false;
  }
});

/* ================================ Sale Order ================================ */
// Search Sale Order
$("#sale-order-search-field").on("keydown", function() {
  // Press enter to search
  if (event.keyCode == 13) {
    $("#sale-order-search").trigger("click");
  }
  // Clear data when the user types
  $("#sale-order-choose .sub-field").val("");
  document.getElementById("sale-order-dropdown").innerHTML = "";
  // disable edit button
  $("#sale-order-edit-button").addClass("disabled");
});

$("#sale-order-search").on("click", function() {
  if ($("#sale-order-search-field").val().length >= 3) {
    // Add data to dropdown and display
    fetch("/srmsng/public/index.php/api/admin/getsaleorder?sale_order_no=" + $("#sale-order-search-field").val())
      .then(resp => {
        return resp.json();
      })
      .then(data_json => {
        // clear dropdown
        document.getElementById("sale-order-dropdown").innerHTML =
          '<span class="autofill-item-default">No Data Found</span>';

        data_json.forEach(element => {
          var item = document.createElement("span");
          item.setAttribute("class", "autofill-item");
          item.innerHTML = element["sale_order_no"];
          item.onclick = function() {
            $("#sale-order-search-field").val(element["sale_order_no"]);
            $("#sale-order-dropdown")
              .attr("tabindex", -1)
              .focusout();
            for (var value in data_json[0]) {
              $("#sale-order-choose .sub-field[name='" + value + "']").val(data_json[0][value]);
            }
            // enable edit button
            $("#sale-order-edit-button").removeClass("disabled");
          };
          document.getElementById("sale-order-dropdown").appendChild(item);
        });
      });

    $("#sale-order-dropdown").addClass("show");
    $("#itesale-orderm-dropdown")
      .attr("tabindex", -1)
      .focus();
  }
});
$("#sale-order-dropdown").on("focusout", function() {
  $(this).removeClass("show");
});

</script>
