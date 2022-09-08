<header class="admin-page-header">
    <nav class="navbar navbar-expand-lg navbar-light bg-light float-right">
        <div class="collapse navbar-collapse flex-row-reverse" id="navbarSupportedContent">
            <ul class="navbar-nav mr-auto">
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button"
                       data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        Admin management
                    </a>
                    <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                        <a class="dropdown-item" href="#">Search</a>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item" href="#">Create</a>
                    </div>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button"
                       data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        User management
                    </a>
                    <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                        <a class="dropdown-item" href="#">Search</a>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item" href="#">Create</a>
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
    <div class="admin-create-title m-2"><span><strong>Admin Create</strong></span></div>
    <div class="create-form-container m-2 border border-dark">
        <form method="POST" action="/admin/create" class="form-create">

            <!-- Avatar input -->
            <div class="d-flex flex-row form-outline admin-create-item">
                <label class="form-label admin-form-label" for="avatar">Avatar*</label>
                <input type="file" id="avatar" name="avatar" class="form-control"/>
                <div class="error-holder m-3">
                    <?php echo handleFlashMessage('avatar'); ?>
                </div>
            </div>
            <div class="avatar-display border-round">
                <span>
                    <?php ?>
                </span>
            </div>

            <!-- Name input -->
            <div class="d-flex flex-row form-outline admin-create-item">
                <label class="form-label admin-form-label" for="name">Name*</label>
                <input type="text" id="name" name="name" class="form-control"/>
                <div class="error-holder m-3">
                    <?php echo handleFlashMessage('name'); ?>
                </div>
            </div>

            <!-- Email input -->
            <div class="d-flex flex-row form-outline admin-create-item">
                <label class="form-label admin-form-label" for="email">Email*</label>
                <input type="email" id="email" name="email" class="form-control"/>
                <div class="error-holder m-3">
                    <?php echo handleFlashMessage('email'); ?>
                </div>
            </div>

            <!-- Password input -->
            <div class="d-flex flex-row form-outline admin-create-item">
                <label class="form-label admin-form-label" for="password">Password*</label>
                <input type="password" id="password" name="password" class="form-control"/>
                <div class="error-holder m-3">
                    <?php echo handleFlashMessage('password'); ?>
                </div>
            </div>

            <!-- Password Confirm input -->
            <div class="d-flex flex-row form-outline admin-create-item">
                <label class="form-label admin-form-label " for="password-confirm">Password Verify*</label>
                <input type="password" id="password-confirm" name="password-confirm" class="form-control"/>
                <div class="error-holder m-3">
                    <?php echo handleFlashMessage('verify_password'); ?>
                </div>
            </div>

            <!-- Role input -->
            <div class="d-flex flex-row form-outline admin-create-item">
                <label class="form-label admin-form-label" for="role">Role*</label>
                <div>
                    <input type="checkbox" id="admin" name="admin" class="checkbox-round form-control"/>
                    <label class="form-label admin-form-label" for="admin">Admin</label>
                </div>
                <div>
                    <input type="checkbox" id="superadmin" name="superadmin" class="checkbox-round form-control"/>
                    <label class="form-label admin-form-label" for="superadmin">Super Admin</label>
                </div>
                <div class="error-holder m-3">
                    <?php echo handleFlashMessage('role'); ?>
                </div>
            </div>

            <!-- Submit button -->
            <div class="row g-2 align-items-center admin-create-item">
                <div class="col-auto">
                    <button type="reset" class="btn btn-primary btn-block mb-4"> Reset</button>
                </div>
                <div class="col-auto">
                    <button type="reset" class="btn btn-primary btn-block mb-4">
                        <a href="##"></a>
                        Delete
                    </button>
                </div>
            </div>
        </form>
    </div>
</section>
