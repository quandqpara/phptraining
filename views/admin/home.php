<header class="page-header">
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
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
                    <a class="nav-link" href="#">Log out <span class="sr-only">(current)</span></a>
                </li>
            </ul>
        </div>
    </nav>
</header>
<section class="h-100 flex-column">
    <div class="notification"></div>
    <div class="search-box">
        <form method="POST" action="##" class="form-create">
            <!-- Email input -->
            <div class="row g-2 align-items-center">
                <div class="col-auto">
                    <label for="inputEmail" class="col-form-label">Email</label>
                </div>
                <div class="col-auto">
                    <input type="email" id="inputEmail" class="form-control">
                </div>
            </div>

            <!-- Password input -->
            <div class="row g-2 align-items-center">
                <div class="col-auto">
                    <label for="inputName" class="col-form-label">Name</label>
                </div>
                <div class="col-auto">
                    <input type="text" id="inputName" class="form-control">
                </div>
            </div>

            <!-- Buttons -->
            <div class="row g-2 align-items-center">
                <div class="col-auto">
                    <button type="reset" class="btn btn-primary btn-block mb-4">Reset</button>
                </div>
                <div class="col-auto">
                    <button type="submit" class="btn btn-primary btn-block mb-4 btn-submit">Search</button>
                </div>
            </div>
        </form>
    </div>
    <div class="result-box">
        <div class="pagination-cover">
            <nav aria-label="Page navigation example">
                <ul class="pagination">
                    <li class="page-item"><a class="page-link" href="#">Previous</a></li>
                    <li class="page-item"><a class="page-link" href="#">1</a></li>
                    <li class="page-item"><a class="page-link" href="#">2</a></li>
                    <li class="page-item"><a class="page-link" href="#">3</a></li>
                    <li class="page-item"><a class="page-link" href="#">Next</a></li>
                </ul>
            </nav>
        </div>
        <div class="table-cover">
            <table class="result-table">
                <thread>
                    <tr>
                        <th scope="col">ID</th>
                        <th scope="col">Avatar</th>
                        <th scope="col">Name</th>
                        <th scope="col">Email</th>
                        <th scope="col">Role</th>
                        <th scope="col">Action</th>
                    </tr>
                </thread>
                <tbody>
                    <tr>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td>
                            <div class="row g-2 align-items-center">
                                <div class="col-auto">
                                    <button type="reset" class="btn btn-primary btn-block mb-4">
                                        <a href="##"></a>
                                        Edit
                                    </button>
                                </div>
                                <div class="col-auto">
                                    <button type="reset" class="btn btn-primary btn-block mb-4">
                                        <a href="##"></a>
                                        Delete
                                    </button>
                                </div>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</section>