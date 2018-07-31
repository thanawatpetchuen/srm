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
    <link href="http://maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css" rel="stylesheet">
    <link href='http://fonts.googleapis.com/css?family=Roboto:400,700,300' rel='stylesheet' type='text/css'>
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.16/css/jquery.dataTables.css">
    <link rel="stylesheet" href="/srmsng/public/css/style.css">
</head>
<body>
<?php include_once($_SERVER['DOCUMENT_ROOT'].'/srmsng/public/admin/superadmin_nav.php'); ?>
    <main class="container">
        <div class="form-container">
            <form id="add-news-form" enctype="multipart/form-data" method="POST" action="/srmsng/public/announcement/news/api/post">
                <h2>Add News<br/>&nbsp</h2>
                <div class="form-group">
                    <label for="title">
                        <b>Title</b>
                    </label>
                    <input type="text" name="title" class="form-control" placeholder="Enter title" id="title" required>
                </div>
                <div class="form-group">
                    <label for="author">
                        <b>Username</b>
                    </label>
                    <input type="text" placeholder="Enter Username" name="author" read-only class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="content">
                        <b>Content</b>
                    </label>
                    <textarea class="form-control" placeholder="Enter Content" rows="5" id="content" name="content" required></textarea>
                    <!-- <input type="text" class="form-control" placeholder="Enter Content" name="content" id="contentFields" required> -->
                </div>
                <div class="form-group">
                    <label for="fileToUpload">
                        <b>Image</b>
                    </label>
                    <!-- <label class="checkbox-inline">
                            <input type="checkbox" value="" id="select-image">Default Image
                    </label> -->
                    <input type="file" class="form-control" name="fileToUpload" id="fileToUpload">
                </div>
                <div class="form-group">
                    <button type="submit" class="btn btn-primary" id="addNewsBtn">Confirm</button>
                    <a href="/srmsng/public/announcement/">
                        <button type="button" class="btn btn-outline-secondary">Cancel</button>
                    </a>   
                </div>
            </form>
        </div>
    </main>
</body>
    <script src="https://code.jquery.com/jquery-3.3.1.min.js" integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.0/umd/popper.min.js" integrity="sha384-cs/chFZiN24E4KMATLdqdvsezGxaGsi4hLGOzlXwp5UZB1LY//20VyM2taTB4QvJ" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.0/js/bootstrap.min.js" integrity="sha384-uefMccjFJAIv6A+rW+L4AHf99KvxDjWSu1z9VI8SKNVmz4sk7buKt/6v9KI65qnm" crossorigin="anonymous"></script>
	<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.js"></script>
    <script src="/srmsng/public/js/submit.js"></script>
    <script>
        var checkbox = document.getElementById("select-image");
        checkbox.addEventListener( 'change', function() {
            var fileinput = document.getElementById("fileToUpload");
            if(this.checked) {
                // Checkbox is checked..
                fileinput.classList.add("disabled");
            } else {
                fileinput.classList.remove("disabled");
                // Checkbox is not checked..
            }
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