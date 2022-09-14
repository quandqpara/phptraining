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
    <div class="admin-update-title m-2"><span><strong>Admin Search</strong></a> > <span
                    style="color:blue">EDIT USER</span></span></div>
    <div class="create-form-container m-2 border border-dark">
        <form method="POST" action="/admin/create" class="form-create" enctype="multipart/form-data">

            <!-- search ID -->
            <div class="d-flex flex-row form-outline admin-update-item">
                <label class="form-label admin-form-label" for="id">
                    <strong style="font-weight: bolder">ID</strong>
                </label>
                <p class="input-info">
                    <?php
                    echo $_GET['id'];
                    $_SESSION['flash_message']['update_target']['id'] = $_GET['id'];
                    ?>
                </p>
            </div>

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
                if (isset($targetUserToUpdate['avatar'])) {
                    $imagePath = $targetUserToUpdate['avatar'];
                    $correctPath = strstr($imagePath, '/uploads');
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
                       value="<?php
                       if (isset($targetUserToUpdate)) {
                           echo $targetUserToUpdate['name'];
                       }
                       ?>"/>
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
                       value="<?php
                       if (isset($targetUserToUpdate)) {
                           echo $targetUserToUpdate['email'];
                       }
                       ?>"/>
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
                <label class="form-label admin-form-label " for="verify-password">Password Verify*</label>
                <input type="password" id="verify-password" name="verify-password" class="form-control"/>
                <div class="error-holder m-3">
                    <?php
                    if (isset($_SESSION['flash_message']['verify_password'])) {
                        echo handleFlashMessage('verify_password');
                    } ?>
                </div>
            </div>

            <!-- Status input -->
            <div class="d-flex flex-row form-outline admin-create-item">
                <label class="form-label admin-form-label" for="status">Role*</label>
                <div>
                    <input type="radio" id="active" name="status"/>
                    <label class="form-label admin-form-label" for="active" value="1">Active</label>
                </div>
                <div>
                    <input type="radio" id="banned" name="status"/>
                    <label class="form-label admin-form-label" for="banned" value="2">Banned</label>
                </div>
                <div class="error-holder m-3">
                    <?php
                    if (isset($_SESSION['flash_message']['status'])) {
                        echo handleFlashMessage('status');
                    } ?>
                </div>
            </div>

            <!-- Submit button -->
            <div class="row g-2 align-items-center admin-update-item">
                <div class="col-auto">
                    <button type="reset" class="btn btn-primary btn-block mb-4"> Reset</button>
                </div>
                <div class="col-auto">
                    <button type="submit" class="btn btn-primary btn-block mb-4">Update</button>
                </div>
            </div>
        </form>
    </div>
</section>
