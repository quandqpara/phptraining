<header class="admin-page-header">
    <nav class="navbar navbar-expand-lg navbar-light bg-light float-right">
        <div class="collapse navbar-collapse flex-row-reverse" id="navbarSupportedContent">
            <ul class="navbar-nav mr-auto">
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" id="navbarDropdown"
                       data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        Admin management
                    </a>
                    <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                        <a class="dropdown-item" href="/management/admin/home">Search</a>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item" href="/management/admin/createPageAdmin">Create</a>
                    </div>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" id="navbarDropdown"
                       data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        User management
                    </a>
                    <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                        <a class="dropdown-item" href="/management/admin/searchPageUser">Search</a>
                    </div>
                </li>
                <li class="nav-item active">
                    <a class="nav-link" href="/management/auth/logout">Log out </a>
                </li>
            </ul>
        </div>
    </nav>
</header>
<?php
if (isset($_SESSION['flash_message']['create'])) {
    echo "<div class=\"w-80 mt-3 mb-3 notification border border-success rounded\">
          <span class=\"noti-message h-100 d-flex align-text-center justify-content-center align-items-center\">";
    echo handleFlashMessage('create');
    echo "</span>
                    </div>";
}
?>
<section class="d-flex flex-column align-items-center justify-content-start">
    <div class="outer-container">
        <div class="title mt-3"><strong>My Profile ><span style="color:blue">Create Admin</span></strong></div>
        <form method="POST" action="/management/admin/createAdmin" class="form-update" enctype="multipart/form-data">
            <div class="info-window-container-for-edit-admin">
                <!-- Avatar input -->
                <div class="row">
                    <div class="col-2">
                        <label class="form-label" for="avatar">Avatar*</label>
                    </div>
                    <div class="col-data">
                        <input type="file" id="avatar" name="avatar" class="form-control"
                               accept="image/png, image/jpg, image/jpeg, image/svg, image/svg"/>
                    </div>
                    <div class="col-4">
                            <span class="error-holder col-4">
                                <?php
                                if (isset($_SESSION['flash_message']['avatar'])) {
                                    echo handleFlashMessage('avatar');
                                } ?>
                             </span>
                    </div>
                </div>
                <div class="row">
                    <div class="col-2"></div>
                    <div class="col-4 avatar-display border-round">
                        <?php
                        if (isset($_SESSION['old_data']['avatar'])) {
                            $imagPath = handleOldData('avatar');
                            $correctPath = strstr($imagPath, '/uploads');
                            echo "<img src=\"" . $correctPath . "\">";
                        } else {
                            echo "<img src=\"/uploads/avatar/default-front-avatar.png\">";
                        }
                        ?>
                    </div>
                </div>

                <!-- Name input -->
                <div class="row">
                    <div class="col-2">
                        <label class="form-label" for="name">Name*</label>
                    </div>
                    <div class="col-data">
                        <input type="text" id="name" name="name" class="form-control"
                               value="<?php
                               if (isset($targetAdminToUpdate)) {
                                   echo $targetAdminToUpdate['0']['name'];
                               }
                               ?>"/>
                    </div>
                    <div class="col-4">
                            <span class="error-holder">
                                <?php
                                if (isset($_SESSION['flash_message']['name'])) {
                                    echo handleFlashMessage('name');
                                } ?>
                            </span>
                    </div>
                </div>

                <!-- Email input -->
                <div class="row">
                    <div class="col-2">
                        <label class="form-label" for="email">Email*</label>
                    </div>
                    <div class="col-data">
                        <input type="email" id="email" name="email" class="form-control"
                               value="<?php
                               if (isset($targetAdminToUpdate)) {
                                   echo $targetAdminToUpdate['0']['email'];
                               }
                               ?>"/>
                    </div>
                    <div class="col-4">
                            <span class="error-holder">
                                <?php
                                if (isset($_SESSION['flash_message']['email'])) {
                                    echo handleFlashMessage('email');
                                } ?>
                            </span>
                    </div>
                </div>

                <!-- Password input -->
                <div class="row">
                    <div class="col-2">
                        <label class="form-label" for="password">Password*</label>
                    </div>
                    <div class="col-data">
                        <input type="password" id="password" name="password" class="form-control"/>
                    </div>
                    <div class="col-4">
                            <span class="error-holder">
                                <?php
                                if (isset($_SESSION['flash_message']['password'])) {
                                    echo handleFlashMessage('password');
                                } ?>
                            </span>
                    </div>
                </div>

                <!-- Password Confirm input -->
                <div class="row">
                    <div class="col-2">
                        <label class="form-label col-2 " for="verify">Password Verify*</label>
                    </div>
                    <div class="col-data">
                        <input type="password" id="verify" name="verify" class="col-data form-control"/>
                    </div>
                    <div class="col-4">
                            <span class="error-holder col-4">
                                <?php
                                if (isset($_SESSION['flash_message']['verify_password'])) {
                                    echo handleFlashMessage('verify_password');
                                } ?>
                            </span>
                    </div>
                </div>

                <!-- Role input -->
                <div class="row">
                    <div class="col-2">
                        <label class="form-label" for="role_type">Role*</label>
                    </div>
                    <div class="col-2">
                        <input type="radio" id="admin" name="role_type" value="1"/>
                        <label class="form-label" for="admin">Admin</label>
                    </div>
                    <div class="col-2">
                        <input type="radio" id="superadmin" name="role_type" value="2"/>
                        <label class="form-label" for="superadmin">Super Admin</label>
                    </div>
                    <div class="col-4">
                            <span class="error-holder">
                                <?php
                                if (isset($_SESSION['flash_message']['role_type'])) {
                                    echo handleFlashMessage('role_type');
                                } ?>
                            </span>
                    </div>
                </div>

                <!-- Submit button -->
                <div class="row g-2 align-items-center admin-update-item">
                    <div class="col-auto">
                        <button type="reset" class="btn btn-primary btn-block mb-4">Reset</button>
                    </div>
                    <div class="col-auto">
                        <button type="submit" class="btn btn-primary btn-block mb-4">Create</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</section>
