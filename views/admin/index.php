<section class = "h-100 d-flex align-items-center justify-content-center">
        <div class="error-message-box">
            <p>
                <?php
                    $number_error = handleFlashMessage('number_of_error');
                    $list_messages = implode('<br>',handleFlashMessage($_SESSION['flash_message']));

                ?>
            </p>
        </div>
        <form method="POST" action="/admin/auth" class="form-login">
            <!-- Email input -->
            <div class="form-outline mb-4">
                <label class="form-label" for="email">Email address</label>
                <input type="email"
                       id="email"
                       name="email"
                       class="form-control"
                       value="<?php echo oldData('email') ?>"
                />
            </div>

            <!-- Password input -->
            <div class="form-outline mb-4">
                <label class="form-label" for="password">Password</label>
                <input type="password" id="password" name="password" class="form-control"/>
            </div>

            <!-- Submit button -->
            <button type="submit" class="btn btn-primary btn-block mb-4 btn-submit">Sign in</button>
        </form>
</section>