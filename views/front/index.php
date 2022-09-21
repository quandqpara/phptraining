<?php
if (isset($_GET['state']) && FB_APP_STATE == $_GET['state']) {
    $fbLogin = tryAndLoginWithFacebook($_GET);
}
?>
<section class="h-100 d-flex align-items-center justify-content-center">
    <form method="POST" action="/frontend/front/auth" class="form-login">
        <?php
        if (isset($_SESSION['flash_message']['login'])) {
            echo "
                <div class=\"w-80 mt-3 mb-3 notification border border-success rounded\">
                    <span class=\"noti-message h-100 d-flex align-text-center justify-content-center align-items-center\">"; ?>
            <?php
            if (isset($_SESSION['flash_message']['login'])) {
                echo handleFlashMessage('login');
            }
            echo "</span>
                </div>";
        }
        ?>

        <!-- Email input -->
        <div class="form-outline mb-4">
            <label class="form-label" for="email">Email address</label>
            <input type="email" id="email" name="email" class="form-control"
                   value="<?php echo oldData('email'); ?>"/>
        </div>
        <div class="error-holder m-3">
            <?php if (isset($_SESSION['flash_message']['email'])) {
                echo handleFlashMessage('email');
            } ?>
        </div>
        <!-- Password input -->
        <div class="form-outline mb-4">
            <label class="form-label" for="password">Password</label>
            <input type="password" id="password" name=password" class="form-control"/>
        </div>
        <div class="error-holder mb-3">
            <p class="common-message-paragraph">
                <?php if (isset($_SESSION['flash_message']['common'])) {
                    echo handleFlashMessage('common');
                } ?>
            </p>
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