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
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
  
  <link rel="stylesheet" href="/srmsng/public/css/style.css">
</head>
<body>
    <?php include_once('../../admin/admin_nav.php'); ?>
    <main class="container narrow">
        <div class="input-group page-title">
            <h1 style="margin-bottom:0;">KPI</h1>
        </div>
        <form id="gen-report-form">
            <fieldset>
                <legend>Time Period</legend>
                <div class="form-group">
                    <label>Month</label>
                    <select name="month" class="form-control" required>
                        <option value="0">All Year</option>
                        <option value="1">January</option>
                        <option value="2">February</option>
                        <option value="3">March</option>
                        <option value="4">April</option>
                        <option value="5">May</option>
                        <option value="6">June</option>
                        <option value="7">July</option>
                        <option value="8">August</option>
                        <option value="9">September</option>
                        <option value="10">October</option>
                        <option value="11">November</option>
                        <option value="12">December</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Year</label>
                    <select list="year" class="form-control" id="since-field" name="year" autocomplete="off">
                        <datalist id="year">
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
                </div>
            </fieldset>
            <h3>Filter</h3>
            <div class="form-group" id="filter">
                <label class="control radio" style="margin-right:10px">
                    <input name="filter" value="by-fse" id="by-fse" type="radio" class="control-input" checked> By FSE
                </label>
                <label class="control radio" style="margin-right:10px">
                    <input name="filter" value="by-customer" id="by-customer" type="radio" class="control-input"> By Customer
                </label>
                <label class="control radio" style="margin-right:10px">
                    <input name="filter" value="by-overview" id="overview" type="radio" class="control-input"> Overview
                </label>
            </div>
            <div id="filter-options">
                <fieldset id="by-fse-fieldset" class="tab-content active">
                    <legend>By FSE</legend>
                    <div class="form-group">
                        <label>Field Service Engineer</label>
                        <select name="engname" id="fse-code-dropdown" class="form-control">
                            <option value="">-- Select Field Service Engineer --</option>
                        </select>
                    </div>
                </fieldset>
                <fieldset id="by-customer-fieldset" class="tab-content">
                    <legend>By Customer</legend>
                    <div class="form-group">
                        <label>Customer Name</label>
                        <div class="input-group">
                            <input type="text" class="form-control" id="customer-search-field" placeholder="Customer Name" name="customer_name" autocomplete=off/>
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
                        <div class="input-group">
                            <input type="text" class="form-control" id="customer-code-search-field" name="customer_no" placeholder="Customer Code"/>
                            <div class="autofill-dropdown border" id="customer-code-dropdown">
                            </div>
                            <div class="input-group-append">
                                <button class="btn btn-primary" type="button" id="customer-code-search"><i class="fa fa-search"></i></span>
                            </div>
                        </div>
                        <small class="form-text text-muted">Enter at least 3 characters and press enter to search.</small>
                    </div>
                </fieldset>
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
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
<script src="../fetch_data.js"></script>
<script src="../../../src/js/pdfmake.min.js"></script>
<script src="../../../src/js/vfs_fonts.js"></script>
<script src="gen_chart.js"></script>
<script src="gen_report.js"></script>
<script>
    $(document).ready(function(){

        // Radio
        $('#filter input[type="radio"]').on('change',function() {
            $('#filter-options fieldset').removeClass('active');
            $('#' + $(this).attr('id') + '-fieldset').addClass('active');
        });

        // Generate Report
        $('#gen-report-form').submit(function() {

            genReportPDF($(this).serializeArray());
            return false
        });

        // Get Data
        initFSE();
        initCustomerSearch();
    })
</script>
