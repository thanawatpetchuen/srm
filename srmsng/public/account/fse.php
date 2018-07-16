<?php 
    require($_SERVER['DOCUMENT_ROOT'].'/srmsng/public/cookie_validate_admin.php');
    // echo json_encode($_SESSION);

?>
<html>
<head>
<meta http-equiv="Content-type" content="text/html"; charset="utf-8"/>
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
    <?php include_once($_SERVER['DOCUMENT_ROOT'].'/srmsng/public/admin/admin_nav.php'); ?>
    <main class="container">
        <div class="alert alert-success" id="add-success">
            FSE added
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="alert alert-success" id="update-success">
            FSE updated
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="input-group page-title">
            <h1 style="margin-bottom:0;">Field Service Engineer</h1>
            <a href="#" class="btn btn-primary" data-target="#add-fse-popup" data-toggle="modal">
                <i class="fa fa-plus"></i> Add Field Service Engineer
            </a>
        </div>
        <table id="supertable">
            <thead>
                <!-- <th>No.</th> -->
                <th>FSE Code</th>
                <th>Thai Name</th>
                <th>English Name</th>
                <th>Abbreviation</th>
                <th>Company</th>
            	<th>Position</th>
                <th>Service Center</th>
                <th>Section</th>
            	<th>Team</th>
                <th>Status</th>
                <th>Email</th>
                <th>Phone</th>
                <th>Action</th>
            </thead>
            <tbody id="maintable">
         
            </tbody>
        </table>

         <div class="modal" tabindex="-1" role="dialog" id="edit-fse-popup">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Edit FSE</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </div>
                        <form method="post" id="edit-fse-form">
                            <div class="modal-body">
                                <div class="form-group">
                                    <label for="fse_code">
                                        FSE Code
                                    </label>
                                    <input type="text" name="fse_code" class="form-control" placeholder="Enter FSE Code" id="fse_code" readonly>
                                </div>
                                <div class="form-group">
                                    <label for="thainame">
                                        Thai Name
                                    </label>
                                    <input type="text" name="thainame" class="form-control" placeholder="Enter Thai Name" id="thainame" required>
                                </div>
                                <div class="form-group">
                                    <label for="engname">
                                        English Name
                                    </label>
                                    <input type="text" placeholder="Enter English Name" name="engname" id="engname" class="form-control" required>
                                </div>
                                <div class="form-group">
                                    <label for="abbr">
                                        Abbreviation
                                    </label>
                                    <input type="text" class="form-control" placeholder="Enter Abbreviation" name="abbr" id="abbr" required>
                                </div>
                                <div class="form-group">
                                    <label for="company">
                                        Company
                                    </label>
                                    <select name="company" id="company" class="form-control">
                                        <option>SNG</option>
                                        <option>SNGE</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="position">
                                        Position
                                    </label>
                                    <input type="text" class="form-control" placeholder="Enter Position" name="position" id="position" required>
                                </div>
                                <div class="form-group">
                                    <label for="service_center">
                                        Service Center
                                    </label>
                                    <select name="service_center" id="service_center" class="form-control">
                                        <option value="Central">Central</option>
                                        <option value="Northeast">Northeast</option>
                                        <option value="East">East</option>
                                        <option value="North">North</option>
                                        <option value="South">South</option>
                                        <option value="West">West</option>
                                        <option value="Other">Other</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="section">
                                        Section
                                    </label>
                                    <input type="text" placeholder="Enter Section" name="section" id="section" class="form-control" required>
                                </div>
                                <div class="form-group">
                                    <label for="team">
                                        Team
                                    </label>
                                    <input type="text" placeholder="Enter Team" name="team" id="team" class="form-control" required>
                                </div>
                                <div class="form-group">
                                    <label for="status">
                                        Status
                                    </label>
                                    <select name="status" id="status" class="form-control">
                                        <option>Available</option>
                                        <option>Sick</option>
                                        <option>On Personal Leave</option>
                                        <option>Medium Workload</option>
                                        <option>Heavy Workload</option>
                                        <option>Not Available</option>
                                        <option>Quit</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="email">
                                        Email
                                    </label>
                                    <input type="email" placeholder="Enter Email" name="email" id="email" class="form-control" required>
                                </div>
                                <div class="form-group">
                                    <label for="phone">
                                        Phone
                                    </label>
                                    <input type="text" placeholder="Enter Phone" name="phone" id="phone" class="form-control">
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">Cancel</button>
                                <button type="submit" class="btn btn-primary" id="editFsebtn">Edit FSE</button>
                            </div> 
                        </form>
                    </div>
                </div>
            </div>

            <div class="modal" tabindex="-1" role="dialog" id="add-fse-popup">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Add FSE</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </div>
                    <form id="add-fse-form" accept-charset="UTF-8">
                        <div class="modal-body">
                            <div class="form-group">
                                <label for="fse_code">
                                    FSE Code
                                </label>
                                <input type="text" name="fse_code" class="form-control" placeholder="Enter FSE Code" id="fse_code" required>
                            </div>
                            <div class="form-group">
                                <label for="thainame">
                                    Thai Name
                                </label>
                                <input type="text" name="thainame" class="form-control" placeholder="Enter Thai Name" id="thainame" required>
                            </div>
                            <div class="form-group">
                                <label for="engname">
                                    English Name
                                </label>
                                <input type="text" placeholder="Enter English Name" name="engname" id="engname" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label for="abbr">
                                    Abbreviation
                                </label>
                                <input type="text" class="form-control" placeholder="Enter Abbreviation" name="abbr" id="abbr" required>
                            </div>
                            <div class="form-group">
                                <label for="company">
                                    Company
                                </label>
                                <select name="company" class="form-control">
                                    <option>SNG</option>
                                    <option>SNGE</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="position">
                                    Position
                                </label>
                                <input type="text" placeholder="Enter Position" name="position" class="form-control" id="position" required>
                            </div>
                            <div class="form-group">
                                <label for="service_center">
                                    Service Center
                                </label>
                                <select name="service_center" class="form-control">
                                    <option value="Central">Central</option>
                                    <option value="Northeast">Northeast</option>
                                    <option value="East">East</option>
                                    <option value="North">North</option>
                                    <option value="South">South</option>
                                    <option value="West">West</option>
                                    <option value="Other">Other</option>
                                </select>
                                <!-- <input type="text" placeholder="Enter Service Center" name="service_center" class="form-control" id="service_center" required> -->
                            </div>
                            <div class="form-group">
                                <label for="section">
                                    Section
                                </label>
                                <input type="text" placeholder="Enter Section" name="section" id="section" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label for="team">
                                    Team
                                </label>
                                <input type="text" placeholder="Enter Team" name="team" id="team" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label for="status">
                                    Status
                                </label>
                                <select name="status" id="status" class="form-control">
                                    <option>Available</option>
                                    <option>Sick</option>
                                    <option>On Personal Leave</option>
                                    <option>Medium Workload</option>
                                    <option>Heavy Workload</option>
                                    <option>Not Available</option>
                                    <option>Quit</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="email">
                                    Email
                                </label>
                                <input type="email" placeholder="Enter Email" name="email" id="email" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label for="phone">
                                    Phone
                                </label>
                                <input type="text" placeholder="Enter Phone" name="phone" id="phone" class="form-control">
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-primary" id="addFseBtn">Add FSE</button>
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
    <script src="/srmsng/public/js/table_setup.js"></script>
    <script src="/srmsng/public/js/fetch_ajax.js"></script>
    <script src="/srmsng/public/js/fetch_fse.js"></script>
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
            if (add_success == 'true') {
                $('#add-success').css('display','block');
            }
            var update_success = url.searchParams.get("update_success")
            if (update_success == 'true') {
                $('#update-success').css('display','block');
            }
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