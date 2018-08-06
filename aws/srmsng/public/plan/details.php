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
    <?php include_once($_SERVER['DOCUMENT_ROOT'].'/srmsng/public/admin/admin_nav.php'); ?>
    <main class="container" data-my-var="<?php echo $_GET['id']?>" id="mainn">
        <div class="input-group page-title">
            <h1 style="margin-bottom:0;">Maintenance Plan Deatils</h1>
            <a class="btn btn-outline-secondary" href="/srmsng/public/plan">
                <i class="fa fa-angle-left"></i> Back
            </a>
        </div>
        <div class="view" style="padding-bottom: 20px; display: block">
            <div class="row">
                <div class="col">
                    <h5>Maintenance Plan ID: <span id="maintenance_plan_id" class="detail"></span></h5>
                    <h5>Title: <span id="title" class="detail"></span></h5>
                </div>
                <div class="col">
                    <h5>Start Date: <span id="start_date" class="detail"></span></h5>
                    <h5>Year Count: <span id="year_count" class="detail"></span></h5>
                    <h5>Times Per Year: <span id="times_per_year" class="detail"></span></h5>
                </div>
            </div>
        </div>
        <table id="supertable">
            <thead>
                <th>Service ID</th>
                <th>Title</th>
                <th>Status</th>
                <th>Contact</th>
                <th>FSE</th>
                <th>Work Class</th>
                <th>Location</th>
                <th>Due Date</th>
                <th>Action</th>
            </thead>
            <tbody id="maintable">

            </tbody>
        </table>
    </main>
</body>
<script src="https://code.jquery.com/jquery-3.3.1.min.js" integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.0/js/bootstrap.min.js" integrity="sha384-uefMccjFJAIv6A+rW+L4AHf99KvxDjWSu1z9VI8SKNVmz4sk7buKt/6v9KI65qnm" crossorigin="anonymous"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/v/dt/dt-1.10.16/fc-3.2.4/r-2.2.1/datatables.min.js"></script>
<script src="/srmsng/public/js/table_setup.js"></script>
<script src="/srmsng/public/js/fetch_ajax.js"></script>
<script src="/srmsng/public/js/fetch_plan_services.js"></script>
<script src="/srmsng/public/js/submit.js"></script>
<script>

$(document).ready( function () {
    var url_string = window.location.href;
    var url = new URL(url_string);
    var maintenance_plan_id = url.searchParams.get("maintenance_plan_id");

    fetch("/srmsng/public/index.php/api/admin/plan/single?maintenance_plan_id=" + maintenance_plan_id)
    .then(res => {
        return res.json();
    })
    .then(data => {
        console.log(data);
        for (var value in data[0]) {
            document.getElementById(value).innerHTML = data[0][value];
        }
    })
});

window.addEventListener("beforeunload", function (e) {
    let remember = "<?php echo $_SESSION['remember']?>";
    if(remember == "off"){
        console.log("UNLOAD");
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
