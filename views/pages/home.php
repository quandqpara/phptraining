<section class = "h-100 d-flex align-items-center justify-content-center">
        <form method="POST" action="model\home.php" class="form-login">
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
            <button type="button" class="btn btn-primary btn-block mb-4 btn-submit">Sign in</button>

            <!-- Register buttons -->
            <div class="text-center">
                <p>or sign up with:</p>
                <button type="button" class="btn btn-link btn-floating mx-1">
                    <i class="fab fa-facebook-f"></i>
                </button>
            </div>
        </form>
</section>