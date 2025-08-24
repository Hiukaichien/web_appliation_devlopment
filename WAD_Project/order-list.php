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

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['order_id']) && isset($_POST['status'])) {
  $orderId = intval($_POST['order_id']);
  $status = intval($_POST['status']);
  $dbhelper->updateOrderStatus($orderId, $status);
  exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title>Order LIst - Staff</title>
  <meta content="" name="description">
  <meta content="" name="keywords">

  <!-- Favicons -->
  <link href="assets/img/favicon.png" rel="icon">

  <!-- Google Fonts -->
  <link href="https://fonts.gstatic.com" rel="preconnect">
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Nunito:300,300i,400,400i,600,600i,700,700i" rel="stylesheet">

  <!-- Vendor CSS Files -->
  <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
  <link href="assets/vendor/boxicons/css/boxicons.min.css" rel="stylesheet">
  <link href="assets/vendor/quill/quill.snow.css" rel="stylesheet">
  <link href="assets/vendor/quill/quill.bubble.css" rel="stylesheet">
  <link href="assets/vendor/remixicon/remixicon.css" rel="stylesheet">
  <link href="assets/vendor/simple-datatables/style.css" rel="stylesheet">

  <!-- Template Main CSS File -->
  <link href="assets/scss/style.css" rel="stylesheet">

  <script>
    function updateStatus(orderId, isChecked) {
      var xhr = new XMLHttpRequest();
      xhr.open("POST", "", true);
      xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
      xhr.send("order_id=" + orderId + "&status=" + (isChecked ? 1 : 0));
    }
  </script>
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
        <a class="nav-link" data-bs-target="#charts-nav" data-bs-toggle="collapse" href="#">
          <i class="bi bi-bar-chart"></i><span>Product</span><i class="bi bi-chevron-down ms-auto"></i>
        </a>
        <ul id="charts-nav" class="nav-content collapse show" data-bs-parent="#sidebar-nav">
          <li>
            <a href="crud-product.php">
              <i class="bi bi-circle"></i><span>CRUD Product</span>
            </a>
          </li>
          <li>
            <a href="order-list.php" class="active">
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
        <a class="nav-link collapsed " href="pages-faq.php">
          <i class="bi bi-question-circle"></i>
          <span>F.A.Q</span>
        </a>
      </li><!-- End F.A.Q Page Nav -->

      <li class="nav-item">
        <a class="nav-link collapsed" href="pages-received-message.php">
          <i class="bi bi-card-list"></i>
          <span>Received Messages</span>
        </a>
      </li><!-- End Register Page Nav -->

      <li class="nav-item">
        <a class="nav-link collapsed" href="pages-crud-staff.php">
          <i class="bi-person-fill"></i>
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
        <a class="nav-link collapsed" href="about-us.php">
          <i class="bi bi-info-circle"></i>
          <span>About Us</span>
        </a>
      </li><!-- End About Us Page Nav -->

    </ul>

  </aside><!-- End Sidebar-->

  <main id="main" class="main">

    <div class="pagetitle">
      <h1>Order List</h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="index.php">Home</a></li>
          <li class="breadcrumb-item">Others</li>
          <li class="breadcrumb-item active">Received Messages</li>
        </ol>
      </nav>
    </div><!-- End Page Title -->

    <section class="section">
      <div class="row">
        <div class="col-lg-12">
          <div class="card">
            <div class="card-body">
              <table class="table">
                <thead>
                  <tr>
                    <th>ID</th>
                    <th>Customer ID</th>
                    <th>Product ID</th>
                    <th>Quantity</th>
                    <th>Order Date</th>
                    <th>Status</th>
                  </tr>
                </thead>
                <tbody>
                  <?php
                  $totalProfit = 0;

                  $orders = $dbhelper->getAllOrders();

                  if (count($orders) > 0) {
                    foreach ($orders as $order) {
                      $productPrice = $dbhelper->getProductPriceById($order['product_id']);
                      $profit = $productPrice * $order['quantity'];

                      $totalProfit += $profit;

                      echo '<tr>';
                      echo '<td>' . $order['id'] . '</td>';
                      echo '<td>' . $order['customer_id'] . '</td>';
                      echo '<td>' . $order['product_id'] . '</td>';
                      echo '<td>' . $order['quantity'] . '</td>';
                      echo '<td>' . $order['order_date'] . '</td>';
                      echo '<td style="text-align: center;">';
                      echo '<input type="checkbox" class="form-check-input" style="margin-left: -80px;" 
                          onchange="updateStatus(' . $order['id'] . ', this.checked)" 
                          ' . ($order['status'] ? 'checked' : '') . ' 
                          data-order-id="' . $order['id'] . '">';
                      echo '</td>';
                      echo '</tr>';
                    }
                  } else {
                    echo '<tr><td colspan="6" style="text-align: center;">No relevant data</td></tr>';
                  }
                  ?>
                </tbody>
              </table>
            </div>
          </div>
          <h4>Total Profit : RM <?php echo number_format($totalProfit, 2); ?></h4>
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

  <style>
    .truncate {
      max-width: 200px;
      white-space: nowrap;
      overflow: hidden;
      text-overflow: ellipsis;
      position: relative;
    }

    .truncate:hover::after {
      content: attr(data-fulltext);
      white-space: normal;
      position: fixed;
      background: #fff;
      border: 1px solid #ccc;
      padding: 20px;
      z-index: 1000;
      max-width: 300px;
      box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
      left: 50%;
    }
  </style>

</body>

</html>