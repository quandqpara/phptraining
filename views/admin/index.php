    <section class="h-100 d-flex flex-column align-items-center justify-content-center">
        <div class="login-container">
            <form method="POST" action="/admin/auth" class="form-login ">
                <!-- Email input -->
                <div class="d-flex flex-column form-outline">
                    <label class="form-label" for="email">Email address</label>
                    <input type="email"
                           id="email"
                           name="email"
                           class="form-control"
                           value="<?php echo oldData('email'); ?>"
                    />
                    <div class="error-holder m-3">
                        <?php  if(isset($_SESSION['flash_message']['email'])){
                            echo handleFlashMessage('email');
                        } ?>
                    </div>
                </div>

                <!-- Password input -->
                <div class="d-flex flex-column form-outline">
                    <label class="form-label" for="password">Password</label>
                    <input type="password" id="password" name="password" class="form-control"/>
                    <div class="error-holder m-3">
                        <?php  if(isset($_SESSION['flash_message']['password'])){
                            echo handleFlashMessage('password');
                        } ?>
                    </div>
                </div>

                <div class="error-holder mb-3">
                    <p class="common-message-paragraph">
                        <?php  if(isset($_SESSION['flash_message']['common'])){
                            echo handleFlashMessage('common');
                        } ?>
                    </p>
                </div>

                <!-- Submit button -->
                <button type="submit" class="btn btn-primary btn-block mb-4 btn-submit">Sign in</button>
            </form>
        </div>
    </section>