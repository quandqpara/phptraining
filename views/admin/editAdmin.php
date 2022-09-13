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
                        <a class="dropdown-item" href="/admin/userSearchPage">Search</a>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item" href="/admin/userCreatePage">Create</a>
                    </div>
                </li>
                <li class="nav-item active">
                    <a class="nav-link" href="/admin/logout">Log out </a>
                </li>
            </ul>
        </div>
    </nav>
</header>
<section class="admin-update-section">
    <div class="admin-update-title m-2"><span><strong>Admin Search</strong></a> > <span style="color:blue">EDIT ADMIN</span></span></div>
    <div class="update-form-container m-2 border border-dark">
        <form method="POST" action="/admin/editAdmin" class="form-update" enctype="multipart/form-data">

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
            <div class="d-flex flex-row form-outline admin-update-item">
                <label class="form-label admin-form-label" for="avatar">Avatar*</label>
                <input type="file" id="avatar" name="avatar" class="input-info form-control"/>
                <div class="error-holder m-3">
                    <?php
                    if(isset($_SESSION['flash_message']['avatar'])){
                        echo handleFlashMessage('avatar');
                    }
                    ?>
                </div>
            </div>
            <div class="input-info avatar-display border-round">
                <span>
                    <?php
                    $imagePath = '';
                    echo '<img src='.$imagePath.'';
                    ?>
                </span>
            </div>

            <!-- Name input -->
            <div class="d-flex flex-row form-outline admin-update-item">
                <label class="form-label admin-form-label" for="name">Name*</label>
                <input type="text" id="name" name="name" class="input-info form-control"/>
                <div class="error-holder m-3">
                    <?php
                    if(isset($_SESSION['flash_message']['name'])){
                        echo handleFlashMessage('name');
                    } ?>
                </div>
            </div>

            <!-- Email input -->
            <div class="d-flex flex-row form-outline admin-update-item">
                <label class="form-label admin-form-label" for="email">Email*</label>
                <input type="email" id="email" name="email" class="input-info form-control"/>
                <div class="error-holder m-3">
                    <?php
                    if(isset($_SESSION['flash_message']['email'])){
                        echo handleFlashMessage('email');
                    } ?>
                </div>
            </div>

            <!-- Password input -->
            <div class="d-flex flex-row form-outline admin-update-item">
                <label class="form-label admin-form-label" for="password">Password</label>
                <input type="password" id="password" name="password" class="input-info form-control"/>
                <div class="error-holder m-3">
                    <?php
                    if(isset($_SESSION['flash_message']['password'])){
                        echo handleFlashMessage('password');
                    } ?>
                </div>
            </div>

            <!-- Password Confirm input -->
            <div class="d-flex flex-row form-outline admin-update-item">
                <label class="form-label admin-form-label " for="verify">Password Verify</label>
                <input type="password" id="verify" name="verify" class="input-info form-control"/>
                <div class="error-holder m-3">
                    <?php
                    if(isset($_SESSION['flash_message']['verify'])){
                        echo handleFlashMessage('verify');
                    } ?>
                </div>
            </div>

            <!-- Role input -->
            <div class="d-flex flex-row form-outline admin-update-item">
                <label class="form-label admin-form-label" for="role">Role*</label>
                <div>
                    <input type="radio" id="admin" name="role_type" value="1"/>
                    <label class="form-label admin-form-label" for="admin" >Admin</label>
                </div>
                <div>
                    <input type="radio" id="superadmin" name="role_type" value="2"/>
                    <label class="form-label admin-form-label" for="superadmin" >Super Admin</label>
                </div>
                <div class="error-holder m-3">
                    <?php
                    if(isset($_SESSION['flash_message']['role_type'])){
                        echo handleFlashMessage('role_type');
                    } ?>
                </div>
            </div>

            <!-- Submit button -->
            <div class="row g-2 align-items-center admin-update-item">
                <div class="col-auto">
                    <button type="reset" class="btn btn-primary btn-block mb-4"> Reset</button>
                </div>
                <div class="col-auto">
                    <button type="submit" class="btn btn-primary btn-block mb-4">
                        Update
                    </button>
                </div>
            </div>
        </form>
    </div>
</section>
<?php showLog($_SESSION);?>