<?php 
    
    require($_SERVER['DOCUMENT_ROOT'].'/srmsng/public/cookie_validate_fortemp.php');
    // require('../cookie_validate_admin.php');
    // echo json_encode($_SESSION);
    // echo $id;

    $sql = "SELECT title, content, author, DATE_FORMAT(date, '%d %M %Y (%H:%i)') AS date, image FROM news WHERE id = "."$id";

        // if ($_SESSION['account_type'] == 'ADMIN'){
            try{
                // Get DB Object
                $db = new db();
                // Connect
                $db = $db->connect();

                $stmt = $db->query($sql);
                $news = $stmt->fetchAll(PDO::FETCH_OBJ);
                $db = null;
                if($news == null){
                    // echo "ASD";
                    
                    header("Location: /srmsng/public/announcement", true,  301);
                    exit();
                }
                $data = $news[0]; 
                // $jjd = json_decode($customers);
                // echo json_encode($jj->{"id"});
            } catch(PDOException $e){
                $db = null;
                header("Location: /srmsng/public/announcement", true,  301);
                exit();
                // echo '{"error": {"text": '.$e->getMessage().'}';
            }
    // }


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
    <?php
        if($_SESSION['account_type'] == "USER"){
            include_once($_SERVER['DOCUMENT_ROOT'].'/srmsng/public/customer/customer_nav.php');     
        }else if($_SESSION['account_type'] == "ADMIN"){
            include_once($_SERVER['DOCUMENT_ROOT'].'/srmsng/public/admin/admin_nav.php'); 
        }else if($_SESSION['account_type'] == "SUPERADMIN"){
            include_once($_SERVER['DOCUMENT_ROOT'].'/srmsng/public/admin/superadmin_nav.php'); 
        }else{
            include_once($_SERVER['DOCUMENT_ROOT'].'/srmsng/public/fse/fse_nav.php'); 
        }
    ?>
   
    <main class="container news" >
        <div class="container">
            <a href="../../announcement" style="display:block; margin-bottom:30px;"><i class="fa fa-angle-left"></i> Back</a>
            <div class="row">
                <div class="col-sm-4 news-img order-sm-2">
                    <img class="card-img temp-news-image" data-src="holder.js/100px260/" alt="100%x260" src="<?php echo $data->{"image"}?>">
                </div>
                <div class="col-sm-8 order-sm-1" style="padding-top:.75rem">
                    <div class="news-header-wrapper">
                        <h1><?php echo $data->{"title"}?></h1>
                        <span class="news-date">Posted on <?php echo $data->{"date"}?> by <?php echo $data->{"author"}?></span>
                    </div>
                    <div class="news-content content">
                        <p><?php echo $data->{"content"}?></p>
                    </div>
                </div>
                
            </div>
        </div>

    </main>
</body>
    <script src="https://code.jquery.com/jquery-3.3.1.min.js" integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.0/umd/popper.min.js" integrity="sha384-cs/chFZiN24E4KMATLdqdvsezGxaGsi4hLGOzlXwp5UZB1LY//20VyM2taTB4QvJ" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.0/js/bootstrap.min.js" integrity="sha384-uefMccjFJAIv6A+rW+L4AHf99KvxDjWSu1z9VI8SKNVmz4sk7buKt/6v9KI65qnm" crossorigin="anonymous"></script>
    <script src="https://unpkg.com/infinite-scroll@3/dist/infinite-scroll.pkgd.js"></script>
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

        // ------------- Start Clock -------------
        // var clock = 0;
        // var interval_msec = 1000;

        // // ready

        // $(function() {
        //     // set timer
        //     clock = setTimeout("UpdateClock()", interval_msec);
        // });

        // // UpdateClock
        // function UpdateClock(){

        //     // clear timer
        //     clearTimeout(clock);

        //     var dt_now = new Date();
        //     var hh	= dt_now.getHours();
        //     var mm	= dt_now.getMinutes();
        //     var ss	= dt_now.getSeconds();

        //     if(hh < 10){
        //         hh = "0" + hh;
        //     }
        //     if(mm < 10){
        //         mm = "0" + mm;
        //     }
        //     if(ss < 10){
        //         ss = "0" + ss;
        //     }
        //     $("#myclock").html( hh + ":" + mm + ":" + ss);

        //     // set timer
        //     clock = setTimeout("UpdateClock()", interval_msec);

        // }

        // ---------------------------------------------------

        
        $(document).ready( function () {
        

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