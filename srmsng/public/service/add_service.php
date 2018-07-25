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
      <h1 style="margin-bottom:0;">Add Service</h1>
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
                <option>Preventive Building Maintenance - UOB</option>
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
            <fieldset>
              <legend>SNG Code</legend>
              <div class="form-group" id="asset_array">
                <div class="form-group">
                  <input type="text" class="form-control" name="asset[]" placeholder="SNG-Code" onfocusout="get_asset_location_and_customer(this.value);" required/>
                </div>
              </div>
              <button type="button" class="btn btn-light btn btn-block" id="add_asset"><i class="fa fa-plus"></i> Add more asset</button>
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

    // Get SNG Code in case the user adds service from the work page
    var url_string = window.location.href;
    var url = new URL(url_string);
    var sng_code = url.searchParams.get("sng_code");

    if (sng_code) {
      $('input[name="asset[]"]').val(sng_code);
      $('input[name="asset[]"]').prop('readonly',true);
      get_asset_location_and_customer(sng_code);

      $('#add-service-form').attr('data-type','work');
    }

  })

$("#add_asset").on("click", function() {
    var asset = document.createElement('input');
    asset.setAttribute("type", "text");
    asset.setAttribute("class", "form-control");
    asset.setAttribute("name", "asset[]");
    asset.setAttribute("placeholder", "SNG-Code");
    asset.setAttribute("onfocusout", "check_asset_location_code(this);");
    var asset_form = document.createElement('div');
    asset_form.setAttribute("class", "form-group");
    asset_form.appendChild(asset);
    document.getElementById('asset_array').appendChild(asset_form);
});

</script>
