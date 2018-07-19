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
    <main class="container narrow">
        <div class="input-group page-title">
            <h1 style="margin-bottom:0;">Service Report</h1>
        </div>
        <form id="gen-report-form">
            <div class="form-group">
                <label>Field Service Engineer</label>
                <select name="engname" id="fse-code-dropdown" class="form-control" required>
                    <option value="">-- Select Field Service Engineer --</option>
                </select>
            </div>
            <div class="row">
                <div class="col">
                    <div class="form-group">
                        <label>Start Date</label>
                        <input type="text" class="form-control" name="start_date" placeholder="Start Date"/>
                    </div>
                </div>
                <div class="col">
                    <div class="form-group">
                        <label>End Date</label>
                        <input type="text" class="form-control" name="end_date" placeholder="End Date"/>
                    </div>
                </div>
            </div>
            <div class="form-group" style="margin-top:20px; text-align:right">
                <button type="submit" class="btn btn-primary">Generate Report</button>
            </div> 
        </form>
    </main>
</body>
<script src="https://code.jquery.com/jquery-3.3.1.min.js" integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.0/js/bootstrap.min.js" integrity="sha384-uefMccjFJAIv6A+rW+L4AHf99KvxDjWSu1z9VI8SKNVmz4sk7buKt/6v9KI65qnm" crossorigin="anonymous"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
<script src="../../src/js/pdfmake.min.js"></script>
<script src="../../src/js/vfs_fonts.js"></script>
<script src="gen_report.js"></script>
<script>
    // Initialize Date Range Pickers
    $(function() {
        $('input[name="start_date"],input[name="end_date"]').daterangepicker({
        singleDatePicker: true,
        startDate: moment().startOf('day'),
        endDate: moment().startOf('day').add(1, 'day'),
        locale: {
            format: 'Y-MM-DD'
        }
        });
    });

    
    $(document).ready(function(){
        // FSE
        fetch('/srmsng/public/index.php/api/admin/getfse')
            .then(resp => {
                return resp.json();
            })
            .then(data_json => {
            data_json.forEach(element => {
                if (element['fse_code'] != 0) {
                    var option = document.createElement('option');
                    // option.setAttribute('value',element['fse_code']);
                    option.innerHTML = element['engname'];
                    document.getElementById('fse-code-dropdown').appendChild(option);
                }
            })
        });
        
        // Generate Report
        $('#gen-report-form').submit(function() {
            genReport($(this).serializeArray())
            return false
        });
    })
</script>
