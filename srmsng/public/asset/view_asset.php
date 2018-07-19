<?php 
    require($_SERVER['DOCUMENT_ROOT'].'/srmsng/public/cookie_validate_admin.php');
?>
<html>
<head>
<meta charset="utf-8">
    <meta id="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta id="description" content="">
    <meta id="author" content="">
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
    <?php include_once('../admin/admin_nav.php'); ?>
    <main class="container">
        <div class="input-group page-title">
            <h1 style="margin-bottom:0;">Asset Details</h1>
        </div>
        <fieldset>
            <legend>SNG Code</legend>
            <div class="asset-details">
                <strong>SNG Code: </strong><span id="sng_code"></span>
            </div>
        </fieldset>

        <fieldset>
            <legend>Customer</legend>
            <div class="asset-details">
                <strong>Customer Name: </strong><span id="customer_name"></span><br>
                <strong>Customer Code: </strong><span id="customer_no"></span>
            </div>
        </fieldset>

        <fieldset>
            <legend>Location</legend>
            <div class="asset-details">
                <strong>Site Name: </strong><span id="sitename"></span><br>
                <strong>Location Code: </strong><span id="location_code"></span><br>
                <strong>Address: </strong><span id="address"></span><br>
                <strong>Region: </strong><span id="region"></span><br>
                <strong>Country: </strong><span id="country"></span>
            </div>
        </fieldset>

        <div class="row">
            <div class="col">
                <fieldset>
                    <legend>Sale Order</legend>
                    <div class="asset-details">
                        <strong>Sale Order No: </strong><span id="sale_order_no"></span><br>
                        <strong>Year: </strong><span id="since"></span><br>
                        <strong>Date Ordered: </strong><span id="date_order"></span><br>
                        <strong>PO Number: </strong><span id="po_number"></span><br>
                        <strong>PO Date: </strong><span id="po_date"></span><br>
                        <strong>D/O Number: </strong><span id="do_number"></span>
                    </div>
                </fieldset>
                <fieldset>
                    <legend>Item</legend>
                    <div class="asset-details">
                        <strong>Item Name: </strong><span id="model"></span><br>
                        <strong>Item Number: </strong><span id="itemnumber"></span><br>
                        <strong>Power (kVA): </strong><span id="power"></span>
                    </div>
                </fieldset>
                <fieldset>
                    <div class="asset-details" style="padding-top:20px">
                        <strong>Serial No: </strong><span id="serial"></span><br>
                        <strong>Installed by: </strong><span id="engname"></span>
                    </div>
                </fieldset>
                <fieldset>
                    <legend>Contact</legend>
                    <div class="asset-details">
                        <strong>Contact Name: </strong><span id="contactname"></span><br>
                        <strong>Contact Number: </strong><span id="contactnumber"></span><br>
                    </div>
                </fieldset>
            </div>
            <div class="col">
                <fieldset>
                    <legend>Battery</legend>
                    <div class="asset-details">
                        <strong>Battery Type: </strong><span id="battery"></span><br>
                        <strong>Item Number: </strong><span id="quantity"></span><br>
                        <strong>Battery Date: </strong><span id="battery_date"></span>
                    </div>
                </fieldset>
                <fieldset>
                    <legend>Warranty</legend>
                    <div class="asset-details">
                        <strong>Start Warranty: </strong><span id="startwarranty"></span><br>
                        <strong>End Warranty: </strong><span id="endwarranty"></span>
                    </div>
                </fieldset>
                <fieldset>
                    <legend>Service Level Agreement</legend>
                    <div class="asset-details">
                        <strong>Condition: </strong><span id="sla_condition"></span><br>
                        <strong>Response on Site: </strong><span id="sla_response"></span><br>
                        <strong>Recovery Time: </strong><span id="sla_recovery"></span>
                    </div>
                </fieldset>
                <fieldset>
                    <legend>Preventive Maintenance</legend>
                    <div class="asset-details">
                        <strong>PM/Year: </strong><span id="pmyear"></span><br>
                        <strong>Next PM Date: </strong><span id="nextpm"></span>
                    </div>
                </fieldset>
                <fieldset>
                    <legend>Contract</legend>
                    <div class="asset-details">
                        <strong>Type of Contract: </strong><span id="typeofcontract"></span>
                    </div>
                </fieldset>
                <fieldset>
                    <legend>Status</legend>
                    <div class="asset-details">
                        <strong>UPS Status: </strong><span id="ups_status"></span>
                    </div>
                </fieldset>
            </div>
        </div>
    </main>
</body>
<script src="https://code.jquery.com/jquery-3.3.1.min.js" integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.0/js/bootstrap.min.js" integrity="sha384-uefMccjFJAIv6A+rW+L4AHf99KvxDjWSu1z9VI8SKNVmz4sk7buKt/6v9KI65qnm" crossorigin="anonymous"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
<script src="/srmsng/public/js/submit.js"></script>

<script>
    function fetchDataFromSNGCode() {
        var url_string = window.location.href;
        var url = new URL(url_string);
        var sng_code = url.searchParams.get("sng_code");

        fetch("/srmsng/public/index.php/api/admin/singleasset?sng_code=" + sng_code)
        .then(res => {
            // console.log(res);
            return res.json();
        })
        .then(data => {
            for (var value in data[0]) {
                $("#" + value).text(data[0][value]);
            }
            var village = "";
            var road = "";
            var subdistrict = "";
            var district = "";
            var province = "";
            if (data[0]["village_no"] != "" && data[0]["village_no"] != undefined) {
                village = "หมู่ " + data[0]["village_no"] + " ";
            }
            if (data[0]["road"] != "" && data[0]["road"] != undefined) {
                road = "ถนน" + data[0]["road"] + ", ";
            }
            if (data[0]["region"] == "กรุงเทพมหานคร" || data[0]["province"] == "กรุงเทพมหานคร") {
                if (data[0]["sub_district"] != "" && data[0]["sub_district"] != undefined) {
                subdistrict = "แขวง" + data[0]["sub_district"] + ", ";
                }
                if (data[0]["district"] != "" && data[0]["district"] != undefined) {
                subdistrict = "เขต" + data[0]["district"] + ", ";
                }
                province = data[0]["province"] + " ";
            } else {
                if (data[0]["sub_district"] != "" && data[0]["sub_district"] != undefined) {
                subdistrict = "ตำบล" + data[0]["sub_district"] + ", ";
                }
                if (data[0]["district"] != "" && data[0]["district"] != undefined) {
                subdistrict = "อำเภอ" + data[0]["district"] + ", ";
                }
                province = "จังหวัด" + data[0]["province"] + " ";
            }
            var address = data[0]["house_no"] + " " + village + road + subdistrict + district + province + data[0]["postal_code"];
            $("#address").text(address);
        });
    }
    $(document).ready( function() {
        fetchDataFromSNGCode();
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