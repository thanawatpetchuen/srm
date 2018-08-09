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
<?php include_once($_SERVER['DOCUMENT_ROOT'].'/srmsng/public/admin/admin_nav.php'); ?>
    <main class="container" data-my-var="<?php echo $_GET['id']?>" id="mainn">
         <div class="alert alert-success" id="add-success">
            Location added
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="alert alert-success" id="update-success">
            Location updated
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="input-group page-title">
            <h1 style="margin-bottom:0;">Customer Infomation</h1>
            <a class="btn btn-outline-secondary" href="../customer">
                <i class="fa fa-angle-left"></i> Back
            </a>
        </div>

        <div class="view" style="padding-bottom: 20px; display: block">
            <div class="row">
                <div class="col">
                    <h5>Customer Number: <span id="customer_number" class="detail"></span></h5>
                    <h5>Account Group: <span id="account_group" class="detail"></span></h5>
                    <h5>Tax ID: <span id="tax_id" class="detail"></span></h5>
                </div>
                <div class="col">
                    <h5>Customer Name: <span id="customer_name" class="detail"></span></h5>
                    <h5>Product Sale: <span id="product_sale" class="detail"></span></h5>
                    <h5>Primary Contact: <span id="primary_contact" class="detail"></span></h5>
                </div>
            </div>
        </div>
        <table id="supertable">
            <thead>
                <!-- <th>ID</th> -->
                <th>Location Code</th>
                <th>Customer Number</th>
                <th>Site Name</th>
                <th>House Number</th>
                <th>Village Number</th>
                <th>Soi</th>
                <th>Road</th>
                <th>District</th>
                <th>Sub District</th>
                <th>Province</th>
                <th>Postal Code</th>
                <th>Region</th>
                <th>Country</th>
                <th>Store Phone</th>
                <th>Latitude</th>
                <th>Longitude</th>
                <th>Action</th>
            </thead>
            <tbody id="maintable">
         
            </tbody>
        </table>

        <a href="#" class="btn btn-primary" data-target="#add-location-popup" id="add-location-button" data-toggle="modal">
                <i class="fa fa-plus"></i> Add Location
            </a>
        <div class="modal" tabindex="-1" role="dialog" id="edit-location-popup">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Edit Location</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </div>
                        <form method="post" id="edit-location-form">
                            <div class="modal-body">
                                <div class="form-group">
                                    <label for="customer_name">
                                        Location Code
                                    </label>
                                    <input type="text" name="location_code" class="form-control" placeholder="Location Code" id="location_code" readonly>
                                </div>
                                <div class="form-group">
                                    <label for="customer_no">
                                        Customer Number
                                    </label>
                                    <input type="text" placeholder="Customer Number" name="customer_no" id="customer_no" class="form-control" readonly>
                                </div>
                                <div class="form-group">
                                    <label for="taxid">
                                        Site Name
                                    </label>
                                    <input type="text" class="form-control" placeholder="Enter Site Name" name="sitename" id="sitename" required>
                                </div>
                                <div class="form-group">
                                    <label for="house_no">
                                        House Number
                                    </label>
                                    <input type="text" class="form-control" placeholder="Enter House Number" name="house_no" id="house_no">
                                </div>
                                <div class="form-group">
                                    <label for="village_no">
                                        Village Number
                                    </label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">หมู่</span>
                                        </div>
                                        <input type="text" placeholder="Enter Village Number" name="village_no" class="form-control" id="village_no">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="account_owner">
                                        Soi
                                    </label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">ซอย</span>
                                        </div>
                                        <input type="text" placeholder="Soi" name="soi" id="soi" class="form-control">
                                    </div>  
                                </div>
                                <div class="form-group">
                                    <label for="account_owner">
                                        Road
                                    </label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">ถนน</span>
                                        </div>
                                        <input type="text" placeholder="Enter Road" name="road" id="road" class="form-control">
                                    </div>  
                                </div>
                                <div class="form-group">
                                    <label for="sub_district">
                                        Sub District
                                    </label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">ตำบล/แขวง</span>
                                        </div>
                                        <input type="text" placeholder="Enter Sub District" name="sub_district" id="sub_district" class="form-control">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="district">
                                        District
                                    </label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">อำเภอ/เขต</span>
                                        </div>
                                        <input type="text" placeholder="Enter District" name="district" id="district" class="form-control">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="province">
                                        Province
                                    </label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">จังหวัด</span>
                                        </div>
                                        <input type="text" placeholder="Enter Province" name="province" id="province" class="form-control">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="postal_code">
                                        Postal Code
                                    </label>
                                    <input type="text" placeholder="Enter Postal Code" name="postal_code" id="postal_code" class="form-control">
                                </div>
                                <div class="form-group">
                                    <label for="region">
                                        Region
                                    </label>
                                    <select name="region" id="region" class="form-control clear">
                                        <option value="กรุงเทพมหานคร">กรุงเทพมหานคร</option>
                                        <option value="กลาง">กลาง</option>
                                        <option value="ตะวันออกเฉียงเหนือ">ตะวันออกเฉียงเหนือ</option>
                                        <option value="ตะวันออก">ตะวันออก</option>
                                        <option value="เหนือ">เหนือ</option>
                                        <option value="ใต้">ใต้</option>
                                        <option value="ตะวันตก">ตะวันตก</option>
                                        <option value="อื่นๆ">อื่นๆ</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="district">
                                        Country
                                    </label>
                                    <input type="text" placeholder="Enter Country" name="country" id="country" class="form-control" value="ไทย" required>
                                </div>
                                <div class="form-group">
                                    <label for="store_phone">
                                        Store Phone
                                    </label>
                                    <input type="text" placeholder="Enter Store Phone" name="store_phone" id="store_phone" class="form-control">
                                </div>
                                <fieldset>
                                    <div class="form-group">
                                        <label for="store_phone">
                                            Latitude
                                        </label>
                                        <input type="text" placeholder="Site Latitude" name="lat" id="latitude" class="form-control" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="store_phone">
                                            Longitude
                                        </label>
                                        <input type="text" placeholder="Site Longitude" name="lon" id="longitude" class="form-control" required>
                                    </div>
                                    <button type="button" class="btn btn-primary" onClick="openMap('edit')">Find on Map</button>
                                </fieldset>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">Cancel</button>
                                <button type="submit" class="btn btn-primary" id="addUserBtn">Confirm Edit</button>
                            </div> 
                        </form>
                    </div>
                </div>
            </div>

            <div class="modal" tabindex="-1" role="dialog" id="add-location-popup">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Add Location</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </div>
                        <form method="post" id="add-location-form">
                            <div class="modal-body">
                                <div class="form-group">
                                    <label for="customer_name">
                                        Location Code
                                    </label>
                                    <input type="text" name="location_code" class="form-control" placeholder="Location Code" id="location_code" require>
                                </div>
                                <div class="form-group">
                                    <label for="customer_no">
                                        Customer Number
                                    </label>
                                    <input type="text" placeholder="Customer Number" name="customer_no" id="customer_no" class="form-control" require readonly>
                                </div>
                                <div class="form-group">
                                    <label for="taxid">
                                        Site Name
                                    </label>
                                    <input type="text" class="form-control" placeholder="Enter Site Name" name="sitename" id="sitename" required>
                                </div>
                                <div class="form-group">
                                    <label for="house_no">
                                        House Number
                                    </label>
                                    <input type="text" class="form-control" placeholder="Enter House Number" name="house_no" id="house_no" required>
                                </div>
                                <div class="form-group">
                                    <label for="village_no">
                                        Village Number
                                    </label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">หมู่</span>
                                        </div>
                                        <input type="text" placeholder="Enter Village Number" name="village_no" class="form-control" id="village_no" required>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="account_owner">
                                        Soi
                                    </label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">ซอย</span>
                                        </div>
                                        <input type="text" placeholder="Soi" name="soi" class="form-control">
                                    </div>  
                                </div>
                                <div class="form-group">
                                    <label for="account_owner">
                                        Road
                                    </label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">ถนน</span>
                                        </div>
                                        <input type="text" placeholder="Enter Road" name="road" id="road" class="form-control" required>
                                    </div>  
                                </div>
                                <div class="form-group">
                                    <label for="sub_district">
                                        Sub District
                                    </label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">ตำบล/แขวง</span>
                                        </div>
                                        <input type="text" placeholder="Enter Sub District" name="sub_district" id="sub_district" class="form-control" required>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="district">
                                        District
                                    </label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">อำเภอ/เขต</span>
                                        </div>
                                        <input type="text" placeholder="Enter District" name="district" id="district" class="form-control" required>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="province">
                                        Province
                                    </label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">จังหวัด</span>
                                        </div>
                                        <input type="text" placeholder="Enter Province" name="province" id="province" class="form-control" required>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="postal_code">
                                        Postal Code
                                    </label>
                                    <input type="text" placeholder="Enter Postal Code" name="postal_code" id="postal_code" class="form-control" required>
                                </div>
                                <div class="form-group">
                                    <label for="region">
                                        Region
                                    </label>
                                    <select name="region" id="region" class="form-control clear">
                                        <option value="กรุงเทพมหานคร">กรุงเทพมหานคร</option>
                                        <option value="กลาง">กลาง</option>
                                        <option value="ตะวันออกเฉียงเหนือ">ตะวันออกเฉียงเหนือ</option>
                                        <option value="ตะวันออก">ตะวันออก</option>
                                        <option value="เหนือ">เหนือ</option>
                                        <option value="ใต้">ใต้</option>
                                        <option value="ตะวันตก">ตะวันตก</option>
                                        <option value="อื่นๆ">อื่นๆ</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="district">
                                        Country
                                    </label>
                                    <input type="text" placeholder="Enter Country" name="country" id="country" class="form-control" value="ไทย" required>
                                </div>
                                <div class="form-group">
                                    <label for="store_phone">
                                        Store Phone
                                    </label>
                                    <input type="text" placeholder="Enter Store Phone" name="store_phone" id="store_phone" class="form-control">
                                </div>
                                <fieldset>
                                    <div class="form-group">
                                        <label for="store_phone">
                                            Latitude
                                        </label>
                                        <input type="text" placeholder="Site Latitude" name="lat" id="latitude" class="form-control" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="store_phone">
                                            Longitude
                                        </label>
                                        <input type="text" placeholder="Site Longitude" name="lon" id="longitude" class="form-control" required>
                                    </div>
                                    <button type="button" class="btn btn-primary" onClick="openMap('add')">Find on Map</button>
                                </fieldset>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">Cancel</button>
                                <button type="submit" class="btn btn-primary" id="addUserBtn">Add Location</button>
                            </div> 
                        </form>
                    </div>
                </div>
            </div>
            <div class="modal" role="dialog" id="map-modal">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header" style="display:block; padding-bottom:0;">
                            <h5 class="modal-title">Mark Location on Map</h5>
                            <div class="form-group" style="margin-top:15px">
                                <input type="text" placeholder="Search" name="map_search" id="map-search-field" class="form-control">
                            </div>
                        </div>
                        <div id="map" style="width:100%; height:500px"></div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-outline-secondary" data-dismiss="modal" data-toggle="modal" data-target="#add-location-popup" id="map-cancel">Cancel</button>
                            <button type="submit" class="btn btn-primary" id="mark-location">Mark</button>
                        </div>
                        <input type="hidden" id="from-type" disabled/>
                    </div>
                </div>
            </div>
    </main>
</body>
<script src="https://code.jquery.com/jquery-3.3.1.min.js" integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.0/js/bootstrap.min.js" integrity="sha384-uefMccjFJAIv6A+rW+L4AHf99KvxDjWSu1z9VI8SKNVmz4sk7buKt/6v9KI65qnm" crossorigin="anonymous"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/v/dt/dt-1.10.16/fc-3.2.4/r-2.2.1/datatables.min.js"></script>
<script src="/srmsng/public/js/table_setup.js"></script>
<script src="/srmsng/public/js/fetch_ajax.js"></script>
<script src="/srmsng/public/js/submit.js"></script>
<script src="/srmsng/public/js/view_customer.js"></script>
<script src="/srmsng/public/js/map_places.js"></script>
<!-- <script
    src="https://maps.googleapis.com/maps/api/js?key=AIzaSyC8NC0ToCiqmrDLjvOu9H74ZjeWtgQkU7E&libraries=places&callback=initMap">
    </script> -->
    <script
    src="https://maps.googleapis.com/maps/api/js?key=AIzaSyA9PZM0cHmzEm7LOEBB_coeCZpNOLI7aC4&libraries=places&callback=initMap">
    </script>
<script>
    // Map
    function openMap(type) {
        $('#add-location-popup .close').trigger('click');
        $('#edit-location-popup .close').trigger('click');

        $("#map-modal").modal("toggle");
        $("#from-type").val(type);
        if (type == 'edit') {
            $("#map-cancel").attr('data-target','#edit-location-popup');
        } else {
            $("#map-cancel").attr('data-target','#add-location-popup');
        }
        
        var lat = $('input[name="lat"]').val();
        var lng = $('input[name="lon"]').val();
        initMarker(lat,lng);
    }

    $(document).ready( function () {
        var url_string = window.location.href;
        var url = new URL(url_string);
        var id = url.searchParams.get("id");

        $('input[name="customer_no"]').val(id);

        var add_success = url.searchParams.get("add_success");
        if (add_success == 'true') {
            $('#add-success').css('display','block');
        }
        var update_success = url.searchParams.get("update_success")
        if (update_success == 'true') {
            $('#update-success').css('display','block');
        }

        // Map
        initMap();
        $("#mark-location").on("click", function() {
            
            $("#map-modal").modal("toggle");
            if ($("#from-type").val() == "add") {
                $("#add-location-popup").modal("toggle");
            } else {
                $("#edit-location-popup").modal("toggle");
            }
        });

        // Reset Add Location lat and lng
        $('#add-location-button').on('click', function() {
            $('#add-location-popup input[name="latitude"]').val('');
            $('#add-location-popup input[name="longitude"]').val('');
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