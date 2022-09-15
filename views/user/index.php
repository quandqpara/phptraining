<?php
//    session_unset();
//    showLog($_SESSION, true);
    if(isset($_GET['state']) && FB_APP_STATE == $_GET['state']) {
        $fbLogin = tryAndLoginWithFacebook($_GET);
    }

    isLoggedIn();
?>
<section class="h-100 d-flex align-items-center justify-content-center">
    <form method="POST" action="" class="form-login">
        <!-- Email input -->
        <div class="form-outline mb-4">
            <label class="form-label" for="email">Email address</label>
            <input type="email" id="email" class="form-control"/>
        </div>

        <!-- Password input -->
        <div class="form-outline mb-4">
            <label class="form-label" for="password">Password</label>
            <input type="password" id="password" class="form-control"/>
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