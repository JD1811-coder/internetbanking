<?php
session_start();
include('conf/config.php');
include('conf/checklogin.php');
check_login();
$client_id = $_SESSION['client_id'];
//roll back transaction
if (isset($_GET['RollBack_Transaction'])) {
  $id = intval($_GET['RollBack_Transaction']);
  $adn = "DELETE FROM  iB_Transactions  WHERE tr_id = ?";
  $stmt = $mysqli->prepare($adn);
  $stmt->bind_param('i', $id);
  $stmt->execute();
  $stmt->close();

  if ($stmt) {
    $info = "Transaction Rolled Back";
  } else {
    $err = "Try Again Later";
  }
}

?>
<!-- Log on to codeastro.com for more projects! -->
<!DOCTYPE html>
<html>
<meta http-equiv="content-type" content="text/html;charset=utf-8" />
<?php include("dist/_partials/head.php"); ?>

<body class="hold-transition sidebar-mini layout-fixed layout-navbar-fixed">
  <div class="wrapper">
    <!-- Navbar -->
    <?php include("dist/_partials/nav.php"); ?>
    <!-- /.navbar -->

    <!-- Main Sidebar Container -->
    <?php include("dist/_partials/sidebar.php"); ?>

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
      <!-- Content Header (Page header) -->
      <section class="content-header">
        <div class="container-fluid">
          <div class="row mb-2">
            <div class="col-sm-6">
              <h1>Transaction History</h1>
            </div>
            <div class="col-sm-6">
              <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="pages_dashboard.php">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="pages_transactions_engine.php">Transaction History</a></li>
                <li class="breadcrumb-item active">Transactions</li>
              </ol>
            </div>
          </div>
        </div><!-- /.container-fluid -->
      </section>

      <!-- Main content -->
      <section class="content">
        <div class="row">
          <div class="col-12">
            <div class="card">
              <div class="card-header">
                <h3 class="card-title">Select on any action options to manage Transactions</h3>
              </div>
              <div class="card-body">
                <table id="example1" class="table table-hover table-striped">
                  <thead>
                    <tr>
                      <th>#</th>
                      <th>Transaction Code</th>
                      <th>Account No.</th>
                      <th>Type</th>
                      <th>Amount</th>
                      <th>Acc. Owner</th>
                      <th>Sender Account</th>

                      <th>Receiving Account</th>
                      <th>Timestamp</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php
                    //Get latest transactions 
                    $client_id = $_SESSION['client_id'];
                    $ret = "SELECT 
  t.*,  
  b.account_number, 
  at.name AS acc_type,  
  COALESCE(b.acc_name, 'N/A') AS account_owner, 
  COALESCE(c.name, 'N/A') AS client_name,         -- Sender
  COALESCE(rc.name, '-') AS receiver_name         -- Receiver
FROM iB_Transactions t
LEFT JOIN ib_bankaccounts b ON t.account_id = b.account_id
LEFT JOIN ib_acc_types at ON b.acc_type_id = at.acctype_id
LEFT JOIN ib_clients c ON t.client_id = c.client_id              -- Sender
LEFT JOIN ib_bankaccounts rb ON t.receiving_acc_no = rb.account_number
LEFT JOIN ib_clients rc ON rb.client_id = rc.client_id           -- Receiver
WHERE t.client_id = ? OR rb.client_id = ? 
ORDER BY t.created_at DESC
";
$stmt = $mysqli->prepare($ret);
$stmt->bind_param('ii', $client_id, $client_id); // for sender or receiver


                    $stmt->execute(); //ok
                    $res = $stmt->get_result();
                    $cnt = 1;
                    while ($row = $res->fetch_object()) {
                      /* Trim Transaction Timestamp to 
                            *  User Uderstandable Formart  DD-MM-YYYY :
                            */
                      $transTstamp = $row->created_at;
                      //Perfom some lil magic here
                      if ($row->tr_type == 'Deposit') {
                        $alertClass = "<span class='badge badge-success'>$row->tr_type</span>";
                      } elseif ($row->tr_type == 'Withdrawal') {
                        $alertClass = "<span class='badge badge-danger'>$row->tr_type</span>";
                      } else {
                        $alertClass = "<span class='badge badge-warning'>$row->tr_type</span>";
                      }
                    ?>

                      <tr>
                        <td><?php echo $cnt; ?></td>
                        <td><?php echo $row->tr_code; ?></a></td>
                        <td><?php echo $row->account_number; ?></td>
                        <td><?php echo $alertClass; ?></td>
                        <td>Rs. <?php echo $row->transaction_amt; ?></td>
                        <td>
  <?php
    // Show logged-in user name only
    $loggedInUserName = $_SESSION['name'] ?? 'You';
    echo $loggedInUserName;
  ?>
</td>


                        <td>
  <?php
    if ($row->tr_type == 'Transfer') {
      echo $row->client_name ?? '-';
    } else {
      echo '-';
    }
  ?>
</td>
<td>
  <?php
    if ($row->tr_type == 'Transfer') {
      echo $row->receiver_name ?? '-';
    } else {
      echo '-';
    }
  ?>
</td>



                        <td><?php echo date("d-M-Y h:m:s ", strtotime($transTstamp)); ?></td>

                      </tr>
                    <?php $cnt = $cnt + 1;
                    } ?>
                    </tfoot>
                </table>
              </div>
              <!-- /.card-body -->
            </div>
            <!-- /.card -->
          </div>
          <!-- /.col -->
        </div><!-- Log on to codeastro.com for more projects! -->
        <!-- /.row -->
      </section>
      <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->
    <?php include("dist/_partials/footer.php"); ?>

    <!-- Control Sidebar -->
    <aside class="control-sidebar control-sidebar-dark">
      <!-- Control sidebar content goes here -->
    </aside>
    <!-- /.control-sidebar -->
  </div>
  <!-- ./wrapper -->

  <!-- jQuery -->
  <script src="plugins/jquery/jquery.min.js"></script>
  <!-- Bootstrap 4 -->
  <script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
  <!-- DataTables -->
  <script src="plugins/datatables/jquery.dataTables.js"></script>
  <script src="plugins/datatables-bs4/js/dataTables.bootstrap4.js"></script>
  <!-- AdminLTE App -->
  <script src="dist/js/adminlte.min.js"></script>
  <!-- AdminLTE for demo purposes -->
  <script src="dist/js/demo.js"></script>
  <!-- page script -->
  <script>
    $(function() {
      $("#example1").DataTable();
      $('#example2').DataTable({
        "paging": true,
        "lengthChange": false,
        "searching": false,
        "ordering": true,
        "info": true,
        "autoWidth": false,
      });
    });
  </script>
</body>

</html>