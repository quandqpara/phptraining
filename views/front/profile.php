<header class="admin-page-header">
    <nav class="navbar navbar-expand-lg navbar-light bg-light float-right">
        <div class="collapse navbar-collapse flex-row-reverse" id="navbarSupportedContent">
            <ul class="navbar-nav mr-auto logout-btn">
                <li class="nav-item active">
                    <a class="nav-link" href="/frontend/front/logout"><strong class="logout-text">Log out</strong></a>
                </li>
            </ul>
        </div>
    </nav>
</header>
<section class="user-info-section d-flex flex-column align-items-center justify-content-start overflow-hidden">
    <?php
    displayNoticeMessage(array('login', 'permission', 'require'));
    ?>
    <div class="outer-container">
        <div class="title"><strong>My Profile</strong></div>
        <div class="info-window-container">
            <div class="row">
                <div class="col-data"><strong>ID</strong></div>
                <div class="col">
                    <?php
                    if (isset($_SESSION['session_user']['0']['id'])) {
                        echo $_SESSION['session_user']['0']['id'];
                    } else if (isset($_SESSION['session_user']['id'])) {
                        echo $_SESSION['session_user']['id'];
                    } else {
                        echo 'ID not found';
                    }
                    ?>
                </div>
            </div>
            <div class="row">
                <div class="col-data"><strong>Avatar</strong></div>
                <div class="col">
                    <div class="">
                        <?php
                        if (isset($_SESSION['session_user']['avatar'])) {
                            $imagePath = $_SESSION['session_user']['avatar'];
                            echo "<img src=\"" . $imagePath . "\">";
                        } else if (isset($_SESSION['session_user']['0']['avatar'])) {
                            $imagePath = $_SESSION['session_user']['0']['avatar'];
                            echo "<img src=\"" . $imagePath . "\">";
                        } else {
                            echo "<img src=\"/uploads/avatar/default-front-avatar.png\">";
                        }
                        ?>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-data"><strong>Name</strong></div>
                <div class="col">
                    <?php
                    if (isset($_SESSION['session_user']['0']['name'])) {
                        echo $_SESSION['session_user']['0']['name'];
                    } else if (isset($_SESSION['session_user']['name'])) {
                        echo $_SESSION['session_user']['name'];
                    } else {
                        echo 'Name not found';
                    }
                    ?>
                </div>
            </div>
            <div class="row">
                <div class="col-data"><strong>Email</strong></div>
                <div class="col">
                    <?php
                    if (isset($_SESSION['session_user']['0']['email'])) {
                        echo $_SESSION['session_user']['0']['email'];
                    } else if (isset($_SESSION['session_user']['email'])) {
                        echo $_SESSION['session_user']['email'];
                    } else {
                        echo 'email not found';
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>
</section>