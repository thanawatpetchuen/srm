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
    <main class="container">
        <div class="input-group page-title">
            <h1 style="margin-bottom:0;">Edit Ticket</h1>
        </div>

        <form id="update-ticket-form">
            <div class="form-group">
                <label><h4>CM ID</h4></label>
                <input type="text" class="form-control" name="cm_id" placeholder="CM ID/Job ID" readonly/>
            </div>
            <div class="form-group">
                <label><h4>SNG Code</h4></label>
                <input type="text" class="form-control" name="sng_code" placeholder="SNG Code"readonly/>
            </div>

            <fieldset>
                <legend>Contact Information</legend>
                <div class="form-group">
                    <label>Name</label>
                    <input type="text" class="form-control" name="name" placeholder="Customer Name" />  
                </div>
                <div class="form-group">
                    <label>Phone Number</label>
                    <input type="text" class="form-control" name="phone_number" placeholder="Phone Number" id="phone-number-field"/>
                    <small class="form-text text-muted" id="phone-number-warning">Mobile phone number only (10 digits)</small> 
                </div>
                <div class="form-group">
                    <label>Email</label>
                    <input type="email" class="form-control" name="email" placeholder="Email" />
                </div>
            </fieldset>

            <div class="row" >
                <div class="col">
                    <fieldset>
                        <legend>Problem Reported by Customer</legend>
                        <div class="form-group">
                            <label>Type of Problem Reported</label>
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
                            <textarea type="text" class="form-control" name="asset_problem" placeholder="Details on the problem reported by the customer" maxlength="300"></textarea>  
                            <small class="form-text text-muted">Maximum: 300 Characters</small>
                        </div>
                    </fieldset>
                    <fieldset>
                        <legend>Cause</legend>
                        <div class="form-group">
                            <label>Type of Cause</label>
                            <select name="cause_id" class="form-control" id="cause-dropdown">
                                <option value="0">-- Select Type of Cause --</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Details</label>
                            <textarea type="text" class="form-control" name="cause_detail" placeholder="Details on cause of problem" maxlength="300"></textarea>
                            <small class="form-text text-muted">Maximum: 300 Characters</small> 
                        </div>
                    </fieldset>
                </div>
                <div class="col">
                    <fieldset>
                        <legend>Corrections</legend>
                        <div class="form-group">
                            <label>Type of Correction</label>
                            <select name="correction_id" id="correction-dropdown" class="form-control">
                                <option value="0">-- Select Type of Correction --</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Details</label>
                            <textarea type="text" class="form-control" name="correction_detail" placeholder="Details on corrections" maxlength="300"></textarea>
                            <small class="form-text text-muted">Maximum: 300 Characters</small> 
                        </div>
                    </fieldset>
                    <fieldset>
                        <legend>Detected Problem</legend>
                        <div class="form-group">
                            <label>Detected Problem</label>
                            <textarea type="text" class="form-control" name="asset_detected" placeholder="Problem found by FSE" maxlength="300"></textarea>
                            <small class="form-text text-muted">Maximum: 300 Characters</small> 
                        </div>
                        <div class="form-group">
                            <label>Solution</label>
                            <textarea type="text" class="form-control" name="solution" placeholder="Solution to the problem" maxlength="300"></textarea>
                            <small class="form-text text-muted">Maximum: 300 Characters</small> 
                        </div>
                        <div class="form-group">
                            <label>Suggestions</label>
                            <textarea type="text" class="form-control" name="suggestions" placeholder="Suggestions" maxlength="300"></textarea>
                            <small class="form-text text-muted">Maximum: 300 Characters</small> 
                        </div>
                    </fieldset>
                </div>
            </div>
            <fieldset>
                <legend>Job</legend>
                <div class="form-group">
                    <label>Job Type</label>
                    <select name="job_type" class="form-control">
                        <option value="">-- Select Job Type --</option>
                        <option value="Fixed by phone">Fixed by Phone</option>
                        <option value="On site">On Site</option>
                    </select>
                </div>
                <div class="form-group checkbox">
                    <input type="checkbox" name="assign-fse"/>
                    <label class="checkbox">Assign Field Service Engineer</label>
                </div>
                <div class="form-group disabled" id="fse-fieldset">
                    <div class="select-multiple" id="fse-dropdown"></div>
                    <span>Assign Field Service Engineer <b>Leader</b></span>
                    <div id="selected-fse">
                        <span class="selected-fse-none">None</span>
                    </div>
                </div>
                <div class="form-group checkbox">
                    <input type="checkbox" name="assign-cm-time"/>
                    <label class="checkbox">Assign CM Time</label>
                </div>
                <div class="form-group disabled" id="cm-time-field">
                    <input type="text" class="form-control" name="cm_time" placeholder="CM Time" id="cm_time" autocomplete="off" disabled/>
                </div>
                <div class="form-group checkbox disabled" id="close-time-check">
                    <input type="checkbox" name="assign-close-time"/>
                    <label class="checkbox">Completed</label>
                </div>
                <div class="form-group disabled" id="close-time-field">
                    <label>Completion time</label>
                    <input type="text" class="form-control" name="complete_time" placeholder="Completion Time" id="complete_time" autocomplete="off" disabled/>
                </div>
            </fieldset>
            <div class="form-group" style="margin-top:40px; display: flex; align-items: center; justify-content: flex-end; ">
                <!-- <div class="loader"> -->
                <i class="fas fa-spinner fa-spin text-center" style="font-size:22px; display: none" id="loader"></i>
                <a class="btn btn-outline-secondary" href="/srmsng/public/ticket" style="margin-left: 10px"> 
                    Cancel
                </a>
                <button type="submit" class="btn btn-primary" style="margin-left: 5px">Update Ticket</button>
                <!-- </div> -->
            </div> 
        </form>
        
    </main>
</body>
<script src="https://code.jquery.com/jquery-3.3.1.min.js" integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.0/js/bootstrap.min.js" integrity="sha384-uefMccjFJAIv6A+rW+L4AHf99KvxDjWSu1z9VI8SKNVmz4sk7buKt/6v9KI65qnm" crossorigin="anonymous"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
<script src="/srmsng/public/js/submit.js"></script>
<script>
    // Initialize Date Range Pickers
    $(function() {
        $('input[name="cm_time"], input[name="complete_time"]').daterangepicker({
            timePicker: true,
            "timePicker24Hour": true,
            singleDatePicker: true,
            startDate: moment().startOf('hour'),
            endDate: moment().startOf('hour').add(32, 'hour'),
            locale: {
                format: 'Y-MM-DD H:mm:ss'
            }
        });
    });

    function fetchDropdowns(codeParam, valueParam, dropdownID, url) {
        // fetch dropdowns
        $(dropdownID).val(0);
        fetch(url)
        .then(resp => {
          return resp.json();
        })
        .then(data_json => {
          data_json.forEach(element => {
            if (element[codeParam] != 0) {
                var option = document.createElement('option');
                option.setAttribute('value',element[codeParam]);
                option.innerHTML = element[valueParam];
                document.getElementById(dropdownID).appendChild(option);
            }
          })
        })
    }

    $(document).ready( function() {
        // fetch causes
        fetchDropdowns(
            'cause_id',
            'cause_description',
            'cause-dropdown',
            '/srmsng/public/index.php/api/admin/getallcauses');
        // fetch coorections
        fetchDropdowns(
            'correction_id',
            'correction_description',
            'correction-dropdown',
            '/srmsng/public/index.php/api/admin/getallcorrections');

        // fetch fse
        $('#fse-dropdown input[type="checkbox"]').prop('checked', false);
        fetch('/srmsng/public/index.php/api/admin/getfse')
            .then(resp => {
                return resp.json();
            })
            .then(data_json => {
                data_json.forEach(element => {
                    if (element['fse_code'] != 0) {
                        var item = document.createElement('span');
                        item.setAttribute('class','select-multiple-item');

                        var checkbox = document.createElement('input');
                        checkbox.setAttribute('type','checkbox');
                        checkbox.setAttribute('value',element['fse_code']);
                        checkbox.setAttribute('name','fse_code[]');

                        checkbox.onclick = function() {
                            var fselabel = document.createElement('span');
                            var radio_leader = document.createElement('input');
                            radio_leader.setAttribute("type", "radio");
                            radio_leader.setAttribute("name", "leader");
                            radio_leader.setAttribute("value", element['fse_code']);
                            radio_leader.required = true;
                            fselabel.appendChild(radio_leader);
                            fselabel.setAttribute('id',element['fse_code']);
                            fselabel.appendChild(document.createTextNode(element['engname']));
                            if (!checkbox.checked) {
                                document.getElementById(element['fse_code']).outerHTML = '';
                            } else {
                                document.getElementById('selected-fse').appendChild(fselabel);
                            }
                        }

                        item.appendChild(checkbox);
                        item.appendChild(document.createTextNode(' ' + element['engname']));
                        document.getElementById('fse-dropdown').appendChild(item);
                    }
                })
            })
            .then( function() {
                // fetch data from cm id
                var url_string = window.location.href;
                var url = new URL(url_string);
                var cm_id = url.searchParams.get("cm_id")
                
                fetch("/srmsng/public/index.php/api/admin/request/single?cm_id=" + cm_id)
                    .then(res => {
                        // console.log(res);
                        return res.json();
                    })
                    .then(data => {
                        for (var value in data[0]) {
                            $(".form-control[name='" + value + "']").val(data[0][value]);
                        }
                        $(".form-control[name='cm_id']").val(cm_id);
                        if (data[0]['GROUP_CONCAT(fse_code)'] != 0) {
                            // enable assign fse if fse already exists for this ticket
                            $('input[name="assign-fse"]').prop('checked', true);
                            //$('#fse-fieldset input').prop('disabled', false);
                            $('#fse-fieldset').removeClass('disabled');
                            
                            var fseArray = data[0]['GROUP_CONCAT(fse_code)'].split(',');
                            for (var i in fseArray) {
                                $('#fse-dropdown input[value="' + fseArray[i] + '"]').trigger('click');
                            }
                        }
                        if (data[0]['cm_time'] != '' && data[0]['cm_time'] != null) {
                            $('#cm-time-field input').prop('disabled', false);
                            $('#cm-time-field input').val(data[0]['cm_time']);
                            $('#cm-time-field').removeClass('disabled');
                            $('input[name="assign-cm-time"]').prop('checked',true);
                        }
                        if (data[0]['complete_time'] != '' && data[0]['complete_time'] != null) {
                            $('#close-time-field input').prop('disabled', false);
                            $('#close-time-field input').val(data[0]['complete_time']);
                            $('#close-time-field').removeClass('disabled');
                            $('#close-time-check').removeClass('disabled');
                            $('input[name="assign-close-time"]').prop('checked',true);
                        }
                })
            });

        // Tick to assign
        $('input[name="assign-fse"]').on('change', function() {
            if ($(this).is(':checked')) {
                $('#fse-fieldset').removeClass('disabled');
                
                if ($('input[name="assign-cm-time"]').is(':checked')) {
                    $('#close-time-check').removeClass('disabled');
                }

            } else {
                $('#fse-fieldset').addClass('disabled');

                // Cannot assign completion time
                $('#close-time-check').addClass('disabled');
                $('#close-time-check input').prop('checked',false);
                $('#close-time-field').addClass('disabled');
                $('#close-time-field input').prop('disabled',true);
            }
        });
        $('input[name="assign-cm-time"]').on('change', function() {
            if ($(this).is(':checked')) {
                $('#cm-time-field input').prop('disabled', false);
                $('#cm-time-field').removeClass('disabled');
                
                if ($('input[name="assign-fse"]').is(':checked')) {
                    $('#close-time-check').removeClass('disabled');
                }

            } else {
                $('#cm-time-field input').prop('disabled', true);
                $('#cm-time-field').addClass('disabled');

                // Cannot assign completion time
                $('#close-time-check').addClass('disabled');
                $('#close-time-check input').prop('checked',false);
                $('#close-time-field').addClass('disabled');
                $('#close-time-field input').prop('disabled',true);
            }
        });
        $('input[name="assign-close-time"]').on('change', function() {
            if ($(this).is(':checked')) {
                $('#close-time-field input').prop('disabled', false);
                $('#close-time-field').removeClass('disabled');
            } else {
                $('#close-time-field input').prop('disabled', true);
                $('#close-time-field').addClass('disabled');
            }
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