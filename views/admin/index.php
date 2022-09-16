<section class="h-100 d-flex flex-column align-items-center justify-content-center">
    <div class="login-container">
        <form method="POST" action="/admin/auth" class="form-login ">
            <?php
            if(isset($_SESSION['flash_message']['login'])){
                echo "
                <div class=\"w-80 mt-3 mb-3 notification border border-success rounded\">
                    <span class=\"noti-message h-100 d-flex align-text-center justify-content-center align-items-center\">"; ?>
                <?php
                if (isset($_SESSION['flash_message']['login'])) {
                    echo handleFlashMessage('login');
                }
                if (isset($_SESSION['flash_message']['common'])) {
                    echo handleFlashMessage('common');
                }
                echo "</span>
                </div>";
            }
            ?>

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