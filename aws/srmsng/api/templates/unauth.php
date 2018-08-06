<?php
    
?>
<html>

<head>

    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" integrity="sha384-WskhaSGFgHYWDcbwN70/dfYBj47jz9qbsMId/iRN3ewGhXQFZCSftd1LZCfmhktB"
        crossorigin="anonymous">

    <style>
        .container {
            align-items: center;
            display: flex;
            height: 100vh;
            width: 100vw;
        }

        .avatar {
            margin: auto;
            width: 150px;
        }

        .wrapper {
            display: flex;
            align-items: center;
            margin: auto;
        }

        .imgcontainer {
            padding-right: 40px;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="wrapper">
            <div class="imgcontainer">
                <img src="/srmsng/src/image/sng.png" alt="Avatar" class="avatar">
            </div>
            <div>
                <h1>401</h1>
                <h4>You are unauthorized!</h4>
                <h4>Please login to see this page.</h4>
                <br/>
                <a href="/srmsng/public/login">
                    <button class="btn btn-primary">
                        Go to Login page
                    </button>
                </a>
            </div>
        </div>

    </div>

</body>

</html>