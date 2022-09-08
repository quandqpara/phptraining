<section class="h-100 d-flex align-items-center justify-content-center">
    <?php
    //check if already logged in
    isLoggedIn();
    ?>
    <div class="button-container">
        <div class="row g-2 align-items-center">
            <div class="col-auto">
                <a href="/admin/index">
                    <button type="button" class="btn btn-primary btn-block mb-4">ADMIN</button>
                </a>
            </div>
            <div class="col-auto">
                <a href="/users/index">
                    <button type="button" class="btn btn-primary btn-block mb-4">USER</button>
                </a>
            </div>
        </div>
    </div>
</section>