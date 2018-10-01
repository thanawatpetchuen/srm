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
    <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css" rel="stylesheet">
    <link href='https://fonts.googleapis.com/css?family=Roboto:400,700,300' rel='stylesheet' type='text/css'>
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.16/css/jquery.dataTables.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
    <link rel="stylesheet" href="/srmsng/public/css/style.css">
</head>
<body>
    <?php include_once('../admin/admin_nav.php'); ?>
    <main class="container" data-sng-code="<?php echo $_GET['sng_code']?>" id="main-container">

        <div class="input-group page-title">
            <h1 style="margin-bottom:0;" id="sng-title"></h1>
            <a href="" id="view-asset" target="_blank" class="btn btn-primary" style="margin-right:10px;">
                <i class="fa fa-search"></i> View Details
            </a>
        </div>

        <div class="tabs">
            <div class="tab-item active" id="scheduled-work-tab">
                Scheduled Work
            </div>
            <div class="tab-item" id="history-tab">
                History
            </div>
        </div>

        <div class="alert alert-success" role="alert" id="edit-success-alert">
            <span class="alert-content">Edited Successfully</span>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="alert alert-success" role="alert" id="add-success-alert">
            <span class="alert-content">Added Successfully</span>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>

        <div class="tab-content active" id="scheduled-work">
            <div class="input-group page-title">
                <h3 style="margin-bottom:0;">Scheduled Work</h3>
                <a href="../service/add_service" id="pm_add" target="_blank" class="btn btn-primary" style="margin-right:10px;">
                    <i class="fa fa-plus"></i> Add PM Work
                </a>
                <a href="" id="cm_add" class="btn btn-primary">
                    <i class="fa fa-plus"></i> Add CM Work (Ticket)
                </a>
            </div>
            <table id="supertable-plan">
                <thead>
                    <th>No.</th>
                    <th>Scheduled Date</th>
                    <th>Site Name</th>
                    <th>Description</th>
                    <th>Work Class</th>
                    <th>Job Type</th>
                    <th>Assgined to</th>
                    <th>Status</th>
                    <th>Action</th>
                </thead>
                <tbody id="maintable-plan">
            
                </tbody>
            </table>
        </div>

        <div class="tab-content" id="history">
            <div class="input-group page-title">
                <h3 style="margin-bottom:0;">Work History</h3>
            </div>
            <table id="supertable-history">
                <thead>
                    <th>Job ID</th>
                    <th>Creation Date</th>
                    <th>Task</th>
                    <th>Work Class</th>
                    <th>Job Type</th>
                    <th>Service Type</th>
                    <th>Assigned To</th>
                    <th>Service Time</th>
                    <th>Status</th>
                </thead>
                <tbody id="maintable-history">
            
                </tbody>
            </table>
        </div>
    </main>
</body>
    <script src="https://code.jquery.com/jquery-3.3.1.min.js" integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.0/umd/popper.min.js" integrity="sha384-cs/chFZiN24E4KMATLdqdvsezGxaGsi4hLGOzlXwp5UZB1LY//20VyM2taTB4QvJ" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.0/js/bootstrap.min.js" integrity="sha384-uefMccjFJAIv6A+rW+L4AHf99KvxDjWSu1z9VI8SKNVmz4sk7buKt/6v9KI65qnm" crossorigin="anonymous"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.js"></script>
    <script src="/srmsng/public/js/table_setup.js"></script>
    <script src="/srmsng/public/js/fetch_ajax.js"></script>
    <script src="/srmsng/public/js/fetch_plan.js"></script>
    <script src="/srmsng/public/js/submit.js"></script>

    <script>
        $(document).ready( function () {

            var url_string = window.location.href;
            var url = new URL(url_string);
            var addsuccess = url.searchParams.get("add_success");
            var editsuccess = url.searchParams.get("edit_success");

            if (addsuccess == 'true') {
                $('#add-success-alert').css('display','block');
            }
            if (editsuccess == 'true') {
                $('#edit-success-alert').css('display','block');
            }

            var sng_code = url.searchParams.get("sng_code");
            $("#cm_add").attr('href','../ticket/add_ticket?sng_code=' + sng_code)
            $('#view-asset').attr('href','view_asset?sng_code=' + sng_code);
            $('#sng-title').text('SNG Code: ' + sng_code);
            $("#pm_add").attr('href','../service/add_service?sng_code=' + sng_code)

            $('#scheduled-work-tab').on('click',function() {
                $('#scheduled-work').addClass('active');
                $('#scheduled-work-tab').addClass('active');
                $('#history').removeClass('active');
                $('#history-tab').removeClass('active');
            });

            $('#history-tab').on('click',function() {
                $('#scheduled-work').removeClass('active');
                $('#scheduled-work-tab').removeClass('active');
                $('#history').addClass('active');
                $('#history-tab').addClass('active');
            });
        });
        window.addEventListener("beforeunload", function (e) {       
            
            let remember = "<?php echo $_SESSION['remember']?>";
    
            if(remember == "off"){
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
    
</html>