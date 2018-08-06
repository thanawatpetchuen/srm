<?php 
    
    require($_SERVER['DOCUMENT_ROOT'].'/srmsng/public/cookie_validate_forall.php');
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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <link rel="stylesheet" href="/srmsng/public/css/style.css">
    
</head>
<body>
    <?php
        if($_SESSION['account_type'] == "USER"){
            include_once('../customer/customer_nav.php');     
        }else if($_SESSION['account_type'] == "ADMIN"){
            include_once('../admin/admin_nav.php'); 
        }else if($_SESSION['account_type'] == "SUPERADMIN"){
            include_once('../admin/superadmin_nav.php'); 
        }else{
            include_once('../fse/fse_nav.php'); 
        }
    ?>
   
    <main class="container news" >
        <div class="container">
            <div class="d-flex justify-content-between">
                <h2>Hello, <?php echo $_SESSION["username_unhash"]?>!</h2>
                <h2 id="myclock"></h2>
            </div>
            <div id="notices">
                <!-- <div class="alert alert-primary" role="alert">
                    <i class="fa fa-info"></i> A simple info alert—check it out!
                    Lorem ipsum dolor sit amet, consectetur adipisicing elit. Doloribus maiores dolores voluptate dolorum! Molestiae sed ullam dicta vel nam deleniti eveniet optio saepe. Ducimus saepe consectetur, est assumenda nemo nobis!
                </div> -->
            </div>
            
            
            <h2>Announcements</h2>
            <div id="all-news">
                <!-- <div class="news-item row">
                    <div class="col news-img col-2">
                        <img class="card-img" data-src="holder.js/100px260/" alt="100%x260" src="data:image/svg+xml;charset=UTF-8,%3Csvg%20width%3D%22372%22%20height%3D%22260%22%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20viewBox%3D%220%200%20372%20260%22%20preserveAspectRatio%3D%22none%22%3E%3Cdefs%3E%3Cstyle%20type%3D%22text%2Fcss%22%3E%23holder_164b6064ab9%20text%20%7B%20fill%3Argba(255%2C255%2C255%2C.75)%3Bfont-weight%3Anormal%3Bfont-family%3AHelvetica%2C%20monospace%3Bfont-size%3A19pt%20%7D%20%3C%2Fstyle%3E%3C%2Fdefs%3E%3Cg%20id%3D%22holder_164b6064ab9%22%3E%3Crect%20width%3D%22372%22%20height%3D%22260%22%20fill%3D%22%23777%22%3E%3C%2Frect%3E%3Cg%3E%3Ctext%20x%3D%22137.40625%22%20y%3D%22139%22%3E372x260%3C%2Ftext%3E%3C%2Fg%3E%3C%2Fg%3E%3C%2Fsvg%3E">
                    </div>
                    <div class="col col-10">
                        <span class="news-header-wrapper"><h4 class="news-header">News title</h4><span class="news-date">Posted on 20/07/2018 by Thanawat</span></span>
                        <p>Cards can be organized into Masonry-like columns with just CSS by wrapping them in .card-columns. Cards are built with CSS column properties instead of flexbox for easier alignment. Cards are ordered from top to bottom and left to right.</p>
                        <div class="footer d-flex justify-content-end">
                            <a href="#">Read more...</a>
                        </div>
                    </div>
                </div>
                <div class="news-item row">
                    <div class="col news-img col-2">
                        <img class="card-img" data-src="holder.js/100px260/" alt="100%x260" src="data:image/svg+xml;charset=UTF-8,%3Csvg%20width%3D%22372%22%20height%3D%22260%22%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20viewBox%3D%220%200%20372%20260%22%20preserveAspectRatio%3D%22none%22%3E%3Cdefs%3E%3Cstyle%20type%3D%22text%2Fcss%22%3E%23holder_164b6064ab9%20text%20%7B%20fill%3Argba(255%2C255%2C255%2C.75)%3Bfont-weight%3Anormal%3Bfont-family%3AHelvetica%2C%20monospace%3Bfont-size%3A19pt%20%7D%20%3C%2Fstyle%3E%3C%2Fdefs%3E%3Cg%20id%3D%22holder_164b6064ab9%22%3E%3Crect%20width%3D%22372%22%20height%3D%22260%22%20fill%3D%22%23777%22%3E%3C%2Frect%3E%3Cg%3E%3Ctext%20x%3D%22137.40625%22%20y%3D%22139%22%3E372x260%3C%2Ftext%3E%3C%2Fg%3E%3C%2Fg%3E%3C%2Fsvg%3E">
                    </div>
                    <div class="col col-10">
                        <span class="news-header-wrapper"><h4 class="news-header">News title</h4><span class="news-date">Posted on 20/07/2018 by Thanawat</span></span>
                        <p>Cards can be organized into Masonry-like columns with just CSS by wrapping them in .card-columns. Cards are built with CSS column properties instead of flexbox for easier alignment. Cards are ordered from top to bottom and left to right.</p>
                        <div class="footer d-flex justify-content-end">
                            <a href="#">Read more...</a>
                        </div>
                    </div>
                </div> -->
            </div>

            <nav class="d-flex justify-content-end">
                <!-- <ul class="pagination" id="pagi">
                    <li class="page-item"><a class="page-link" href="#">Previous</a></li>
                    <li class="page-item active"><a class="page-link" href="#">1</a></li>
                    <li class="page-item"><a class="page-link" href="#">2</a></li>
                    <li class="page-item"><a class="page-link" href="#">3</a></li>
                    <li class="page-item"><a class="page-link" href="#">Next</a></li>
                </ul> -->

                <div id="page-selection">
                    
                </div>
                




            </nav>
        </div>

    </main>
</body>
    <script src="https://code.jquery.com/jquery-3.3.1.min.js" integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.0/umd/popper.min.js" integrity="sha384-cs/chFZiN24E4KMATLdqdvsezGxaGsi4hLGOzlXwp5UZB1LY//20VyM2taTB4QvJ" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.0/js/bootstrap.min.js" integrity="sha384-uefMccjFJAIv6A+rW+L4AHf99KvxDjWSu1z9VI8SKNVmz4sk7buKt/6v9KI65qnm" crossorigin="anonymous"></script>
    <script src="https://unpkg.com/infinite-scroll@3/dist/infinite-scroll.pkgd.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
	<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <script type="text/javascript" src="/srmsng/public/js/bootpag.js"></script>
    <script src="/srmsng/public/js/table_setup.js"></script>
    <script src="/srmsng/vendor/dmauro-Keypress/keypress.js"></script>
    <script src="/srmsng/public/js/fetch_ajax.js"></script>
    <script src="/srmsng/public/js/fetch_ticket.js"></script>
    <script src="/srmsng/public/js/submit.js"></script>
    <script src="/srmsng/public/js/onclose.js"></script>
    <script src="/srmsng/public/js/news.js"></script>
</html>