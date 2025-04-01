<?php
session_start();
include('conf/config.php');
include('conf/checklogin.php');
check_login();
$staff_id = $_SESSION['staff_id'];

// Fetch Bank Main Account Balance
$bank_account_query = "SELECT total_balance FROM ib_bank_main_account WHERE id = 1";
$bank_account_stmt = $mysqli->prepare($bank_account_query);
$bank_account_stmt->execute();
$bank_account_result = $bank_account_stmt->get_result();
$bank_account = $bank_account_result->fetch_object();
$bank_balance = number_format($bank_account->total_balance, 2);

?>

<!DOCTYPE html>
<html>
<?php include("dist/_partials/head.php"); ?>
<body class="hold-transition sidebar-mini layout-fixed layout-navbar-fixed">
  <div class="wrapper">
    <?php include("dist/_partials/nav.php"); ?>
    <?php include("dist/_partials/sidebar.php"); ?>

    <div class="content-wrapper">
      <section class="content-header">
        <div class="container-fluid">
          <div class="row mb-2">
            <div class="col-sm-6">
              <h1>Bank Main Account</h1>
            </div>
          </div>
        </div>
      </section>

      <section class="content">
        <div class="row">
          <div class="col-12">
            <div class="card">
              <div class="card-header">
                <h3 class="card-title">Bank Reserve Balance</h3>
              </div>
              <div class="card-body">
                <h4>Available Balance: ₹<?php echo $bank_balance; ?></h4>
              </div>
            </div>
          </div>
        </div>
      </section>
    </div>
    
    <?php include("dist/_partials/footer.php"); ?>
  </div>
  
  <script src="plugins/jquery/jquery.min.js"></script>
  <script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="dist/js/adminlte.min.js"></script>
</body>
</html>