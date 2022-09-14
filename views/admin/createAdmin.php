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
                        <a class="dropdown-item" href="/admin/home">Search</a>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item" href="/admin/createPageAdmin">Create</a>
                    </div>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" id="navbarDropdown"
                       data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        User management
                    </a>
                    <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                        <a class="dropdown-item" href="/admin/searchPageUser">Search</a>
                    </div>
                </li>
                <li class="nav-item active">
                    <a class="nav-link" href="/admin/logout">Log out </a>
                </li>
            </ul>
        </div>
    </nav>
</header>
<section class="admin-create-section">
    <div class="w-80 mt-3 mb-3 notification border border-success rounded">
        <span class="noti-message h-100 d-flex align-text-center justify-content-center align-items-center">
            <?php
            if (isset($_SESSION['flash_message']['create'])) {
                echo handleFlashMessage('create');
            }
            //what messages will appear here? create complete?
            ?>
        </span>
    </div>
    <div class="admin-create-title m-2"><span><strong>Admin Create</strong></span></div>
    <div class="create-form-container m-2 border border-dark">
        <form method="POST" action="/admin/createAdmin" class="form-create" enctype="multipart/form-data">

            <!-- Avatar input -->
            <div class="d-flex flex-row form-outline admin-create-item">
                <label class="form-label admin-form-label" for="avatar">Avatar*</label>
                <input type="file" id="avatar" name="avatar" class="form-control"
                       accept="image/png, image/jpg, image/jpeg, image/svg, image/svg"/>
                <div class="error-holder m-3">
                    <?php
                    if (isset($_SESSION['flash_message']['avatar'])) {
                        echo handleFlashMessage('avatar');
                    } ?>
                </div>
            </div>
            <div class="avatar-display border-round">
                <?php
                if (isset($_SESSION['old_data']['avatar'])) {
                    $imagPath = handleOldData('avatar');
                    $correctPath = strstr($imagPath, '/uploads');
                    echo "<img src=\"" . $correctPath . "\">";
                } else {
                    echo "<img src=\"/uploads/avatar/default-user-avatar.png\">";
                }
                ?>
            </div>

            <!-- Name input -->
            <div class="d-flex flex-row form-outline admin-create-item">
                <label class="form-label admin-form-label" for="name">Name*</label>
                <input type="text" id="name" name="name" class="form-control"
                       value="<?php echo oldData('name'); ?>"
                />
                <div class="error-holder m-3">
                    <?php
                    if (isset($_SESSION['flash_message']['name'])) {
                        echo handleFlashMessage('name');
                    } ?>
                </div>
            </div>

            <!-- Email input -->
            <div class="d-flex flex-row form-outline admin-create-item">
                <label class="form-label admin-form-label" for="email">Email*</label>
                <input type="email" id="email" name="email" class="form-control"
                       value="<?php echo oldData('email'); ?>"
                />
                <div class="error-holder m-3">
                    <?php
                    if (isset($_SESSION['flash_message']['email'])) {
                        echo handleFlashMessage('email');
                    } ?>
                </div>
            </div>

            <!-- Password input -->
            <div class="d-flex flex-row form-outline admin-create-item">
                <label class="form-label admin-form-label" for="password">Password*</label>
                <input type="password" id="password" name="password" class="form-control"/>
                <div class="error-holder m-3">
                    <?php
                    if (isset($_SESSION['flash_message']['password'])) {
                        echo handleFlashMessage('password');
                    } ?>
                </div>
            </div>

            <!-- Password Confirm input -->
            <div class="d-flex flex-row form-outline admin-create-item">
                <label class="form-label admin-form-label " for="verify">Password Verify*</label>
                <input type="password" id="verify" name="verify" class="form-control"/>
                <div class="error-holder m-3">
                    <?php
                    if (isset($_SESSION['flash_message']['verify'])) {
                        echo handleFlashMessage('verify');
                    } ?>
                </div>
            </div>

            <!-- Role input -->
            <div class="d-flex flex-row form-outline admin-update-item">
                <label class="form-label admin-form-label" for="role">Role*</label>
                <div>
                    <input type="radio" id="admin" name="role_type" value="1"/>
                    <label class="form-label admin-form-label" for="admin">Admin</label>
                </div>
                <div>
                    <input type="radio" id="superadmin" name="role_type" value="2"/>
                    <label class="form-label admin-form-label" for="superadmin">Super Admin</label>
                </div>
                <div class="error-holder m-3">
                    <?php
                    if (isset($_SESSION['flash_message']['role_type'])) {
                        echo handleFlashMessage('role_type');
                    } ?>
                </div>
            </div>

            <!-- Submit button -->
            <div class="row g-2 align-items-center admin-create-item">
                <div class="col-auto">
                    <button type="reset" class="btn btn-primary btn-block mb-4"> Reset</button>
                </div>
                <div class="col-auto">
                    <button type="submit" class="btn btn-primary btn-block mb-4">
                        Create
                    </button>
                </div>
            </div>
        </form>
    </div>
</section>
