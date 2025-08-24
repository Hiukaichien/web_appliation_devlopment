<?php
session_start();
if (!isset($_SESSION['username'])) {
  header("Location: StaffLoginPage.php");
  exit();
}
$username = $_SESSION['username'];

if (isset($_GET['logout'])) {
  session_destroy();
  header("Location: CustomerLoginPage.php");
  exit();
}

require_once 'DBhelper.php';
$dbhelper = new DBhelper();
$messages = $dbhelper->getAllContactMessages();
$unread_messages = $dbhelper->getContactMessages();
$unread_count = $dbhelper->countUnreadMessages();
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title>About Us - Staff</title>
  <meta content="" name="description">
  <meta content="" name="keywords">

  <!-- Favicons -->
  <link href="assets/img/favicon.png" rel="icon">

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

  <!-- ======= Header ======= -->
  <header id="header" class="header fixed-top d-flex align-items-center">

    <div class="d-flex align-items-center justify-content-between">
      <a href="index.php" class="logo d-flex align-items-center">
        <img src="assets/img/logo-new.png" alt="">
        <span class="d-none d-lg-block">NeucStaff</span>
      </a>
      <i class="bi bi-list toggle-sidebar-btn"></i>
    </div><!-- End Logo -->


    <!--
    <div class="search-bar">
      <form class="search-form d-flex align-items-center" method="POST" action="#">
        <input type="text" name="query" placeholder="Search" title="Enter search keyword">
        <button type="submit" title="Search"><i class="bi bi-search"></i></button>
      </form>
    </div>
    -->

    <nav class="header-nav ms-auto">
      <ul class="d-flex align-items-center">

        <!--
        <li class="nav-item d-block d-lg-none">
          <a class="nav-link nav-icon search-bar-toggle " href="#">
            <i class="bi bi-search"></i>
          </a>
        </li>
        -->

        <!--
        <li class="nav-item dropdown">

          <a class="nav-link nav-icon" href="#" data-bs-toggle="dropdown">
            <i class="bi bi-bell"></i>
            <span class="badge bg-success badge-number">1</span>
          </a>

          <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow notifications">
            <li class="dropdown-header">
              Here is your notifications
            </li>
            <li>
              <hr class="dropdown-divider">
            </li>

            <li>
              <hr class="dropdown-divider">
            </li>

            <li class="notification-item">
              <i class="bi bi-info-circle text-primary"></i>
              <div>
                <h4>Dicta reprehenderit</h4>
                <p>Quae dolorem earum veritatis oditseno</p>
                <p>4 hrs. ago</p>
              </div>
            </li>

            <li>
              <hr class="dropdown-divider">
            </li>
            <li class="dropdown-footer">
              That's all your notifications
            </li>

          </ul>

        </li>-->

        <li class="nav-item dropdown">

          <a class="nav-link nav-icon" href="#" data-bs-toggle="dropdown">
            <i class="bi bi-chat-left-text"></i>
            <span class="badge bg-success badge-number"><?php echo $unread_count; ?></span>
          </a><!-- End Messages Icon -->

          <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow messages">
            <li class="dropdown-header">
              Here is your new messages
            </li>
            <li>
              <hr class="dropdown-divider">
            </li>

            <li class="message-item">
              <?php if (empty($unread_messages)): ?>
                <h4>No any new messages here</h4>
              <?php else: ?>
                <?php foreach ($unread_messages as $message): ?>
                  <a href="pages-received-message.php">
                    <img src="assets/img/customer.png" alt="" class="rounded-circle">
                    <div>
                      <h4><?php echo htmlspecialchars($message['customerusername']); ?></h4>
                      <p><?php echo htmlspecialchars($message['message']); ?></p>
                      <p><?php echo htmlspecialchars($message['date']); ?></p>
                    </div>
                  </a>
                <?php endforeach; ?>
              <?php endif; ?>
            </li>
            <li>
              <hr class="dropdown-divider">
            </li>

            <li>
              <hr class="dropdown-divider">
            </li>

            <li class="dropdown-footer">
              That's all your messages
            </li>

          </ul><!-- End Messages Dropdown Items -->

        </li><!-- End Messages Nav -->

        <li class="nav-item dropdown pe-3">

          <a class="nav-link nav-profile d-flex align-items-center pe-0" href="#" data-bs-toggle="dropdown">
            <img src="assets/img/staff-profile-picture.png" alt="Profile" class="rounded-circle">
            <span class="d-none d-md-block dropdown-toggle ps-2"><?php echo htmlspecialchars($username); ?></span>
          </a><!-- End Profile Iamge Icon -->

          <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow profile">
            <li class="dropdown-header">
              <h6><?php echo htmlspecialchars($username); ?></h6>
              <span>Staff</span>
            </li>
            <li>
              <hr class="dropdown-divider">
            </li>

            <li>
              <a class="dropdown-item d-flex align-items-center" href="staff-profile.php">
                <i class="bi bi-person"></i>
                <span>My Profile</span>
              </a>
            </li>
            <li>
              <hr class="dropdown-divider">
            </li>

            <li>
              <a class="dropdown-item d-flex align-items-center" href="pages-faq.php">
                <i class="bi bi-question-circle"></i>
                <span>Need Help?</span>
              </a>
            </li>
            <li>
              <hr class="dropdown-divider">
            </li>

            <li>
              <a class="dropdown-item d-flex align-items-center" href="?logout=true">
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
        <a class="nav-link collapsed" href="index.php">
          <i class="bi bi-grid"></i>
          <span>Dashboard</span>
        </a>
      </li><!-- End Dashboard Nav -->

      <li class="nav-item">
        <a class="nav-link collapsed" data-bs-target="#charts-nav" data-bs-toggle="collapse" href="#">
          <i class="bi bi-bar-chart"></i><span>Product</span><i class="bi bi-chevron-down ms-auto"></i>
        </a>
        <ul id="charts-nav" class="nav-content collapse " data-bs-parent="#sidebar-nav">
          <li>
            <a href="crud-product.php">
              <i class="bi bi-circle"></i><span>CRUD Product</span>
            </a>
          </li>
          <li>
            <a href="order-list.php">
              <i class="bi bi-circle"></i><span>Order List</span>
            </a>
          </li>
        </ul>
      </li><!-- End Nav -->

      <li class="nav-heading">Others</li>

      <li class="nav-item">
        <a class="nav-link collapsed" href="staff-profile.php">
          <i class="bi bi-person"></i>
          <span>Profile</span>
        </a>
      </li><!-- End Profile Page Nav -->

      <li class="nav-item">
        <a class="nav-link collapsed" href="pages-faq.php">
          <i class="bi bi-question-circle"></i>
          <span>F.A.Q</span>
        </a>
      </li><!-- End F.A.Q Page Nav -->

      <li class="nav-item">
        <a class="nav-link collapsed" href="pages-received-message.php">
          <i class="bi bi-card-list"></i>
          <span>Received Messages</span>
        </a>
      </li><!-- End Received Message Page Nav -->

      <li class="nav-item">
        <a class="nav-link collapsed" href="pages-crud-staff.php">
          <i class="bi bi-person-fill"></i>
          <span>CRUD Staff</span>
        </a>
      </li><!-- End CRUD Staff Page Nav -->

      <li class="nav-item">
        <a class="nav-link collapsed" href="pages-crud-user.php">
          <i class="bi bi-person-add"></i>
          <span>CRUD User</span>
        </a>
      </li><!-- End CRUD User Page Nav -->

      <li class="nav-item">
        <a class="nav-link" href="about-us.php">
          <i class="bi bi-info-circle"></i>
          <span>About Us</span>
        </a>
      </li><!-- End About Us Page Nav -->

    </ul>

  </aside><!-- End Sidebar-->

  <main id="main" class="main">

    <div class="pagetitle">
      <h1>About Us Page</h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="index.php">Home</a></li>
          <li class="breadcrumb-item">Others</li>
          <li class="breadcrumb-item active">About Us</li>
        </ol>
      </nav>
    </div><!-- End Page Title -->

    <section class="section about">
      <div class="row">
        <div class="col-lg-6">
          <div class="card">
            <div class="card-body">
              <h5 class="card-title">Our Journey (1950s to 1990s)</h5>
              <div class="content">
                <img src="assets/img/history-1950s.jpg" alt="1950s" class="img-fluid">
                <p>During the 1950s, the company established its roots in the agricultural industry, focusing on Malaysia’s primary crops—rubber and rice. Rubber plantations became the cornerstone of its business, catering to the global demand for natural rubber used in manufacturing tires, industrial products, and household goods. Meanwhile, rice cultivation supported domestic food security, ensuring a steady supply for Malaysia’s growing population. This decade laid the groundwork for the company’s agricultural expertise, combining traditional farming methods with a dedication to supporting rural economies.</p>
                <img src="assets/img/history-1970s.webp" alt="1970s" class="img-fluid">
                <p>The 1970s marked a pivotal shift as Malaysia emerged as a leader in the global palm oil industry. The company recognized the massive potential of palm oil as a versatile commodity used in cooking, cosmetics, and industrial applications. It began converting its rubber plantations into oil palm estates, rapidly expanding its land holdings to keep pace with the industry’s explosive growth. This strategic pivot led to a significant increase in production capacity and positioned the company as a key player in the palm oil sector.</p>
                <img src="assets/img/history-1990s.webp" alt="1990s" class="img-fluid">
                <p>As globalization opened new opportunities, the company in the 1990s embraced modern farming methods to boost productivity and quality. Mechanized equipment replaced traditional tools, and scientific methods such as soil analysis and pest control were introduced to maximize yields. Simultaneously, the company diversified its revenue streams by entering international markets. Exporting raw and processed palm oil, rubber, and other agricultural products to countries in Asia, Europe, and the Middle East cemented its reputation as a trusted global supplier and drove substantial revenue growth.</p>
              </div>
            </div>
          </div>
        </div>

        <div class="col-lg-6">
          <div class="card">
            <div class="card-body">
              <h5 class="card-title">Our Evolution (2000s to 2020s)</h5>
              <div class="content">
                <img src="assets/img/history-2000s.jpg" alt="2000s" class="img-fluid">
                <p>The 2000s saw the company embracing sustainability in response to increasing global awareness of environmental conservation. It implemented eco-friendly farming practices, such as reducing chemical inputs, conserving water, and protecting biodiversity in plantation areas. To reduce dependency on palm oil, it diversified into tropical fruits like durians, bananas, and pineapples, catering to growing consumer demand. Moreover, the company ventured into aquaculture, investing in fish and shrimp farming to provide high-quality seafood for local and international markets. This diversification ensured resilience and long-term growth.</p>
                <img src="assets/img/history-2010s.jpg" alt="2010s" class="img-fluid">
                <p>In the 2010s, the company became a leader in integrating technology into agriculture. It deployed precision farming tools, including satellite imagery, sensors, and drones, to monitor crop health and optimize yields. These advancements allowed it to reduce waste and improve profitability. The company also expanded into processing raw agricultural products into refined goods, such as bottled palm oil, packaged tropical fruits, and seafood ready for export. This vertical integration created value-added products, enabling the company to capture higher margins and appeal to premium markets.</p>
                <img src="assets/img/history-2020s..jpeg" alt="2020s" class="img-fluid">
                <p>By the 2020s, the company turned its attention to branding and global market dominance. It launched aggressive marketing campaigns to build a reputation as a leader in sustainable and innovative agriculture. The company also forged strategic partnerships, acquired complementary businesses, and expanded its presence in key international markets. Through these efforts, it built a trusted global brand that resonated with environmentally conscious consumers and businesses, securing its position as a market leader and a pioneer in agricultural innovation.</p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>

  </main><!-- End #main -->

  <!-- ======= Footer ======= -->
  <footer id="footer" class="footer">
    <div class="copyright">
      &copy; Copyright <strong><span>NEUC</span></strong>. All Rights Reserved
    </div>
    <div class="credits">
      <!-- All the links in the footer should remain intact. -->
      <!-- You can delete the links only if you purchased the pro version. -->
      <!-- Licensing information: https://bootstrapmade.com/license/ -->
      <!-- Purchase the pro version with working PHP/AJAX contact form: https://bootstrapmade.com/nice-admin-bootstrap-admin-html-template/ -->

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