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
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
  <link rel="stylesheet" href="/srmsng/public/css/style.css">
</head>
<body>
    <?php include_once('../../admin/admin_nav.php'); ?>
    <main class="container narrow">
        <div class="input-group page-title">
            <h1 style="margin-bottom:0;">Workload</h1>
        </div>
        <form id="gen-report-form">
            <div class="form-group">
                <label>Field Service Engineer</label>
                <select name="engname" id="fse-code-dropdown" class="form-control" required>
                    <option value="">-- Select Field Service Engineer --</option>
                </select>
            </div>
            <div class="form-group">
                <label>Month</label>
                <select name="month" class="form-control" required>
                    <option value="">-- Select Month --</option>
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
            <div class="form-group">
                <label>Format</label>
                <select name="format" class="form-control" required>
                    <option value="">-- Select File Format --</option>
                    <option value="pdf">PDF</option>
                    <option value="csv">CSV</option>
                </select>
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
<script src="gen_report.js"></script>
<script>
    // Initialize Date Range Pickers
    $(function() {
        $('input[name="start_time"],input[name="stop_time"]').daterangepicker({
        singleDatePicker: true,
        startDate: moment().startOf('day'),
        endDate: moment().startOf('day').add(1, 'day'),
        locale: {
            format: 'Y-MM-DD'
        }
        });
    });

    
    $(document).ready(function(){
        // Generate Report
        $('#gen-report-form').submit(function() {
            if ($('select[name="format"]').val() === 'pdf') {
                genReportPDF($(this).serializeArray())
            } else {
                genReportCSV($(this).serializeArray())
            }
            return false
        });

        // Get Data
        initFSE();

        // Get Current Month
        getCurrentMonth();
    })
</script>
