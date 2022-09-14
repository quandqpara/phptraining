<header class="admin-page-header">
    <nav class="navbar navbar-expand-lg navbar-light bg-light float-right">
        <div class="collapse navbar-collapse flex-row-reverse" id="navbarSupportedContent">
            <ul class="navbar-nav mr-auto">
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" id="navbarDropdown"
                       data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        Admin management
                    </a>
                    <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                        <a class="dropdown-item" href="/admin/home">Search</a>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item" href="/admin/createPageAdmin">Create</a>
                    </div>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" id="navbarDropdown"
                       data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        User management
                    </a>
                    <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                        <a class="dropdown-item" href="/admin/searchPageUser">Search</a>
                    </div>
                </li>
                <li class="nav-item active">
                    <a class="nav-link" href="/admin/logout">Log out </a>
                </li>
            </ul>
        </div>
    </nav>
</header>
<section class="h-100 w-100 flex-column mb-auto admin-home-sect">
    <div class="w-80 mt-3 mb-3 notification border border-success rounded">
        <span class="noti-message h-100 d-flex align-text-center justify-content-center align-items-center">
            <?php
            if (isset($_SESSION['flash_message']['login'])) {
                echo handleFlashMessage('login');
            }
            if (isset($_SESSION['flash_message']['search'])) {
                echo handleFlashMessage('search');
            }
            if (isset($_SESSION['flash_message']['update'])) {
                echo handleFlashMessage('update');
            }
            if (isset($_SESSION['flash_message']['permission'])) {
                echo handleFlashMessage('permission');
            }
            if (isset($_SESSION['flash_message']['id'])) {
                echo handleFlashMessage('id');
            }
            if (isset($_SESSION['flash_message']['delete'])) {
                echo handleFlashMessage('delete');
            }
            //what messages will appear here? login, search complete?
            ?>
        </span>
    </div>
    <div class="mt-3 mb-3 search-box border border-dark">
        <form method="GET" action="/admin/searchAdmin" class=" m-4 form-create">
            <!-- Email input -->
            <div class="row g-2 align-items-center mb-3 mt-3">
                <div class="col-auto m-3">
                    <label for="email" class="col-form-label">Email</label>
                </div>
                <div class="col-auto m-3">
                    <input type="text"
                           id="email"
                           name="email"
                           class="form-control"
                           value="<?php echo oldData('email'); ?>"
                    />
                </div>
                <div class="error-holder m-3">
                    <?php if (isset($_SESSION['flash_message']['email'])) {
                        echo handleFlashMessage('email');
                    } ?>
                </div>
            </div>

            <!-- Name input -->
            <div class="row g-2 align-items-center mb-3 mt-3">
                <div class="col-auto m-3">
                    <label for="name" class="col-form-label">Name</label>
                </div>
                <div class="col-auto m-3">
                    <input type="text"
                           id="name"
                           name="name"
                           class="form-control"
                           value="<?php echo oldData('name'); ?>"
                    />
                </div>
                <div class="error-holder m-3">
                    <?php if (isset($_SESSION['flash_message']['name'])) {
                        echo handleFlashMessage('name');
                    } ?>
                </div>
            </div>

            <!-- Buttons -->
            <div class="d-flex justify-content-between row g-2 align-items-center">
                <div class="col-auto">
                    <button type="reset" class="reset-button btn btn-primary btn-block mb-4">Reset</button>
                </div>
                <div class="col-auto">
                    <button type="submit" class="btn btn-primary btn-block mb-4 btn-submit">Search</button>
                </div>
            </div>
        </form>
    </div>
    <div class="d-flex flex-column result-container mb-2 mt-2 p-3 border border-dark">
        <div class="pagination-cover flex-row-reverse m-2">
            <nav aria-label="Page navigation example" class="page-nav">
                <?php
                $reloadUrl = ltrim(strstr($_SERVER['REQUEST_URI'], '?'), '?');

                //remove duplicate params
                $correctingUrl = explode("&", $reloadUrl);
                $reloadUrl = "";
                if (!isset($correctingUrl)) {
                    foreach ($correctingUrl as $param) {
                        $temp = explode("=", $param);
                        $key = $temp[0];
                        $value = $temp[1];
                        if (!str_contains($reloadUrl, $key)) {
                            $reloadUrl .= $key . "=" . $value . "&";
                        }
                    }
                }
                $href = rtrim("/admin/searchAdmin?" . $reloadUrl, '&');

                //print Pagination
                if (!empty($data)) {
                    $pageLink = "<ul class='pagination'>";
                    $pageLink .= "<li class='page-item'><a class='page-link' href='" . $href . "&page=" . $data['pagination']['prev'] . "'>Previous</a></li>";
                    for ($i = 1; $i <= $data['pagination']['totalPages']; $i++) {
                        $pageLink .= "<li class='page-item'><a class='page-link' href='" . $href . "&page=" . $i . "'>" . $i . "</a></li>";
                    }
                    $pageLink .= "<li class='page-item'><a class='page-link' href='" . $href . "&page=" . $data['pagination']['next'] . "'>Next</a></li>";
                    echo $pageLink . "</ul>";
                }
                ?>
            </nav>
        </div>

        <div class="table-cover border border-dark">
            <table class="result-table table table-striped table-bordered table-hover">
                <thread class="thead-dark">
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
                <?php
                if (!isset($data['data']) || count($data['data']) == 0) {
                    $searchTable = "<tr>";
                    $searchTable .= "<td colspan='6'><span>No Results Found!</span></td>";
                    echo $searchTable . "</tr>";
                } else {
                    foreach ($data['data'] as $result) {
                        $searchTable = "<tr>";
                        $searchTable .= "<td>" . $result['id'] . "</td>";

                        $imagePath = $result['avatar'];
                        $correctPath = '';
                        if (!empty($imagePath)) {
                            $correctPath = strstr($imagePath, '/uploads');
                            $correctPath = "<img class= src=\"" . $correctPath . "\">";
                        } else if (empty($imagePath)) {
                            $correctPath = "<img src=\"/uploads/avatar/default-user-avatar.png\">";
                        }
                        $searchTable .= "<td>" . $correctPath . "</td>";

                        $searchTable .= "<td>" . $result['name'] . "</td>";
                        $searchTable .= "<td>" . $result['email'] . "</td>";

                        $role = '';
                        if (!empty($result['role_type'])) {
                            $role = $result['role_type'];
                            switch ($role) {
                                case 1:
                                    $role = 'Admin';
                                    break;
                                case 2:
                                    $role = 'Super Admin';
                                    break;
                            }
                        }
                        $searchTable .= "<td>" . $role . "</td>";

                        $searchTable .= " <td>
                        <div class=\"row g-2 align-items-center\">
                            <div class=\"col-auto\">
                                    <a class=\"disguised-button edit-btn\" href=\"/admin/editPageAdmin?id=" . $result['id'] . "\">Edit</a> 
                            </div>
                            <div class=\"col-auto\">
                                    <a  class=\"disguised-button delete-btn confirmation\" 
                                        href=\"/admin/deleteAdmin?id=" . $result['id'] . "\"
                                        onclick=\"return confirm('Are you sure?')\"
                                    >
                                        Delete
                                    </a>
                            </div>
                        </div>
                    </td>";
                        echo $searchTable . "</tr>";
                    }
                }
                ?>
                </tbody>
            </table>
        </div>
    </div>

</section>