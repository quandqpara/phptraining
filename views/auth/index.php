<header class="admin-page-header">
    <nav class="navbar navbar-expand-lg navbar-light bg-light float-right">
        <div class="collapse navbar-collapse flex-row-reverse" id="navbarSupportedContent">
            <ul class="navbar-nav mr-auto">
                <li class="nav-item active">
                    <a class="nav-link" href="/frontend/front/index">To User Login</a>
                </li>
            </ul>
        </div>
    </nav>
</header>
<section class="d-flex flex-column align-items-center justify-content-center section-container" style="height: 90vh;">
    <?php
    error_reporting(E_ERROR | E_PARSE);
    isLoggedIn();
    displayNoticeMessage(array('login', 'permission', 'logout', 'common'));
    ?>
    <div class="login-container">
        <form method="POST" action="/management/auth/login" class="form-login ">
            <!-- Email input -->
            <div class="d-flex flex-column form-outline">
                <label class="form-label" for="email">Email address</label>
                <input type="email"
                       id="email"
                       name="email"
                       class="form-control"
                       value="<?php echo oldData('email'); ?>"
                />
                <span class="error-holder m-3">
                    <?php if (isset($_SESSION['flash_message']['email'])) {
                        echo handleFlashMessage('email');
                    } ?>
                </span>
            </div>

            <!-- Password input -->
            <div class="d-flex flex-column form-outline">
                <label class="form-label" for="password">Password</label>
                <input type="password" id="password" name="password" class="form-control"/>
                <div class="error-holder m-3">
                    <?php if (isset($_SESSION['flash_message']['password'])) {
                        echo handleFlashMessage('password');
                    } ?>
                </div>
            </div>

            <!-- Submit button -->
            <button type="submit" class="btn btn-primary btn-block mb-4 btn-submit">Sign in</button>
        </form>
    </div>
</section>