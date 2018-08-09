<?php 
    require($_SERVER['DOCUMENT_ROOT'].'/srmsng/public/cookie_validate_superadmin.php');
    $ticket_exist = file_exists($_SERVER['DOCUMENT_ROOT'].'/srmsng/settings/ticket.enable');
    $announcement_exist = file_exists($_SERVER['DOCUMENT_ROOT'].'/srmsng/settings/announcement.enable');
    $service_exist = file_exists($_SERVER['DOCUMENT_ROOT'].'/srmsng/settings/service.enable');
    $maintenance_exist = file_exists($_SERVER['DOCUMENT_ROOT'].'/srmsng/settings/maintenance.enable');
    $tracking_exist = file_exists($_SERVER['DOCUMENT_ROOT'].'/srmsng/settings/tracking.enable');
    $fse_exist = file_exists($_SERVER['DOCUMENT_ROOT'].'/srmsng/settings/fse.enable');
    $workload_exist = file_exists($_SERVER['DOCUMENT_ROOT'].'/srmsng/settings/workload.enable');
    $kpi_exist = file_exists($_SERVER['DOCUMENT_ROOT'].'/srmsng/settings/kpi.enable');
    $asset_exist = file_exists($_SERVER['DOCUMENT_ROOT'].'/srmsng/settings/asset.enable');
    $item_exist = file_exists($_SERVER['DOCUMENT_ROOT'].'/srmsng/settings/item.enable');
    $customer_exist = file_exists($_SERVER['DOCUMENT_ROOT'].'/srmsng/settings/customer.enable');
    $account_exist = file_exists($_SERVER['DOCUMENT_ROOT'].'/srmsng/settings/account.enable');
    $password_exist = file_exists($_SERVER['DOCUMENT_ROOT'].'/srmsng/settings/password.enable');
    $system_exist = file_exists($_SERVER['DOCUMENT_ROOT'].'/srmsng/settings/system.enable');

    // echo $rr;
    $ticket_setting = '<input type="checkbox" id="ticket" name="ticket" onChange="testing(this)">';
    if($ticket_exist != null){
        $ticket_setting = '<input type="checkbox" checked id="ticket" name="ticket" onChange="testing(this)">';
    }

    $announcement_setting = '<input type="checkbox" id="announcement" name="announcement" onChange="testing(this)">';
    if($announcement_exist != null){
        $announcement_setting = '<input type="checkbox" checked id="announcement" name="announcement" onChange="testing(this)">';
    }

    $service_setting = '<input type="checkbox" id="service" name="service" onChange="testing(this)">';
    if($service_exist != null){
        $service_setting = '<input type="checkbox" checked id="service" name="service" onChange="testing(this)">';
    }

    $maintenance_setting = '<input type="checkbox" id="maintenance" name="maintenance" onChange="testing(this)">';
    if($maintenance_exist != null){
        $maintenance_setting = '<input type="checkbox" checked id="maintenance" name="maintenance" onChange="testing(this)">';
    }

    $tracking_setting = '<input type="checkbox" id="tracking" name="tracking" onChange="testing(this)">';
    if($tracking_exist != null){
        $tracking_setting = '<input type="checkbox" checked id="tracking" name="tracking" onChange="testing(this)">';
    }

    $fse_setting = '<input type="checkbox" id="fse" name="fse" onChange="testing(this)">';
    if($fse_exist != null){
        $fse_setting = '<input type="checkbox" checked id="fse" name="fse" onChange="testing(this)">';
    }

    $workload_setting = '<input type="checkbox" id="workload" name="workload" onChange="testing(this)">';
    if($workload_exist != null){
        $workload_setting = '<input type="checkbox" checked id="workload" name="workload" onChange="testing(this)">';
    }

    $kpi_setting = '<input type="checkbox" id="kpi" name="kpi" onChange="testing(this)">';
    if($kpi_exist != null){
        $kpi_setting = '<input type="checkbox" checked id="kpi" name="kpi" onChange="testing(this)">';
    }

    $asset_setting = '<input type="checkbox" id="asset" name="asset" onChange="testing(this)">';
    if($asset_exist != null){
        $asset_setting = '<input type="checkbox" checked id="asset" name="asset" onChange="testing(this)">';
    }

    $item_setting = '<input type="checkbox" id="item" name="item" onChange="testing(this)">';
    if($item_exist != null){
        $item_setting = '<input type="checkbox" checked id="item" name="item" onChange="testing(this)">';
    }

    $customer_setting = '<input type="checkbox" id="customer" name="customer" onChange="testing(this)">';
    if($customer_exist != null){
        $customer_setting = '<input type="checkbox" checked id="customer" name="customer" onChange="testing(this)">';
    }

    $account_setting = '<input type="checkbox" id="account" name="account" onChange="testing(this)">';
    if($account_exist != null){
        $account_setting = '<input type="checkbox" checked id="account" name="account" onChange="testing(this)">';
    }

    $password_setting = '<input type="checkbox" id="password" name="password" onChange="testing(this)">';
    if($password_exist != null){
        $password_setting = '<input type="checkbox" checked id="password" name="password" onChange="testing(this)">';
    }

    $system_setting = '<input type="checkbox" id="system" name="system" onChange="testing(this)">';
    if($system_exist != null){
        $system_setting = '<input type="checkbox" checked id="system" name="system" onChange="testing(this)">';
    }
    // echo $ticket_setting;
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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <link rel="stylesheet" href="/srmsng/public/css/style.css">
</head>
<body>
<?php include_once($_SERVER['DOCUMENT_ROOT'].'/srmsng/public/admin/superadmin_nav.php'); ?>
    <main class="container">
        <div class="form-container">
            
                <h2>Maintenance<br/>&nbsp</h2>
                <!-- <div class="row"> -->
                <div class="row">
                    <div class="col-8">
                        <b>Ticket</b>
                    </div>
                    <div class="col-4">
                        <label class="switch" style="bottom: 20px; right: 50%">
                            <?php echo $ticket_setting; ?>
                            <span class="slider" ></span>
                        </label>   
                    </div>
                </div>
                <div>
                    <b>Ticket</b>
                    <label class="switch" style="margin-left: 60%">
                        <?php echo $ticket_setting; ?>
                        <span class="slider" ></span>
                    </label>    
                </div>
                <div>
                    <b>Announcement</b>
                    <label class="switch">
                            <?php echo $announcement_setting; ?>
                            <span class="slider"></span>
                    </label>      
                </div>
                <div class="">
                    <b>Service Request</b>
                    <label class="switch" style="margin-left: 10em">
                        <?php echo $service_setting; ?>
                        <span class="slider"></span>
                    </label>    
                </div>
                <div class="">
                    <b>Maintenance Plan</b>
                    <label class="switch" style="margin-left: 10em">
                        <?php echo $maintenance_setting; ?>
                        <span class="slider"></span>
                    </label>    
                </div>
                <div class="">
                    <b>Tracking</b>
                    <label class="switch" style="margin-left: 10em">
                        <?php echo $tracking_setting; ?>
                        <span class="slider"></span>
                    </label>    
                </div>
                <div class="">
                    <b>FSE</b>
                    <label class="switch" style="margin-left: 10em">
                        <?php echo $fse_setting; ?>
                        <span class="slider"></span>
                    </label>    
                </div>
                <div class="">
                    <b>Workload</b>
                    <label class="switch" style="margin-left: 10em">
                        <?php echo $workload_setting; ?>
                        <span class="slider"></span>
                    </label>    
                </div>
                <div class="">
                    <b>KPI</b>
                    <label class="switch" style="margin-left: 10em">
                        <?php echo $kpi_setting; ?>
                        <span class="slider"></span>
                    </label>    
                </div>
                <div class="">
                    <b>Assets</b>
                    <label class="switch" style="margin-left: 10em">
                        <?php echo $asset_setting; ?>
                        <span class="slider"></span>
                    </label>    
                </div>
                <div class="">
                    <b>Items</b>
                    <label class="switch" style="margin-left: 10em">
                        <?php echo $item_setting; ?>
                        <span class="slider"></span>
                    </label>    
                </div>
                <div class="">
                    <b>Customers</b>
                    <label class="switch" style="margin-left: 10em">
                        <?php echo $customer_setting; ?>
                        <span class="slider"></span>
                    </label>    
                </div>
                <div class="">
                    <b>Account Management</b>
                    <label class="switch" style="margin-left: 10em">
                        <?php echo $account_setting; ?>
                        <span class="slider"></span>
                    </label>    
                </div>
                <div class="">
                    <b>Password Reset</b>
                    <label class="switch" style="margin-left: 10em">
                        <?php echo $password_setting; ?>
                        <span class="slider"></span>
                    </label>    
                </div>
                <div class="">
                    <b>System Log</b>
                    <label class="switch" style="margin-left: 10em">
                        <?php echo $system_setting; ?>
                        <span class="slider"></span>
                    </label>    
                </div>

                
        </div>
    </main>
</body>
    <script src="https://code.jquery.com/jquery-3.3.1.min.js" integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.0/umd/popper.min.js" integrity="sha384-cs/chFZiN24E4KMATLdqdvsezGxaGsi4hLGOzlXwp5UZB1LY//20VyM2taTB4QvJ" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.0/js/bootstrap.min.js" integrity="sha384-uefMccjFJAIv6A+rW+L4AHf99KvxDjWSu1z9VI8SKNVmz4sk7buKt/6v9KI65qnm" crossorigin="anonymous"></script>
    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <script src="/srmsng/public/js/submit.js"></script>
    <script>
        

        function testing(test){
            var checkbox = document.getElementById(test.name);
            console.log(test.name);
            if(checkbox.checked){
                $.ajax({
                    type: "POST",
                    url: `/srmsng/api/v1/maintenance/${test.name}`,
                    success: () => {
                        alert(`Maintenance Mode: ${test.name} ENABLE`);
                    }
                })
            }else{
                $.ajax({
                    type: "DELETE",
                    url: `/srmsng/api/v1/maintenance/${test.name}`,
                    success: () => {
                        alert(`Maintenance Mode: ${test.name} DISABLE`);
                    }
                })
            }
        }
        // checkbox.addEventListener( 'change', function() {
        //     var fileinput = document.getElementById("fileToUpload");
        //     if(this.checked) {
        //         // Checkbox is checked..
        //         fileinput.classList.add("disabled");
        //     } else {
        //         fileinput.classList.remove("disabled");
        //         // Checkbox is not checked..
        //     }
        // });

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