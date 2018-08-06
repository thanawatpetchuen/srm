<?php 
    require($_SERVER['DOCUMENT_ROOT'].'/srmsng/public/cookie_validate_superadmin.php');
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
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.0.13/css/all.css" integrity="sha384-DNOHZ68U8hZfKXOrtjWvjxusGo9WQnrNx2sqG0tfsghAvtVlRW3tvkXWZh58N9jp" crossorigin="anonymous">
    <link href='http://fonts.googleapis.com/css?family=Roboto:400,700,300' rel='stylesheet' type='text/css'>
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.16/css/jquery.dataTables.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <link rel="stylesheet" href="/srmsng/public/css/style.css">
</head>
<body>
    <?php include_once($_SERVER['DOCUMENT_ROOT'].'/srmsng/public/admin/superadmin_nav.php'); ?>
    <main class="container">
        <div class="tabs">
            <div class="tab-item active" id="news-tab">
                News
            </div>
            <div class="tab-item" id="notice-tab">
                Notices
            </div>
        </div>
        
        <div class="tab-content active" id="news-table">
            <div class="input-group page-title">
                <h1 style="margin-bottom:0;">News</h1>
                <a class="btn btn-primary" href="/srmsng/public/announcement/news/postnews">
                    <i class="fa fa-plus"></i> Add News
                </a>
            </div>
            <table id="supertable-news">
                    <thead>
                        <th id="select">Select</th>
                        <th id="id">ID</th>
                        <th id="title">Title</th>
                        <th id="content">Content</th>
                        <th id="author">Author</th>
                        <th id="date">Date</th>
                        <th id="Image">Image</th>
                        <th id="action">Action</th>
                    </thead>
                    <tbody id="maintable-news">
                
                    </tbody>
            </table>
            <button class="btn btn-secondary" id="deleteSelectedNewsBtn">
                <i class="fa fa-trash"></i> <span>Delete Selected</span>
            </button>
        </div>
        <div class="tab-content" id="notice-table">
            <div class="input-group page-title">
                <h1 style="margin-bottom:0;">Notices</h1>
                <a class="btn btn-primary" href="/srmsng/public/announcement/news/notices">
                    <i class="fa fa-plus"></i> Add Notice
                </a>
            </div>
            <table id="supertable-notices">
                    <thead>
                        <th id="select">Select</th>
                        <th id="id">ID</th>
                        <th id="title">Title</th>
                        <th id="date">Date</th>
                        <th id="type">Type</th>
                        <th id="action">Action</th>
                    </thead>
                    <tbody id="maintable-notices">
                
                    </tbody>
            </table>
            <button class="btn btn-secondary" id="deleteSelectedNoticesBtn">
                <i class="fa fa-trash"></i> <span>Delete Selected</span>
            </button>
        </div>
        

    </main>
</body>
    <script src="https://code.jquery.com/jquery-3.3.1.min.js" integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.0/umd/popper.min.js" integrity="sha384-cs/chFZiN24E4KMATLdqdvsezGxaGsi4hLGOzlXwp5UZB1LY//20VyM2taTB4QvJ" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.0/js/bootstrap.min.js" integrity="sha384-uefMccjFJAIv6A+rW+L4AHf99KvxDjWSu1z9VI8SKNVmz4sk7buKt/6v9KI65qnm" crossorigin="anonymous"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/v/dt/dt-1.10.16/fc-3.2.4/r-2.2.1/datatables.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <script src="/srmsng/public/js/table_setup.js"></script>
    <script src="/srmsng/public/js/fetch_ajax.js"></script>
    <script src="/srmsng/public/js/fetch_news.js"></script>
    <script src="/srmsng/public/js/submit.js"></script>

    <script>
        $(document).ready( function () {
            
            // Tabs config
            var setUpSecondTab = false
            $('#news-tab').on('click',function() {
                $('#notice-table').removeClass('active');
                $('#notice-tab').removeClass('active');
                $('#news-table').addClass('active');
                $('#news-tab').addClass('active');
            });

            $('#notice-tab').on('click',function() {
                $('#news-table').removeClass('active');
                $('#news-tab').removeClass('active');
                $('#notice-table').addClass('active');
                $('#notice-tab').addClass('active');
                if (setUpSecondTab === false) {
                    setUpFixed("supertable-notices");
                }
                setUpSecondTab = true;
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
</html>