<?php 
    require($_SERVER['DOCUMENT_ROOT'].'/srmsng/public/cookie_validate_fse.php');
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
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" />
    
    <link rel="stylesheet" href="/srmsng/public/css/style.css">
</head>
<body>
    <?php include_once('./fse_nav.php'); ?>
    <main class="container">
        <div class="tabs">
            <div class="tab-item active" id="cm-tab">
                Corrective Maintenance (CM)
            </div>
            <div class="tab-item" id="pm-tab">
                Preventive Maintenance (PM)
            </div>
        </div>
        <div class="tab-content active" id="cm-table">
            <div class="input-group page-title">
                <h1 style="margin-bottom:0;">Corrective Maintenance</h1>
            </div>
            <table id="supertable-cm">
                <thead>
                    <th>Action</th>
                    <th>CM ID</th>
                    <th>SNG-CODE</th>
                    <th>Item Name</th>
                    <th>Rate</th>
                    <th>Battery Spec</th>
                    <th>Number of Batteries</th>
                    <th>Site</th>
                    <th>Customer Name</th>
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
                    <th>Job Status</th>
                    <th>CM Time</th>
                    <th>Request Time</th>
                    <th>Start Time</th>
                    <th>Close Time</th>
                    <th>Notes</th>
                </thead>
                <tbody id="maintable-cm">
            
                </tbody>
            </table>
        </div>
        <div class="tab-content" id="pm-table">
            <div class="input-group page-title">
                <h1 style="margin-bottom:0;">Preventive Maintenance</h1>
            </div>
            <table id="supertable-pm">
                <thead>
                    <th>Action</th>
                    <th>Service ID</th>
                    <th>Title</th>
                    <th>Status</th>
                    <th>Contact</th>
                    <th>FSE</th>
                    <th>Work Class</th>
                    <th>Location</th>
                    <th>Due Date</th>
                </thead>
                <tbody id="maintable-pm">
            
                </tbody>
            </table>
        </div>
        <div class="modal" tabindex="-1" role="dialog" id="note-modal">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Incomplete Work Submit</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </div>
                    <form id="incomplete-submit">
                        <div class="modal-body">
                            <div class="form-group">
                                <label>CM ID:</label> 
                                <input class="form-control" value="" name="cm_id" readonly/>
                            </div>
                            <div class="form-group">
                                <label>Notes</label>
                                <textarea type="text" class="form-control" name="notes" placeholder="Notes" maxlength="300"></textarea>
                                <small class="form-text text-muted">Maximum: 300 Characters</small> 
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-primary">Confirm</button>
                        </div>
                    </form>
                </div>
            </div>
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
    <script type="text/javascript" src="/srmsng/bower_components/remarkable-bootstrap-notify/bootstrap-notify.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <!-- /srmsng/bower_components/remarkable-bootstrap-notify -->
    <script src="/srmsng/public/js/table_setup.js"></script>
    <script src="/srmsng/public/js/fetch_ajax.js"></script>
    <script src="/srmsng/public/js/fetch_ticket_fse.js"></script>
    <script src="/srmsng/public/js/fetch_pm_fse.js"></script>
    <script src="/srmsng/public/js/submit.js"></script>
    <script src="/srmsng/public/js/onclose.js"></script>

    <script>
        console.log("READY");
        // console.log(String()+"<= This is String");

        //     console.log("NULL");
        // }else{
        //     console.log("NOT NULL");
        // }
        function setModal(cm_id) {
            $('#incomplete-submit input[name="cm_id"]').val(cm_id);
        }
        $(document).ready( function () {
            var url_string = window.location.href;
            var url = new URL(url_string);
            var add_success = url.searchParams.get("add_success");
            if (add_success == 'true') {
                $('#add-success').css('display','block');
            }
            var update_success = url.searchParams.get("update_success")
            if (update_success == 'true') {
                $('#update-success').css('display','block');
            }
           
            console.log(<?php echo json_encode($_SESSION)?>);
            sessionStorage.setItem("username_unhash", "<?php echo $_SESSION["username_unhash"] ?>");
            
            // Incomplete Work Submit
            $('#incomplete-submit').submit(function() {
                var form_data = $(this).serialize();
                $.ajax({
                    type: "PUT",
                    url: "/srmsng/public/index.php/api/fse/notfinishwork",
                    data: form_data,
                    success: data => {
                        console.log(data);
                        toastr.options = {
                            positionClass: "toast-bottom-center"
                        };
                        toastr.success("<span>Please wait, this website is going to refresh...</span>");
                        setTimeout(() => {
                            window.location.reload();
                        }, 2000);
                        // window.location.reload();
                    },
                    error: data => {
                        console.log(data);
                    }
                });
                return false
            });
            
            // Tabs
            var setUpSecondTab = false
            $('#cm-tab').on('click',function() {
                $('#pm-table').removeClass('active');
                $('#pm-tab').removeClass('active');
                $('#cm-table').addClass('active');
                $('#cm-tab').addClass('active');
            });

            $('#pm-tab').on('click',function() {
                $('#cm-table').removeClass('active');
                $('#cm-tab').removeClass('active');
                $('#pm-table').addClass('active');
                $('#pm-tab').addClass('active');
                if (setUpSecondTab === false) {
                    setUpFixed("supertable-pm");
                }
                setUpSecondTab = true;
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