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
            <h1 style="margin-bottom:0;">Add Ticket</h1>
        </div>

        <form id="add-ticket-form">
            <div class="form-group" id="sng-code-ticket-field">
                <label><h4>SNG Code</h4></label>
                <input type="text" class="form-control" name="sng_code" placeholder="SNG Code" required/>
                <small class="form-text text-danger hidden">SNG Code does not exist. Please check your SNG Code or add an asset of this SNG Code and try again.</small> 
            </div>

            <fieldset>
                <legend>Contact Information</legend>
                <div class="form-group">
                    <label>Name</label>
                    <input type="text" class="form-control" name="name" placeholder="Customer Name" required/>  
                </div>
                <div class="form-group">
                    <label>Phone Number</label>
                    <input type="text" class="form-control" name="phone_number" placeholder="Phone Number" maxlength="10" size="10" id="phone-number-field" required/>
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
                            <label>Details of Problem Reported</label>
                            <textarea type="text" class="form-control" name="asset_problem" placeholder="Details on the problem reported by the customer" maxlength="300"></textarea>  
                            <small class="form-text text-muted">Maximum: 300 Characters</small>
                        </div>
                    </fieldset>
                    <fieldset>
                        <legend>Cause</legend>
                        <div class="form-group">
                            <label>Type of Cause</label>
                            <select name="cause_id" id="cause-dropdown" class="form-control">
                                <option value="0">-- Select Type of Cause --</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Cause Details</label>
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
                <div class="hidden" id="job-details-site">
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
                </div>
                <div class="hidden" id="job-details-phone">
                    <div class="form-group">
                        <label>Start time</label>
                        <input type="text" class="form-control" name="start_time" placeholder="Start Time" id="start_time" autocomplete="off" disabled/>
                    </div>
                    <div class="form-group">
                        <label>Completion time</label>
                        <input type="text" class="form-control" name="close_time" placeholder="Close Time" id="close_time" autocomplete="off" disabled/>
                    </div>
                </div>
            </fieldset>
            <div class="form-group" style="margin-top:40px; display: flex; align-items: center; justify-content: flex-end; text-align:right ">
                <i class="fas fa-spinner fa-spin text-center" style="font-size:22px; display: none" id="loader"></i>
                <a class="btn btn-outline-secondary" href="/srmsng/public/ticket" id="ticket-cancel"  style="margin-left: 10px"> 
                    Cancel
                </a>
                <button type="submit" class="btn btn-primary" style="margin-left: 5px">Add Ticket</button>
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
        $('input[name="cm_time"], input[name="complete_time"], input[name="start_time"], input[name="close_time"]').daterangepicker({
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

    $(document).ready(function() {
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

                    checkbox.onchange = function() {
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
                            if (document.getElementById(element['fse_code'])) {
                                document.getElementById(element['fse_code']).outerHTML = '';
                            }
                        } else {
                            document.getElementById('selected-fse').appendChild(fselabel);
                        }
                    }

                    item.appendChild(checkbox);
                    item.appendChild(document.createTextNode(' ' + element['engname']));
                    document.getElementById('fse-dropdown').appendChild(item);
                }
            });
        });

        // fetch causes
        fetchDropdowns(
            'cause_id',
            'cause_description',
            'cause-dropdown',
            '/srmsng/public/index.php/api/admin/getallcauses');
        // fetch causes
        fetchDropdowns(
            'correction_id',
            'correction_description',
            'correction-dropdown',
            '/srmsng/public/index.php/api/admin/getallcorrections');
        
        // Tick to assign and lock completion time
        var assigned_fse = $('input[name="assign-fse"]').is(':checked');
        var assigned_cm_time = $('input[name="assign-cm-time"]').is(':checked');

        $('select[name="job_type"]').on('change',function() {
            if ($(this).val() === 'On site') {
                $('#job-details-site').removeClass('hidden');
                $('#job-details-phone').addClass('hidden');

                $('#job-details-phone input').val("");
                $('#job-details-phone input').prop('disabled',true);

            } else if ($(this).val() === 'Fixed by phone') {
                $('#job-details-site').addClass('hidden');
                $('#job-details-phone').removeClass('hidden');

                $('#job-details-site input').val("");
                $('#job-details-site input[type="text"]').prop('disabled',true);

                $('#job-details-site input[name="assign-close-time"]').prop('checked',false).change();
                $('#job-details-site input[name="assign-fse"]').prop('checked',false).change();
                $('#job-details-site input[name="assign-cm-time"]').prop('checked',false).change();

                $('#job-details-phone input').prop('disabled',false);
            } else {
                $('#job-details-site').addClass('hidden');
                $('#job-details-phone').addClass('hidden');

                $('#job-details-phone input').val("");
                $('#job-details-phone input').prop('disabled',true);
                $('#job-details-site input').val("");
                $('#job-details-site input[type="text"]').prop('disabled',true);

                $('#job-details-site input[name="assign-close-time"]').prop('checked',false).change();
                $('#job-details-site input[name="assign-fse"]').prop('checked',false).change();
                $('#job-details-site input[name="assign-cm-time"]').prop('checked',false).change();
            }
        });
        $('input[name="assign-fse"]').on('change', function() {
            if ($(this).is(':checked')) {
                $('#fse-fieldset').removeClass('disabled');
                assigned_fse = true;
            } else {
                $('#fse-fieldset').addClass('disabled');
                $('#fse-fieldset input[type="checkbox"]').prop('checked',false).change();
                assigned_fse = false;
            }
        });
        $('input[name="assign-cm-time"]').on('change', function() {
            if ($(this).is(':checked')) {
                $('#cm-time-field input').prop('disabled', false);
                $('#cm-time-field').removeClass('disabled');
                assigned_cm_time = true
            } else {
                $('#cm-time-field input').prop('disabled', true);
                $('#cm-time-field').addClass('disabled');
                assigned_cm_time = false
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

        $('input[name="assign-fse"], input[name="assign-cm-time"]').on('change',function() {
            if (assigned_cm_time && assigned_fse) {
                $('#close-time-check').removeClass('disabled');
            } else {
                // Cannot assign completion time
                $('#close-time-check').addClass('disabled');
                $('#close-time-check input').prop('checked',false);
                $('#close-time-field').addClass('disabled');
                $('#close-time-field input').prop('disabled',true);
            }
        });


        // Get SNG Code in case the user adds a ticket from the work page
        var url_string = window.location.href;
        var url = new URL(url_string);
        var sng_code = url.searchParams.get("sng_code");

        if (sng_code) {
            $('input[name="sng_code"]').val(sng_code);
            $('input[name="sng_code"]').prop('readonly',true);
            
            // New Submit function (different redirection if SNG Code exists
            // in the URL)
            $('#add-ticket-form').unbind('submit');
            $('#add-ticket-form').on('submit', function(e) {
                $("#loader").css("display", "block");
                if ($("#phone-number-field").val().length == 10) {
                    e.preventDefault();
                    console.log($("#add-ticket-form").serializeArray());
                    $.ajax({
                    type: "POST",
                    data: $("#add-ticket-form").serialize(),
                    url: "/srmsng/public/index.php/api/admin/addticket",
                    success: data => {
                        console.log(data);
                        if (data == 1) {
                        $("#sng-code-ticket-field small").removeClass("hidden");
                        window.scrollTo(0, 0);
                        } else {
                        $("#loader").css("display", "none");
                        window.location = "/srmsng/public/asset/work?add_success=true&sng_code=" + sng_code;
                        }
                    },
                    error: err => {
                        console(err);
                    }
                    });
                } else {
                    $("#phone-number-warning").attr("class", "form-text text-danger");
                    $("#phone-number-field").focus();
                    return false;
                }
                return false;
            })

            // New Cancel Link
            $('#ticket-cancel').attr('href','/srmsng/public/asset/work?sng_code='+sng_code)
        }
    })
</script>