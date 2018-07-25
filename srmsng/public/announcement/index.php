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
    <script>
        console.log("READY");

        // ------------- Start Clock -------------
        var clock = 0;
        var interval_msec = 1000;

        // ready

        $(function() {
            // set timer
            clock = setTimeout("UpdateClock()", interval_msec);
        });

        // UpdateClock
        function UpdateClock(){

            // clear timer
            clearTimeout(clock);

            var dt_now = new Date();
            var hh	= dt_now.getHours();
            var mm	= dt_now.getMinutes();
            var ss	= dt_now.getSeconds();

            if(hh < 10){
                hh = "0" + hh;
            }
            if(mm < 10){
                mm = "0" + mm;
            }
            if(ss < 10){
                ss = "0" + ss;
            }
            $("#myclock").html( hh + ":" + mm + ":" + ss);

            // set timer
            clock = setTimeout("UpdateClock()", interval_msec);

        }

        // ---------------------------------------------------

        
        $(document).ready( function () {
            // var total_news = 0;
            $.ajax({
                type: "GET",
                dataType: "JSON",
                url: "/srmsng/public/announcement/news/api/getnotices",
                success: data => {
                    data.forEach((datai) => {
                        $("#notices").append(`<div class="alert alert-${datai.type}" role="alert">
                    <i class="fa fa-info"></i> ${datai.title}</div>`)
                    })
                }
            })
            $.ajax({
                type: "GET",
                dataType: "JSON",
                url: "/srmsng/public/announcement/news/api/getnews/0/5",
                success: (data) => {
                    // console.log(data);
                    
                    data.forEach((datai) => {
                        var badge = "";
                        var default_image = "https://increasify.com.au/wp-content/uploads/2016/08/default-image.png";
                        console.log(datai);
                        if (datai.image !== ""){
                            default_image = datai.image;
                        }
                        let inputDate = new Date(datai.date);
                        let todaysDate = new Date();
                        if(inputDate.setHours(0,0,0,0) == todaysDate.setHours(0,0,0,0)) {
                            // Date equals today's date
                            badge = `<span class="badge badge-primary blink" style="margin-left: 0.5em;">New</span>`;
                        }
                        console.log(inputDate);
                        $("#all-news").append(`<div class="news-item row">
                    <div class="col-sm-2 ">
                        <img class="img-fluid news-image" data-src="holder.js/100px260/" alt="100%x260" src="${default_image}">
                    </div>
                    <div class="col-sm-10">
                        <span class="news-header-wrapper"><h4 class="news-header">${datai.title}</h4><span class="news-date">Posted on ${datai.date} by ${datai.author}</span>${badge}</span>
                        <p style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">${datai.content}</p>
                        <div class="d-flex align-items-end flex-column footee">
                            <a class="mt-auto p-2" href="/srmsng/public/announcement/news/${datai.id}">Read more...</a>
                        </div>
                    </div>
                </div>`)
                    })

                }
            }).then(() => {
                var total_news = 0
                $.ajax({
                    type: "GET",
                    dataType: "JSON",
                    url: "/srmsng/public/announcement/news/api/countnews",
                    success: (total) => {
                        total_news = total[0].count;
                        console.log("Total news on success: "+total_news);
                        // console.log("CEil"+Math.ceil(/5));
                        
                    }
                }).then(() => {
                    
                    $('#page-selection').bootpag({
                                total: Math.ceil((total_news/5))
                            }).on("page", function(event, /* page number here */ num){
                                console.log("Total news: "+total_news);
                                var start_page = (num*5)-5;
                                var stop_page = 5;
                                console.log("Page Number: "+num);
                                console.log(`Start Page: ${start_page}, Stop Page: ${stop_page}`);
                                $("#all-news").empty();
                                $.ajax({
                                    type: "GET",
                                    dataType: "JSON",
                                    url: `/srmsng/public/announcement/news/api/getnews/${start_page}/${stop_page}`,
                                    success: (data) => {
                                        console.log("SEC");
                                        console.log(data);
                                        data.forEach((datai) => {
                                            var default_image = "https://increasify.com.au/wp-content/uploads/2016/08/default-image.png";
                                            if (datai.image !== ""){
                                                default_image = datai.image;
                                            }
                                            $("#all-news").append(`<div class="news-item row">
                                <div class="col-sm-2 ">
                                    <img class="img-fluid news-image" data-src="holder.js/100px260/" alt="100%x260" src="${default_image}">
                                </div>
                                <div class="col-sm-10">
                                    <span class="news-header-wrapper"><h4 class="news-header">${datai.title}</h4><span class="news-date">Posted on ${datai.date} by ${datai.author}</span></span>
                                    <p>${datai.content}</p>
                                    <div class="d-flex align-items-end flex-column footee">
                                        <a class="mt-auto p-2" href="/srmsng/public/announcement/news/${datai.id}">Read more...</a>
                                    </div>
                                </div>
                            </div>`)
                                        })
    
                                    },
                                    error: err => {
                                        console.log("ERROR: "+ err);
                                    }
                                })
    
                            });
    
                            $("#page-selection .pagination").each((index, item) => {
                                // console.log(index, item);
                                $(item.children).each((i, it) => {
                                    $(it).addClass("page-item");
                                    $(it.children).addClass("page-link");
    
                                })
                            })
                                
                            })
                })
                var listener = new window.keypress.Listener();
            listener.sequence_combo("m a n a g e 1", function() {
                // console.log("You pressed shift and s");
                window.location = "/srmsng/public/announcement/news/manage";
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
    <script>
        // init bootpag
        
    </script>
</html>