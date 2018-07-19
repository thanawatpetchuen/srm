<?php 
    require($_SERVER['DOCUMENT_ROOT'].'/srmsng/public/cookie_validate_admin.php');
    // require('../cookie_validate_admin.php');
    // echo json_encode($_SESSION);

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
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/dt/dt-1.10.16/fc-3.2.4/r-2.2.1/datatables.min.css"/>
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
    <link rel="stylesheet" href="/srmsng/public/css/style.css">
</head>
<body>
    <?php include_once('../admin/admin_nav.php'); ?>
    <main class="container">
        <div class="alert alert-success" id="add-success">
            Ticket added
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="alert alert-success" id="update-success">
            Ticket updated
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        
        

        <div class="tabs">
            <div class="tab-item active" id="ticket-view-tab">
                Tickets
            </div>
            <div class="tab-item" id="approve-view-tab">
                Request Approve
            </div>
        </div>

        <div class="tab-content active" id="ticket-view">
            <div class="input-group page-title">
                <h1 style="margin-bottom:0;">Tickets</h1>
                <a href="add_ticket" class="btn btn-primary">
                    <i class="fa fa-plus"></i> Add Ticket
                </a>
            </div>

            <table id="supertable">
                <thead>
                    <th>CM ID</th>
                    <th>SNG-CODE</th>
                    <th>Item Name</th>
                    <th>Rate</th>
                    <th>Battery Spec</th>
                    <th>Number of Batteries</th>
                    <th>Site</th>
                    <th>Name</th>
                    <th>Phone Number</th>
                    <th>Email</th>
                    <th>Asset Problem</th>
                    <th>Asset Detected</th>
                    <th>Corrections</th>
                    <th>Cause</th>
                    <th>Solution</th>
                    <th>Suggestions</th>
                    <th>UPS Status</th>
                    <th>FSE</th>
                    <th>Job Type</th>
                    <th>Job Status</th>
                    <th>CM Time</th>
                    <th>Request Time</th>
                    <th>Start Time</th>
                    <th>Completion Time</th>
                    <th>Action</th>
                </thead>
                <tbody id="maintable">
            
                </tbody>
            </table>
        </div>

        <div class="tab-content" id="approve-view">
            <div class="input-group page-title">
                <h1 style="margin-bottom:0;">Request Approve</h1>
               
            </div>

            <table id="supertable-approve">
                <thead>
                    <th>CM ID</th>
                    <th>SNG-CODE</th>
                    <th>Item Name</th>
                    <th>Rate</th>
                    <th>Battery Spec</th>
                    <th>Number of Batteries</th>
                    <th>Site</th>
                    <th>Name</th>
                    <th>Phone Number</th>
                    <th>Email</th>
                    <th>Asset Problem</th>
                    <th>Asset Detected</th>
                    <th>Corrections</th>
                    <th>Cause</th>
                    <th>Solution</th>
                    <th>Suggestions</th>
                    <th>UPS Status</th>
                    <th>FSE</th>
                    <th>Job Type</th>
                    <th>Job Status</th>
                    <th>CM Time</th>
                    <th>Request Time</th>
                    <th>Start Time</th>
                    <th>Close Time</th>
                    <th>Action</th>
                </thead>
                <tbody id="maintable-approve">
            
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
	<!-- <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.js"></script> -->
    <script type="text/javascript" src="https://cdn.datatables.net/v/dt/dt-1.10.16/fc-3.2.4/r-2.2.1/datatables.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <script src="/srmsng/public/js/table_setup.js"></script>
    <script src="/srmsng/public/js/fetch_ajax.js"></script>
    <script src="/srmsng/public/js/fetch_ticket.js"></script>
    <script src="/srmsng/public/js/submit.js"></script>
    <script src="/srmsng/public/js/onclose.js"></script>
    <script>
        console.log("READY");
        // console.log(String()+"<= This is String");

        //     console.log("NULL");
        // }else{
        //     console.log("NOT NULL");
        // }
        $(document).ready( function () {
            var url_string = window.location.href;
            var url = new URL(url_string);
            var add_success = url.searchParams.get("add_success");
            var approve = url.searchParams.get("approve");
            if(approve == 'true'){
                $('#ticket-view').removeClass('active');
                $('#ticket-view-tab').removeClass('active');
                $('#approve-view').addClass('active');
                $('#approve-view-tab').addClass('active');
            }
            if (add_success == 'true') {
                $('#add-success').css('display','block');
            }
            var update_success = url.searchParams.get("update_success")
            if (update_success == 'true') {
                $('#update-success').css('display','block');
            }
           
            console.log(<?php echo json_encode($_SESSION)?>);

            $('#ticket-view-tab').on('click',function() {
                $('#ticket-view').addClass('active');
                $('#ticket-view-tab').addClass('active');
                $('#approve-view').removeClass('active');
                $('#approve-view-tab').removeClass('active');
            });

            $('#approve-view-tab').on('click',function() {
                $('#ticket-view').removeClass('active');
                $('#ticket-view-tab').removeClass('active');
                $('#approve-view').addClass('active');
                $('#approve-view-tab').addClass('active');
            });

        });
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
    </script>
</html>