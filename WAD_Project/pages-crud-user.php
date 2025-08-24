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

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['newsusername']) && isset($_POST['newsuseremail'])) {
  $newUsername = trim($_POST['newsusername']);
  $newEmail = trim($_POST['newsuseremail']);

  if (empty($newUsername) || empty($newEmail)) {
    echo "<script>alert('User name and email cannot be empty.');</script>";
  } elseif (!filter_var($newEmail, FILTER_VALIDATE_EMAIL)) {
    echo "<script>alert('Invalid email format. Please include @ and .');</script>";
  } elseif ($dbhelper->customerExists($newUsername, $newEmail)) {
    echo "<script>alert('User name or email already exists.');</script>";
  } else {
    $dbhelper->insertNewCustomer($newUsername, $newEmail, '123');
    echo "<script>alert('New user created with default password 123.');</script>";
  }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['updateuserid'], $_POST['updateusername'], $_POST['updateuseremail'])) {
  $updateUserId = intval($_POST['updateuserid']);
  $updateUsername = trim($_POST['updateusername']);
  $updateEmail = trim($_POST['updateuseremail']);

  if (empty($updateUsername) || empty($updateEmail) || $updateUserId <= 0) {
    echo "<script>alert('All fields are required for updating user details.');</script>";
  } elseif (!filter_var($updateEmail, FILTER_VALIDATE_EMAIL)) {
    echo "<script>alert('Invalid email format. Please include @ and .');</script>";
  } elseif (!$dbhelper->customerExistsById($updateUserId)) {
    echo "<script>alert('User ID not found.');</script>";
  } elseif ($dbhelper->customerUsernameExistsForOthers($updateUserId, $updateUsername)) {
    echo "<script>alert('Username already exists for another user. Please use a different username.');</script>";
  } elseif ($dbhelper->customerEmailExistsForOthers($updateUserId, $updateEmail)) {
    echo "<script>alert('Email already exists for another user. Please use a different email.');</script>";
  } else {
    $dbhelper->updateCustomer($updateUserId, $updateUsername, $updateEmail);
    echo "<script>alert('User details updated successfully.');</script>";
  }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['deleteuserid'])) {
  $deleteUserId = intval($_POST['deleteuserid']);

  if ($deleteUserId <= 0) {
    echo "<script>alert('Invalid User ID.');</script>";
  } elseif (!$dbhelper->customerExistsById($deleteUserId)) {
    echo "<script>alert('User ID not found.');</script>";
  } else {
    echo "<script>if (confirm('Are you sure you want to delete this user?')) { window.location.href = '?deleteid=$deleteUserId'; }</script>";
  }
}

if (isset($_GET['deleteid'])) {
  $deleteUserId = intval($_GET['deleteid']);
  $dbhelper->deleteCustomer($deleteUserId);
  echo "<script>alert('User deleted successfully.'); window.location.href = 'pages-crud-user.php';</script>";
}

$customerList = $dbhelper->getAllCustomers();

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title>CRUD User - Staff</title>
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
        <a class="nav-link" href="pages-crud-user.php">
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
      <h1>CRUD User</h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="index.php">Home</a></li>
          <li class="breadcrumb-item">Others</li>
          <li class="breadcrumb-item active">CRUD User</li>
        </ol>
      </nav>
    </div><!-- End Page Title -->

    <section class="section">
      <div class="row">
        <div class="col-lg-12">

          <div class="card">
            <div class="card-body">

              <!-- Table with stripped rows -->
              <table class="table">
                <thead>
                  <tr>
                    <th>ID</th>
                    <th>User Name</th>
                    <th>User Email</th>
                  </tr>
                </thead>
                <tbody>

                  <?php if (empty($customerList)): ?>
                    <tr>
                      <td>No relevant data</td>
                    </tr>
                  <?php else: ?>
                    <?php foreach ($customerList as $customer): ?>
                      <tr>
                        <td><?= htmlspecialchars($customer['customerid']); ?></td>
                        <td><?= htmlspecialchars($customer['customerusername']); ?></td>
                        <td><?= htmlspecialchars($customer['customeremail']); ?></td>
                      </tr>
                    <?php endforeach; ?>
                  <?php endif; ?>
                </tbody>
              </table>
              <!-- End Table with stripped rows -->

            </div>
          </div>

          <!-- Form -->
          <form method="POST">
            <div class="row mb-3">
              <label for="newsusername" class="col-md-4 col-lg-3 col-form-label">Create User Name</label>
              <div class="col-md-8 col-lg-9">
                <input name="newsusername" type="text" class="form-control" id="newsusername" placeholder="Type here">
              </div>
            </div>

            <div class="row mb-3">
              <label for="newsuseremail" class="col-md-4 col-lg-3 col-form-label">Create User Email</label>
              <div class="col-md-8 col-lg-9">
                <input name="newsuseremail" type="email" class="form-control" id="newsuseremail" placeholder="Type here">
              </div>
            </div>

            <div class="text-center">
              <button type="submit" class="btn btn-primary">Insert</button>
            </div>
          </form>
          <div style="margin-top:10px;"></div>

          <form method="POST">
            <div class="row mb-3">
              <label for="updateuserid" class="col-md-4 col-lg-3 col-form-label">User ID</label>
              <div class="col-md-8 col-lg-9">
                <input name="updateuserid" type="number" class="form-control" id="updateuserid" placeholder="Type here">
              </div>
            </div>

            <div class="row mb-3">
              <label for="updateusername" class="col-md-4 col-lg-3 col-form-label">Update User Name</label>
              <div class="col-md-8 col-lg-9">
                <input name="updateusername" type="text" class="form-control" id="updateusername" placeholder="Type here">
              </div>
            </div>

            <div class="row mb-3">
              <label for="updateuseremail" class="col-md-4 col-lg-3 col-form-label">Update User Email</label>
              <div class="col-md-8 col-lg-9">
                <input name="updateuseremail" type="email" class="form-control" id="updateuseremail" placeholder="Type here">
              </div>
            </div>

            <div class="text-center">
              <button type="submit" class="btn btn-primary">Update</button>
            </div>
          </form>
          <div style="margin-top:10px;"></div>

          <form method="POST">
            <div class="row mb-3">
              <label for="deleteuserid" class="col-md-4 col-lg-3 col-form-label">Delete User ID</label>
              <div class="col-md-8 col-lg-9">
                <input name="deleteuserid" type="number" class="form-control" id="deleteuserid" placeholder="Type here">
              </div>
            </div>

            <div class="text-center">
              <button type="submit" class="btn btn-primary">Delete</button>
            </div>
          </form>

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

  <script>
    function updateStatus(id, checked) {
      var status = checked ? 1 : 0;
      var xhr = new XMLHttpRequest();
      xhr.open("POST", "update-status.php", true);
      xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
      xhr.send("id=" + id + "&status=" + status);
    }
  </script>

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