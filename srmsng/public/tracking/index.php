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
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <link rel="stylesheet" href="/srmsng/public/css/style.css">
</head>
<body class="tracking">
    <?php include_once('../admin/admin_nav.php'); ?>
    <div id="map"></div>
    <div class="modal" tabindex="-1" role="dialog" id="assign-modal-site">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Assign FSE</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </div>
                <form id="assign-tracking-form">
                    <div class="modal-body">
                        <div class="form-group">
                            <label>CM ID</label>
                            <input type="text" class="form-control" name="cm_id" placeholder="CM ID" readonly/>
                        </div>
                        <div class="form-group">
                            <label>Site Name</label>
                            <input type="text" class="form-control" name="sitename" placeholder="Site Name" readonly/>
                        </div>
                        <!-- <div class="form-group">
                            <label>Type of Problem Reported</label>
                            <input type="text" class="form-control" name="problem_type" readonly/>
                        </div>
                        <div class="form-group">
                            <label>Details</label>
                            <textarea type="text" class="form-control" name="asset_problem" readonly></textarea>  
                        </div> -->
                        <div class="form-group" id="cm-time-field">
                            <label>CM Time</label>
                            <input type="text" class="form-control" name="cm_time" placeholder="CM Time" id="cm_time" autocomplete="off"/>
                        </div>
                        <div class="form-group">
                            <label><b>Assign FSE</b></label>
                            <div class="selected-fse" id="selected-fse">
                                <span class="selected-fse-none">None</span>
                            </div>
                            <div id="fse-code-input">
                            </div>
                            <button class="btn btn-primary" style="margin-top:15px;" type="button" id="select-from-map" data-dismiss="modal">Select from map</button>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <i class="fas fa-spinner fa-spin text-center" style="font-size:22px; display: none" id="loader"></i>
                        <button type="button" class="btn btn-outline-secondary" data-dismiss="modal" id="assign-cancel">Cancel</button>
                        <button type="submit" class="btn btn-primary">Assign</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="tracking-select">
        <h5>Selected Field Service Engineers:</h5>
        <div class="selected-fse" id="selected-fse-on-select">
            <span class="selected-fse-none">None</span>
        </div>
        <button class="btn btn-primary" id="select-done" style="margin-top:15px;" data-toggle="modal" data-target="#assign-modal-site">Done</button>
    </div>
    <script src="https://code.jquery.com/jquery-3.3.1.min.js" integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.0/umd/popper.min.js" integrity="sha384-cs/chFZiN24E4KMATLdqdvsezGxaGsi4hLGOzlXwp5UZB1LY//20VyM2taTB4QvJ" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.0/js/bootstrap.min.js" integrity="sha384-uefMccjFJAIv6A+rW+L4AHf99KvxDjWSu1z9VI8SKNVmz4sk7buKt/6v9KI65qnm" crossorigin="anonymous"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    
    <script src="https://www.gstatic.com/firebasejs/5.1.0/firebase-app.js"></script>
    <script src="https://www.gstatic.com/firebasejs/5.1.0/firebase-database.js"></script>
    <script src="https://www.gstatic.com/firebasejs/5.1.0/firebase-functions.js"></script>
    <script src="/srmsng/public/js/submit.js"></script>
    <script src="tracking.js"></script>
    <!-- <script async defer
    src="https://maps.googleapis.com/maps/api/js?key=AIzaSyC8NC0ToCiqmrDLjvOu9H74ZjeWtgQkU7E&callback=initMap">
    </script> -->
    <script async defer
    src="https://maps.googleapis.com/maps/api/js?key=AIzaSyA9PZM0cHmzEm7LOEBB_coeCZpNOLI7aC4&callback=initMap">
    </script>
    <script>
      $(document).ready( function() {
        // Initialize Date Range Pickers
        $('input[name="cm_time"]').daterangepicker({
            timePicker: true,
            "timePicker24Hour": true,
            singleDatePicker: true,
            // startDate: moment().startOf('hour'),
            // endDate: moment().startOf('hour').add(32, 'hour'),
            locale: {
                format: 'Y-MM-DD H:mm:ss'
            }
        });

        // Submit tracking form
        $('#assign-tracking-form').submit(function() {
            console.log($('#assign-tracking-form').serialize());
            return false;
        });
      });
    </script>
    <script>
    var config = {
        apiKey: "AIzaSyA9PZM0cHmzEm7LOEBB_coeCZpNOLI7aC4",
        authDomain: "srm-tracking-system.firebaseapp.com",
        databaseURL: "https://srm-tracking-system.firebaseio.com",
        projectId: "srm-tracking-system",
        storageBucket: "srm-tracking-system.appspot.com",
        messagingSenderId: "28002227602"
    };

    firebase.initializeApp(config);
    </script>
</body>
</html>