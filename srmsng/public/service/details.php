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
            <h1 style="margin-bottom:0;">Service Details</h1>
        </div>

        <form id="details-service-form">
            <div class="row">
                <div class="col">
                    <div class="form-group">
                        <label><h4>Service ID</h4></label>
                        <input type="text" class="form-control" name="service_request_id" placeholder="Service ID" readonly="">
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col">
                    <fieldset>
                        <legend>Service Details</legend>
                        <div class="form-group">
                            <label>Title</label>
                            <input type="text" class="form-control" name="title" placeholder="Service Title" maxlength="127" disabled required/>
                        </div>
                        <div class="form-group">
                            <label>Description</label>
                            <textarea type="text" class="form-control" name="description" placeholder="Service Description" maxlength="255" disabled></textarea>
                            <small class="form-text text-muted">Maximum: 255 Characters</small>
                        </div>
                        <div class="form-group">
                            <label>Work Class</label>
                            <input type="text" class="form-control" name="work_class" maxlength="127" disabled required/>
                        </div>
                    </fieldset>
                </div>

                <div class="col">
                    <fieldset>
                        <legend>Contact Information</legend>
                        <div class="form-group">
                            <label>Contact Name</label>
                            <input type="text" class="form-control" name="contact_name" placeholder="Customer Name" disabled/>
                        </div>
                        <div class="form-group">
                            <label>Phone Number</label>
                            <input type="text" class="form-control" name="contact_number" placeholder="Phone Number" maxlength="10" size="10" id="contact-number-field" disabled/>
                            <small class="form-text text-muted" id="contact-number-warning">Mobile phone number only (10 digits)</small>
                        </div>
                        <div class="form-group">
                            <label>Alternate Number</label>
                            <input type="text" class="form-control" name="alternate_number" placeholder="Phone Number" maxlength="10" size="10" id="alternate-number-field" disabled/>
                            <small class="form-text text-muted" id="alternate-number-warning">Mobile phone number only (10 digits)</small>
                        </div>
                    </fieldset>
                </div>
            </div>

            <div class="row">
                <?php /* //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////// */ ?>
                <div class="col">
                    <fieldset>
                        <legend>SNG Code</legend>
                        <div class="form-group" id="asset_array">
                            <div class="form-group">
                                <input type="text" class="form-control" name="asset[]" placeholder="SNG-Code" onfocusout="get_asset_location_and_customer(this.value);" disabled required/>
                            </div>
                        </div>
                    </fieldset>
                </div>
                <?php /* //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////// */ ?>
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
                                <input type="text" class="form-control" name="location_code" placeholder="Location Code" disabled/>
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
                <?php /* //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////// */ ?>
            </div>

            <fieldset>
                <legend>Assign Service</legend>
                <div class="form-group" id="fse-fieldset">
                    <div class="select-multiple" id="fse-dropdown" hidden></div>
                    <label>Field Service Engineer Leader:</label>
                    <div id="selected-fse">
                        <span class="selected-fse-none">None</span>
                    </div>
                </div>
                <div class="form-group">
                    <label>Due Date</label>
                    <input type="text" class="form-control" name="due_date" placeholder="YYYY-MM-DD" id="due_date" autocomplete="off" disabled required/>
                </div>
                <div class="form-group">
                    <label>Job Status</label>
                    <select name="job_status" class="form-control" disabled required>
                        <option value="">-- Select Job Status --</option>
                        <option value="Closed">Closed</option>
                        <option value="Pending">Pending</option>
                        <option value="Work in Progress">Work in Progress</option>
                        <option value="Acknowledged">Acknowledged</option>
                        <option value="Assigned">Assigned</option>
                        <option value="Fixed by Phone">Fixed by Phone</option>
                        <option value="Canceled">Canceled</option>
                    </select>
                </div>
            </fieldset>

            <div class="form-group" style="margin-top:40px; display: flex; align-items: center; justify-content: flex-end; text-align:right ">
                <i class="fas fa-spinner fa-spin text-center" style="font-size:22px; display: none" id="loader"></i>
                <a class="btn btn-outline-secondary" href="/srmsng/public/service"  style="margin-left: 10px">
                    Cancel
                </a>
                <button type="button" onclick="location.href='/srmsng/public/service/update?service_request_id=' + document.getElementsByName('service_request_id')[0].value" class="btn btn-primary" style="margin-left: 5px">Update Service</button>
            </div>
        </form>
    </main>
</body>
<script src="https://code.jquery.com/jquery-3.3.1.min.js" integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.0/js/bootstrap.min.js" integrity="sha384-uefMccjFJAIv6A+rW+L4AHf99KvxDjWSu1z9VI8SKNVmz4sk7buKt/6v9KI65qnm" crossorigin="anonymous"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
<script src="/srmsng/public/js/asset_location_check.js"></script>
<script src="/srmsng/public/js/submit.js"></script>
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
                    //fselabel.setAttribute('style','background-color:#E9ECEF');
                    fselabel.setAttribute('style','background-color:#F8F9FA');
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
    })
    .then( function() {
        // fetch data from service_request_id
        var url_string = window.location.href;
        var url = new URL(url_string);
        var service_request_id = url.searchParams.get("service_request_id")

        fetch("/srmsng/public/index.php/api/admin/service/single?service_request_id=" + service_request_id)
        .then(res => {
            return res.json();
        })
        .then(data => {
            for (var value in data[0]) {
                $(".form-control[name='" + value + "']").val(data[0][value]);
            }
            if (data[0]['GROUP_CONCAT(fse_code)']) {
                // enable assign fse if fse already exists for this ticket
                $('input[name="assign-fse"]').prop('checked', true);
                $('#fse-fieldset').removeClass('disabled');
                var fseArray = data[0]['GROUP_CONCAT(fse_code)'].split(',');
                var leaderArray = data[0]['GROUP_CONCAT(is_leader)'].split(',');
                for (var i in fseArray) {
                    $('#fse-dropdown input[value="' + fseArray[i] + '"]').trigger('click');
                    if (leaderArray[i] == 1) {
                        $('#' + fseArray[i] + ' input[value="' + fseArray[i] + '"]').trigger('click');
                    }
                    document.getElementsByName('leader')[i].disabled = true;
                }
            }
            if (data[1]['GROUP_CONCAT(sng_code)']) {
                var sngArray = data[1]['GROUP_CONCAT(sng_code)'].split(',');
                document.getElementsByName('asset[]')[0].value = sngArray[0];
                get_asset_location_and_customer(sngArray[0]);
                for (var i = 1; i < sngArray.length; i++) {
                    var asset = document.createElement('input');
                    asset.setAttribute("type", "text");
                    asset.setAttribute("class", "form-control");
                    asset.setAttribute("name", "asset[]");
                    asset.setAttribute("placeholder", "SNG-Code");
                    asset.setAttribute("value", sngArray[i]);
                    asset.setAttribute("disabled", true);
                    var asset_form = document.createElement('div');
                    asset_form.setAttribute("class", "form-group");
                    asset_form.appendChild(asset);
                    document.getElementById('asset_array').appendChild(asset_form);
                }
            }
        })
    });
})

</script>
