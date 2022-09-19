<header class="admin-page-header">
    <nav class="navbar navbar-expand-lg navbar-light bg-light float-right">
        <div class="collapse navbar-collapse flex-row-reverse" id="navbarSupportedContent">
            <ul class="navbar-nav mr-auto">
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" id="navbarDropdown"
                       data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        User management
                    </a>
                    <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                        <a class="dropdown-item" href="/management/user/searchPageUser">Search</a>
                    </div>
                </li>
                <li class="nav-item active">
                    <a class="nav-link" href="/management/auth/logout">Log out </a>
                </li>
            </ul>
        </div>
    </nav>
</header>
<section class="h-100 w-100 flex-column mb-auto admin-home-sect">
    <?php
    $acceptableMessage = array('login', 'search', 'update', 'id', 'permission', 'delete', 'permission');
    foreach ($_SESSION['flash_message'] as $key => $value) {
        if (in_array($key, $acceptableMessage)) {
            if (isset($_SESSION['flash_message'][$key])) {
                echo "
                            <div class=\"w-80 mt-3 mb-3 notification border border-success rounded\">
                            <span class=\"noti-message h-100 d-flex align-text-center justify-content-center align-items-center\">"; ?>
                <?php
                if (isset($_SESSION['flash_message'][$key])) {
                    echo handleFlashMessage($key);
                }
                echo "</span>
                    </div>";
            }
        }
    }
    ?>
    <div class="mt-3 mb-3 search-box border border-dark">
        <form method="GET" action="/management/user/searchUser" class=" m-4 form-create">
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
                           value="<?php
                           if (isset($_SESSION['old_data']['email'])) {
                               echo oldData('email');
                           }
                           ?>"
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
                           value="<?php
                           if (isset($_SESSION['old_data']['name'])) {
                               echo oldData('name');
                           }
                           ?>"
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
                error_reporting(E_ERROR | E_PARSE);
                loadPaginator($data);
                ?>
            </nav>
        </div>

        <div class="table-cover border border-dark">
            <table class="result-table table table-striped table-bordered table-hover">
                <thread class="thead-dark">
                    <tr>
                        <th class="fathread-column" scope="col" onclick="sortTable(0)">ID <i class="fa fa-sort"
                                                                                             style="font-size:20px"></i>
                        </th>
                        <th scope="col">Avatar</th>
                        <th class="thread-column" scope="col" onclick="sortTable(1)">Name <i class="fa fa-sort"
                                                                                             style="font-size:20px"></i>
                        </th>
                        <th class="thread-column" scope="col" onclick="sortTable(2)">Email <i class="fa fa-sort"
                                                                                              style="font-size:20px"></i>
                        </th>
                        <th class="thread-column" scope="col" onclick="sortTable(3)">Role <i class="fa fa-sort"
                                                                                             style="font-size:20px"></i>
                        </th>
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
                        if (!empty($imagePath)) {
                            $correctPath = "<img src=\"" . $imagePath . "\">";
                        } else if (empty($imagePath)) {
                            $correctPath = "<img src=\"/uploads/avatar/default-front-avatar.png\">";
                        }
                        $searchTable .= "<td>" . $correctPath . "</td>";

                        $searchTable .= "<td>" . $result['name'] . "</td>";
                        $searchTable .= "<td>" . $result['email'] . "</td>";

                        $status = '';
                        if (!empty($result['status'])) {
                            switch ($result['status']) {
                                case '1':
                                    $status = 'Active';
                                    break;
                                case '2':
                                    $status = 'Banned';
                                    break;
                            }
                        }

                        $searchTable .= "<td>" . $status . "</td>";

                        $searchTable .= " <td>
                        <div class=\"row g-2 align-items-center\">
                            <div class=\"col-auto\">
                                    <a class=\"disguised-button edit-btn\" href=\"/management/user/editPageUser?id=" . $result['id'] . "\">Edit</a> 
                            </div>
                            <div class=\"col-auto\">
                                    <a  class=\"disguised-button delete-btn confirmation\" 
                                        href=\"/management/user/deleteUser?id=" . $result['id'] . "\"
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