<nav class="navbar navbar-expand-lg navbar-light bg-light fixed-top">
    <a class="navbar-brand" href="/srmsng/public/announcement">
        <img src="/srmsng/public/image/logo/logo.png" class="nav-logo" height="50" alt="">
    </a>
    <span class="nav-item" id="nav-logo-title">Service Request Management</span>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav justify-content-end ml-auto">
            <li class="nav-item active">
                <a class="nav-link">
                    <i class="fa fa-user"></i>
                    <span id="username"><?php echo $_SESSION['username_unhash']?></span>
                </a>
            </li>
            <ul class="navbar-nav">
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    Menu
                    </a>
                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                        <a class="dropdown-item" href="/srmsng/public/customer">My Assets</a>
                        <a class="dropdown-item" href="/srmsng/public/customer/ticket">Request History</a>
                        <div class="dropdown-divider"></div>
                        <h6 class="dropdown-header">Account Management</h6>
                        <a href="/srmsng/public/customer/passwordreset" class="dropdown-item">Password Reset</a>
                        <!-- <a href="/srmsng/public/system/log" class="dropdown-item">System Log</a> -->
                    </div>
                </li>
            </ul>
            <li class="nav-item">
                <a class="nav-link" href="#" id="logoutBtn">
                    <i class="fa fa-sign-out"></i>
                    <span>Logout</span>
                </a>
            </li>
  
        </ul>
    </div>
</nav>