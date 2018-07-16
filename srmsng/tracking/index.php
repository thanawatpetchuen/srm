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
                <img src="../src/image/sng.png" alt="Avatar" class="avatar">
            </div>
            <div>
                <label>ID: <span id="id"></span></label><br>
                <label>Lat: <span id="lat"></span></label><br>
                <label>Lon: <span id="lon"></span></label><br>
                <label>Time: <span id="time"></span></label><br>
                <!-- <div id="time">
                </div>
                <div id="lat"></div>
                <div id="lon"></div> -->
                <!-- <div id="status"></div> -->
                <br/>
                <a href="/srmsng/public/login">
                    <button class="btn btn-primary">
                        Go Home
                    </button>
                </a>
            </div>
        </div>

    </div>
    <script src="https://code.jquery.com/jquery-3.3.1.min.js" integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=" crossorigin="anonymous"></script>
    <!-- Firebase App is always required and must be first -->
    <script src="https://www.gstatic.com/firebasejs/5.1.0/firebase-app.js"></script>

    <!-- Add additional services that you want to use -->
    <!-- <script src="https://www.gstatic.com/firebasejs/5.1.0/firebase-auth.js"></script> -->
    <script src="https://www.gstatic.com/firebasejs/5.1.0/firebase-database.js"></script>
    <!-- <script src="https://www.gstatic.com/firebasejs/5.1.0/firebase-firestore.js"></script> -->
    <!-- <script src="https://www.gstatic.com/firebasejs/5.1.0/firebase-messaging.js"></script> -->
    <script src="https://www.gstatic.com/firebasejs/5.1.0/firebase-functions.js"></script>

    <!-- Comment out (or don't include) services that you don't want to use -->
    <!-- <script src="https://www.gstatic.com/firebasejs/5.1.0/firebase-storage.js"></script> -->

    <script>
     var config = {
        apiKey: "AIzaSyA9PZM0cHmzEm7LOEBB_coeCZpNOLI7aC4",
        authDomain: "srm-tracking-system.firebaseapp.com",
        databaseURL: "https://srm-tracking-system.firebaseio.com",
        projectId: "srm-tracking-system",
        storageBucket: "srm-tracking-system.appspot.com",
        messagingSenderId: "28002227602"
    };
    firebase.initializeApp(config);
    </script>
    <!-- <script src="tracking.js"></script> -->
    <script src="consumer.js"></script>
</body>

</html>