<header class="admin-page-header">
    <nav class="navbar navbar-expand-lg navbar-light bg-light float-right">
        <div class="collapse navbar-collapse flex-row-reverse" id="navbarSupportedContent">
            <ul class="navbar-nav mr-auto">
                <li class="nav-item active">
                    <a class="nav-link" href="/management/auth/index">To Admin Login</a>
                </li>
            </ul>
        </div>
    </nav>
</header>
<section class="d-flex align-items-center justify-content-center" style="height: 90vh;">
            <form method="POST" action="/frontend/front/auth" class="form-login">
                <?php
                displayNoticeMessage(array('permission'));
                ?>
                <!-- Email input -->
                <div class="form-outline mb-4">
                    <label class="form-label" for="email">Email address</label>
                    <input type="email" id="email" name="email" class="form-control"
                           value="<?php echo oldData('email'); ?>"/>
                    <span>
                <?php if (isset($_SESSION['flash_message']['email'])) {
                    echo handleFlashMessage('email');
                } ?>
            </span>
                </div>

                <!-- Password input -->
                <div class="form-outline mb-4">
                    <label class="form-label" for="password">Password</label>
                    <input type="password" id="password" name=password" class="form-control"/>
                    <span>
                <?php if (isset($_SESSION['flash_message']['common'])) {
                    echo handleFlashMessage('common');
                } ?>
            </span>
                </div>

                <!-- Submit button -->
                <button type="submit" class="btn btn-primary btn-block mb-4 btn-submit">Sign in</button>

                <!-- Facebook login button -->
                <div class="text-center">
                    <p>or sign up with:</p>
                    <button type="button" class="btn btn-link btn-floating mx-1">
                        <a href="<?php echo getFacebookLoginUrl(); ?>"><i class="fab fa-facebook-f"></i></a>
                    </button>
                </div>
            </form>
</section>