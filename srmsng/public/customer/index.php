<?php
    require($_SERVER['DOCUMENT_ROOT'].'/srmsng/public/cookie_validate_customer.php');
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
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.16/css/jquery.dataTables.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <link rel="stylesheet" href="/srmsng/public/css/style.css">

    <!--     Fonts and icons     -->
    <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css" rel="stylesheet">
    <link href='https://fonts.googleapis.com/css?family=Roboto:400,700,300' rel='stylesheet' type='text/css'>

    <style>
        #popup {
            display:none;
        }
    </style>
</head>
<body>
    <?php include_once('customer_nav.php'); ?>
    <main class="container">
        <div class="input-group page-title">
            <h1 style="margin-bottom:0;">My Assets</h1>
        </div>
        <table id="supertable">
            <thead>
                <th>SNG-CODE</th>
                <th>Year</th>
                <th>Product Name</th>
                <th>Rate (kVA)</th>
                <th>Battery Type</th>
                <th>Number of Batteries</th>
                <th>Serial</th>
                <th>Site Name</th>
                <th>Start  Warranty</th>
                <th>End Warranty</th>
                <th>Status</th>
                <th>Type of Contract</th>
                <th>Action</th>
            </thead>
            <tbody id="maintable">

            </tbody>
        </table>
        <div class="modal" tabindex="-1" role="dialog" id="popup">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Request</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </div>
                    <form id="problem-submit">
                        <div class="modal-body">
                            <div class="alert alert-primary" role="alert" id="request-submit-alert"></div>
                            <div class="form-group">
                                <h4>SNG Code:</h4> 
                                <input class="form-control" value="" id="sng-code-modal" name="sngcode" readonly/>
                            </div>
                            <input type="hidden" name="request_id" value="<?php echo $_SESSION['account_no'] ?>"/>
                            <input type="hidden" name="request_user" value="<?php echo $_SESSION['username_unhash']?>"/>
                            <div class="form-group">
                                <label>Name</label>
                                <input class="form-control" type="text" name="name" placeholder="Name" required/>       
                            </div>
                            <div class="form-group">
                                <label>Phone Number</label>
                                <input type="text" class="form-control" name="phone" placeholder="Phone Number" id="phone-number-field"/>
                                <small class="form-text text-muted" id="phone-number-warning">Mobile phone number only (10 digits)</small> 
                            </div>
                            <div class="form-group">
                                <label>Email</label>
                                <input class="form-control" type="email" name="email" placeholder="Email" required/>       
                            </div>
                            <div class="form-group">
                                <label>Type of Problem</label>
                                <select name="problem_type" class="form-control" required>
                                    <option value="">-- Select Type of Problem --</option>
                                    <option>Battery fault</option>
                                    <option>Cooling fault</option>
                                    <option>Internal fault</option>
                                    <option>Unit overload</option>
                                    <option>Input CB trip/OFF</option>
                                    <option>Input CB fault</option>
                                    <option>Output CB trip</option>
                                    <option>No UPS voltage for some outlet</option>
                                    <option>No output</option>
                                    <option>No backup</option>
                                    <option>Emergency power off</option>
                                    <option>Neutral high voltage</option>
                                    <option>Over temperature</option>
                                    <option>Smell burn</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Details</label>
                                <textarea 
                                    class="form-control" 
                                    type="text" 
                                    name="problem" 
                                    placeholder="Additional details on the problem"
                                    maxlength="300"
                                    required></textarea>     
                                <small class="form-text text-muted">Maximum: 300 Characters</small>  
                            </div>
                            <input type="hidden" name="request_time" />
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button type="button" class="btn btn-primary" onClick="confirmRequest()">Confirm</button>
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
    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <script src="/srmsng/public/js/table_setup.js"></script>
    <script src="/srmsng/public/js/fetch_ajax.js"></script>
    <script src="/srmsng/public/js/fetch_data_user.js"></script>
    <script src="/srmsng/public/js/submit.js"></script>
    <script>
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