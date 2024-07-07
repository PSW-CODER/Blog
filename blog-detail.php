<?php
    session_start();
    require '_classes/Database/PostTable.php';
    require '_classes/Auth.php';

    use _classes\Database\PostTable;
    use _classes\Database\MySQL;
    use _classes\Auth;

    $user = Auth::check();
    
    $table = new PostTable(new MySQL());

    if($table){
        $blog = $table->get($_GET['blog']);
        $comments = $table->getComment($_GET['blog']);
    }
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title>Blog Site</title>
  <meta content="" name="description">
  <meta content="" name="keywords">

  <!-- Favicons -->
  <link href="assets/img/favicon.png" rel="icon">
  <link href="assets/img/apple-touch-icon.png" rel="apple-touch-icon">

  <!-- Google Fonts -->
  <link href="https://fonts.gstatic.com" rel="preconnect">
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Nunito:300,300i,400,400i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">

  <!-- Vendor CSS Files -->
  <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
  <link href="assets/vendor/boxicons/css/boxicons.min.css" rel="stylesheet">
  <link href="assets/vendor/quill/quill.snow.css" rel="stylesheet">
  <link href="assets/vendor/quill/quill.bubble.css" rel="stylesheet">
  <link href="assets/vendor/remixicon/remixicon.css" rel="stylesheet">
  <link href="assets/vendor/simple-datatables/style.css" rel="stylesheet">

  <!-- Template Main CSS File -->
  <link href="assets/css/style.css" rel="stylesheet">

  <!-- =======================================================
  * Template Name: NiceAdmin
  * Template URL: https://bootstrapmade.com/nice-admin-bootstrap-admin-html-template/
  * Updated: Apr 20 2024 with Bootstrap v5.3.3
  * Author: BootstrapMade.com
  * License: https://bootstrapmade.com/license/
  ======================================================== -->
</head>
<body>
  <header id="header" class="header fixed-top d-flex align-items-center">
    <div class="container d-flex justify-content-between">
        <div class="d-flex align-items-center justify-content-between">
            <a href="index.html" class="logo d-flex align-items-center justify-content-start">
                <img src="assets/img/logo.png" alt="">
                <span>Blog</span>
            </a>
        </div><!-- End Logo -->

        <nav class="header-nav ms-auto">
            <ul class="d-flex align-items-center">
                <li class="nav-item dropdown pe-3">
                    <a class="nav-link nav-profile d-flex align-items-center pe-0" href="#" data-bs-toggle="dropdown">
                        <img src="images/<?= $user->image ?>" alt="Profile" class="rounded-circle border border-2">
                        <span class="d-none d-md-block dropdown-toggle ps-2"><?= $user->name ?></span>
                    </a><!-- End Profile Iamge Icon -->

                    <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow profile">
                        <li class="dropdown-header">
                            <h6><?= $user->name ?></h6>
                            <span>
                                <?php 
                                if($user->role_id === 1){
                                ?>
                                    Admin
                                <?php
                                }else{
                                ?>
                                    User
                                <?php
                                }
                                ?>
                            </span>
                        </li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        <li>
                            <a class="dropdown-item d-flex align-items-center" href="_actions/logout.php">
                                <i class="bi bi-box-arrow-right"></i>
                                <span>Sign Out</span>
                            </a>
                        </li>
                    </ul><!-- End Profile Dropdown Items -->
                </li><!-- End Profile Nav -->

            </ul>
        </nav><!-- End Icons Navigation -->
    </div>      
  </header><!-- End Header -->

  <main id="main" class="main m-0" style="padding-top: 90px">
    <div class="container">
        <div class="row">
            <div class="d-flex justify-content-center">
                <div class="col-lg-8">
                    <form action="_actions/comment.php" method="post">
                        <div class="card">
                            <div class="card-header">
                                <div class="card-title text-center pb-1" style="font-size: 1.8rem; font-weight: bolder;"><?= $blog->title ?></div>
                            </div>
                            <div class="card-body mt-4">     
                                <input type="hidden" name="authorId" value="<?= $user->id ?>">
                                <input type="hidden" name="blogId" value="<?= $blog->id ?>">   
                                <img src="images/<?= $blog->image ?>" alt="" class="card-img">
                                <p class="card-text mt-3"><?= $blog->content ?></p>
                                    
                                <h6 class="text-end" style="font-size: 14px"><?= date('d-m-Y', strtotime($blog->created_at)) ?></h6>
                            </div>
                            <div class="card-footer">
                                <div class="comment-wrap px-1 mb-4">
                                    <div class="card-title">Comments</div>
                                    <div class="comment-list">   
                                        <?php
                                        foreach ($comments as $comment) {
                                        ?>
                                        <li style="list-style: none;" class="mb-2">
                                            <div class="comment p-2 rounded-2" style="background-color: #f7f9ff">
                                                <div class="comment-writer d-flex justify-content-between align-items-center gap-3">
                                                    <div class="d-flex align-items-center gap-3">
                                                    <img src="images/<?= $comment->photo ?>" class="rounded-circle border border-2" width="40px" height="40px" alt="">
                                                        <h6 class="name" style="font-weight: bolder!important;"><?= $comment->author ?></h6>
                                                    </div>
                                                    <p style="font-size: 14px"><?= date('d-m-Y', strtotime($comment->created_at)) ?></p>
                                                </div>

                                                <p class="card-text ms-5 ps-3 pb-3"><?= $comment->content ?></p>
                                            </div>
                                        </li>
                                        <?php
                                        }
                                        ?>                             
                                    </div>
                                </div>

                                <div class="action-btn position-relative px-1">
                                    <div class="input-comment-wrap p-2 rounded-2" style="background-color: #f7f9ff">
                                        <div class="comment-writer d-flex align-items-center gap-3 p-2">
                                            <img src="images/<?= $user->image ?>" class="rounded-circle border border-2" width="40px" height="40px" alt="">
                                            <div class="position-relative w-100">
                                                <input type="text" name="comment" class="form-control" placeholder="Enter your comment">
                                                <button class="position-absolute btn" style="top: 50%; right: 2px; transform: translateY(-50%)">
                                                    <i class="bi bi-send"></i>
                                                </button> 
                                            </div>       
                                        </div>                                    
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                    <!-- back btn -->
                    <div class="action-btn text-center">
                        <a href="index.php" class="btn text-white px-2 py-1" style="background-color: #4154f1;"><i style="font-size: 24px" class="bi bi-arrow-left-short"></i></a>
                    </div>
                </div>
            </div>
        </div>
    </div>
  </main><!-- End #main -->

  <!-- ======= Footer ======= -->
  <footer id="footer" class="footer m-0">
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
  <script src="assets/vendor/apexcharts/apexcharts.min.js"></script>
  <script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="assets/vendor/chart.js/chart.umd.js"></script>
  <script src="assets/vendor/echarts/echarts.min.js"></script>
  <script src="assets/vendor/quill/quill.js"></script>
  <script src="assets/vendor/simple-datatables/simple-datatables.js"></script>
  <script src="assets/vendor/tinymce/tinymce.min.js"></script>
  <script src="assets/vendor/php-email-form/validate.js"></script>

  <!-- Template Main JS File -->
  <script src="assets/js/main.js"></script>

</body>

</html>