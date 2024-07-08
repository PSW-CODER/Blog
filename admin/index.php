<?php
  session_start();
  require '../_classes/Auth.php';
  require '../_classes/Database/PostTable.php';
  require '../_classes/common.php';
  require '../_classes/escape.php';

  use _classes\Auth;
  use _classes\Database\PostTable;
  use _classes\Database\MySQL;

  $user = Auth::check();

  if($user->role_id !== 1){
    echo"
      <script>
        alert('You cannot access.');
        window.location.href = '../_actions/logout.php';
      </script>
    ";
  }

  $postTable = new PostTable(new MySQL());
  $totalPost = $postTable->getAll();

  // Pagination

  $start = 0;
  $rowsPerPage = 5;
  $totalPages = ceil(count($totalPost) / $rowsPerPage);

  if(isset($_POST['search'])){
    setcookie('search', $_POST['search'], time() + (86400 * 30), '/');
  }else{
    if(!isset($_GET['page-no'])){
      unset($_COOKIE['search']);
      setcookie('search', '', -1, '/');
    }
  }

  if(isset($_POST['search']) || isset($_COOKIE['search'])){
    isset($_POST['search']) ? $searchKey = $_POST['search'] : $searchKey = $_COOKIE['search'];
    $searchPost = $postTable->getSearchPost($searchKey);
    $totalPages = ceil(count($searchPost) / $rowsPerPage);
    
    if(isset($_GET['page-no'])){
      $start = ($_GET['page-no'] - 1) * $rowsPerPage;
      $posts = $postTable->getSearch($searchKey, $start, $rowsPerPage);
    }else{
      $posts = $postTable->getSearch($searchKey, $start, $rowsPerPage);
    }
  }else{
    if(isset($_GET['page-no'])){
      $start = ($_GET['page-no'] - 1) * $rowsPerPage;
      $posts = $postTable->pagination($start, $rowsPerPage); 
    }else{
      $posts = $postTable->pagination($start, $rowsPerPage);
    }
  }

  if(isset($_GET['page-no'])){
    $id = $_GET['page-no'];
  }else{
    $id = 1;
  }
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title>BlogAdmin</title>
  <meta content="" name="description">
  <meta content="" name="keywords">

  <!-- Favicons -->
  <link href="../assets/img/favicon.png" rel="icon">
  <link href="../assets/img/apple-touch-icon.png" rel="apple-touch-icon">

  <!-- Google Fonts -->
  <link href="https://fonts.gstatic.com" rel="preconnect">
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Nunito:300,300i,400,400i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">

  <!-- Vendor CSS Files -->
  <link href="../assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="../assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
  <link href="../assets/vendor/boxicons/css/boxicons.min.css" rel="stylesheet">
  <link href="../assets/vendor/quill/quill.snow.css" rel="stylesheet">
  <link href="../assets/vendor/quill/quill.bubble.css" rel="stylesheet">
  <link href="../assets/vendor/remixicon/remixicon.css" rel="stylesheet">
  <link href="../assets/vendor/simple-datatables/style.css" rel="stylesheet">

  <!-- Template Main CSS File -->
  <link href="../assets/css/style.css" rel="stylesheet">

  <!-- =======================================================
  * Template Name: NiceAdmin
  * Template URL: https://bootstrapmade.com/nice-admin-bootstrap-admin-html-template/
  * Updated: Apr 20 2024 with Bootstrap v5.3.3
  * Author: BootstrapMade.com
  * License: https://bootstrapmade.com/license/
  ======================================================== -->
</head>

<body id="<?= $id ?>">

  <!-- ======= Header ======= -->
  <header id="header" class="header fixed-top d-flex align-items-center">

    <div class="d-flex align-items-center justify-content-between">
      <a href="index.html" class="logo d-flex align-items-center">
        <img src="assets/img/logo.png" alt="">
        <span class="d-none d-lg-block">Blog<span class="text-secondary">Admin</span></span>
      </a>
      <i class="bi bi-list toggle-sidebar-btn"></i>
    </div><!-- End Logo -->

    <div class="search-bar ms-auto">
      <form class="search-form d-flex align-items-center" method="post" action="">
        <input type="hidden" name="_token" value="<?= escape($_SESSION['_token']) ?>">
        <input type="text" name="search" placeholder="Search" title="Enter search keyword">
        <button type="submit" title="Search"><i class="bi bi-search"></i></button>
      </form>
    </div><!-- End Search Bar -->

    <nav class="header-nav ms-auto">
      <ul class="d-flex align-items-center">

        <li class="nav-item d-block d-lg-none">
          <a class="nav-link nav-icon search-bar-toggle " href="#">
            <i class="bi bi-search"></i>
          </a>
        </li><!-- End Search Icon-->

        <li class="nav-item dropdown pe-3">

          <a class="nav-link nav-profile d-flex align-items-center pe-0" href="#" data-bs-toggle="dropdown">
            <img src="../assets/img/<?= escape($user->image) ?>" alt="Profile" class="rounded-circle border border-2">
            <span class="d-none d-md-block dropdown-toggle ps-2"><?= escape($user->name) ?></span>
          </a><!-- End Profile Iamge Icon -->

          <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow profile">
            <li class="dropdown-header">
              <h6><?= escape($user->name) ?></h6>
              <span>Admin</span>
            </li>
            <li>
              <hr class="dropdown-divider">
            </li>
            <li>
              <a class="dropdown-item d-flex align-items-center" href="../_actions/logout.php">
                <i class="bi bi-box-arrow-right"></i>
                <span>Sign Out</span>
              </a>
            </li>

          </ul><!-- End Profile Dropdown Items -->
        </li><!-- End Profile Nav -->

      </ul>
    </nav><!-- End Icons Navigation -->

  </header><!-- End Header -->

  <!-- ======= Sidebar ======= -->
  <aside id="sidebar" class="sidebar">

    <ul class="sidebar-nav" id="sidebar-nav">

      <li class="nav-item">
        <a class="nav-link" href="index.php">
          <i class="bi bi-grid"></i>
          <span>Blogs</span>
        </a>
      </li><!-- End Dashboard Nav -->

      <li class="nav-item">
        <a class="nav-link collapsed" href="user-list.php">
          <i class="bi bi-people"></i>
          <span>Users</span>
        </a>
      </li><!-- End Profile Page Nav -->

      <li class="nav-item">
        <a class="nav-link collapsed" href="../_actions/logout.php">
          <i class="bi bi-box-arrow-right"></i>
          <span>Sign Out</span>
        </a>
      </li><!-- End Register Page Nav -->
    </ul>

  </aside><!-- End Sidebar-->

  <main id="main" class="main">

    <div class="pagetitle">
      <h1>Blog</h1>
    </div><!-- End Page Title -->

    <div class="section">
      <div class="row">

        <div class="col">
          <!-- action button -->
          <div class="action-btn text-end">
            <a href="create-post.php" class="btn btn-primary mb-2">Create Post</a>
          </div>

          <!-- table -->
          <div class="card">
            <div class="card-body p-4">
              <div class="table-responsive">
                <?php
                  if(!$posts){
                ?>
                  <p class="text-center card-title m-0">No Found Blog</p>
                <?php
                  }else{
                ?>
                  <table class="blog-table table table-striped align-middle">
                    <thead>
                      <tr>
                        <th scope="col">#</th>
                        <th scope="col">Title</th>
                        <th scope="col">Content</th>
                        <th scope="col">Author</th>
                        <th scope="col">Date</th>
                        <th scope="col">Actions</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php 
                        $i = 1;
                        foreach ($posts as $post) {
                        ?>
                          <tr>
                            <th scope="row"><?= escape($i) ?></td>
                            <td><?= escape($post->title) ?></td>
                            <td><?= substr(escape($post->content), 0, 50) ?>...</td>
                            <td><?= escape($post->author) ?></td>
                            <td><?= escape(date('Y-m-d', strtotime($post->created_at))) ?></td>
                            <td>
                              <div class="d-flex gap-1">
                                <a href="edit-post.php?post-id=<?= escape($post->id) ?>" class="btn btn-sm btn-primary"><i class="bi bi-pencil-square"></i></a>
                                <a href="../_actions/post-delete.php?post-id=<?= escape($post->id) ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')"><i class="bi bi-trash"></i></a>
                              </div>
                            </td>
                          </tr>
                        <?php
                          $i++;
                        }
                      ?>
                    </tbody>
                </table>
                <?php
                  }
                ?>
              </div>
            </div>
          </div>

          <!-- pagination -->
          <div class="d-flex justify-content-center">
            <nav aria-label="Page navigation example">
              <ul class="pagination">
                <li class="page-item">
                  <a class="page-link" href="?page-no=1" aria-label="Previous">
                    <span aria-hidden="true">&laquo;</span>
                  </a>
                </li>
                <?php 
                  for($i = 1; $i <= $totalPages; $i++) {
                  ?>
                    <li class="page-item"><a class="page-link page-no" href="?page-no=<?= $i ?>"><?= $i ?></a></li>
                  <?php
                  }
                ?>
                
                <li class="page-item">
                  <a class="page-link" href="?page-no=<?= $totalPages ?>" aria-label="Next">
                    <span aria-hidden="true">&raquo;</span>
                  </a>
                </li>
              </ul>
            </nav><!-- End Pagination with icons -->
          </div>
        </div>

      </div>
    </div>

  </main><!-- End #main -->

  <!-- ======= Footer ======= -->
  <footer id="footer" class="footer">
    <div class="copyright">
      &copy; Copyright <strong><span>NiceAdmin</span></strong>. All Rights Reserved
    </div>
    <div class="credits">
      <!-- All the links in the footer should remain intact. -->
      <!-- You can delete the links only if you purchased the pro version. -->
      <!-- Licensing information: https://bootstrapmade.com/license/ -->
      <!-- Purchase the pro version with working PHP/AJAX contact form: https://bootstrapmade.com/nice-admin-bootstrap-admin-html-template/ -->
      Designed by <a href="https://bootstrapmade.com/">BootstrapMade</a>
    </div>
  </footer><!-- End Footer -->

  <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

  <!-- Vendor JS Files -->
  <script src="../assets/vendor/apexcharts/apexcharts.min.js"></script>
  <script src="../assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="../assets/vendor/chart.js/chart.umd.js"></script>
  <script src="../assets/vendor/echarts/echarts.min.js"></script>
  <script src="../assets/vendor/quill/quill.js"></script>
  <script src="../assets/vendor/simple-datatables/simple-datatables.js"></script>
  <script src="../assets/vendor/tinymce/tinymce.min.js"></script>
  <script src="../assets/vendor/php-email-form/validate.js"></script>

  <!-- Template Main JS File -->
  <script src="../assets/js/main.js"></script>

  <script>
    const bodyID = document.querySelector('body').id;
    const pages = document.querySelectorAll('.page-no');

    pages[bodyID - 1].classList.add('active');
  </script>

</body>

</html>