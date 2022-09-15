<header class="admin-page-header">
    <nav class="navbar navbar-expand-lg navbar-light bg-light float-right">
        <div class="collapse navbar-collapse flex-row-reverse" id="navbarSupportedContent">
            <ul class="navbar-nav mr-auto">
                <li class="nav-item active">
                    <a class="nav-link" href="/user/logout">Log out </a>
                </li>
            </ul>
        </div>
    </nav>
</header>

<section class="h-100 d-flex flex-column align-items-center justify-content-start">
    <div class="w-80 d-flex mt-3 mb-3 user-profile-noti border border-success rounded align-items-start ">
        <span class="h-100 d-flex align-text-center justify-content-center align-items-center">
            <?php
            if (isset($_SESSION['flash_message']['login'])) {
                echo handleFlashMessage('login');
            }
            //what messages will appear here? login, search complete?
            ?>
        </span>
    </div>
    <div class="outer-container">
        <div class="title"></div>
        <div class="info-window"></div>
    </div>
</section>