<?php
session_start();
include('conf/config.php');
include('conf/checklogin.php');
check_login();
$client_id = $_SESSION['staff_id'];

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
        <?php
        {
      // Fetch account details
$account_id = $_GET['account_id'];
$client_id = $_SESSION['client_id'];

// Fetch total deposits
$result = "SELECT SUM(transaction_amt) FROM iB_Transactions WHERE client_id = ? AND tr_type = 'Deposit'";
$stmt = $mysqli->prepare($result);
$stmt->bind_param('i', $client_id);
$stmt->execute();
$stmt->bind_result($iB_deposits);
$stmt->fetch();
$stmt->close();
$iB_deposits = $iB_deposits ?? 0; // Ensure it's not NULL

// Fetch total withdrawals
$result = "SELECT SUM(transaction_amt) FROM iB_Transactions WHERE client_id = ? AND tr_type = 'Withdrawal'";
$stmt = $mysqli->prepare($result);
$stmt->bind_param('i', $client_id);
$stmt->execute();
$stmt->bind_result($iB_withdrawal);
$stmt->fetch();
$stmt->close();
$iB_withdrawal = $iB_withdrawal ?? 0;

// Fetch total transfers
$result = "SELECT SUM(transaction_amt) FROM iB_Transactions WHERE client_id = ? AND tr_type = 'Transfer'";
$stmt = $mysqli->prepare($result);
$stmt->bind_param('i', $client_id);
$stmt->execute();
$stmt->bind_result($iB_Transfers);
$stmt->fetch();
$stmt->close();
$iB_Transfers = $iB_Transfers ?? 0;

// Fetch initial balance
$result = "SELECT SUM(acc_amount) FROM iB_bankAccounts WHERE client_id = ?";
$stmt = $mysqli->prepare($result);
$stmt->bind_param('i', $client_id);
$stmt->execute();
$stmt->bind_result($TotalBalInAccount);
$stmt->fetch();
$stmt->close();
$TotalBalInAccount = $TotalBalInAccount ?? 0;

// Fetch account details
$ret = "SELECT a.*, c.name, c.client_number, c.email AS client_email, c.phone AS client_phone,
        t.name AS acc_type, t.rate AS acc_rates
        FROM iB_bankAccounts a
        JOIN iB_clients c ON a.client_id = c.client_id
        LEFT JOIN ib_acc_types t ON a.acc_type_id = t.acctype_id
        WHERE a.account_id = ?";
$stmt = $mysqli->prepare($ret);
$stmt->bind_param('i', $account_id);
$stmt->execute();
$res = $stmt->get_result();
$row = $res->fetch_object();

// Correct Balance Calculation
$money_out = $iB_withdrawal + $iB_Transfers;
$money_in = $iB_deposits;
$banking_rate = ($row->acc_rates ?? 0) / 100;
$rate_amt = $banking_rate * $money_in;
$totalMoney = $TotalBalInAccount + $money_in - $money_out + $rate_amt;

        ?>
            <div class="content-wrapper">
                <!-- Content Header (Page header) -->
                <section class="content-header">
                    <div class="container-fluid">
                        <div class="row mb-2">
                            <div class="col-sm-6">
                                <h1><?php echo $row->name; ?> Account Balance</h1>
                            </div>
                            <div class="col-sm-6">
                                <ol class="breadcrumb float-sm-right">
                                    <li class="breadcrumb-item"><a href="pages_dashboard.php">Dashboard</a></li>
                                    <li class="breadcrumb-item"><a href="pages_balance_enquiries.php">Finances</a></li>
                                    <li class="breadcrumb-item"><a href="pages_balance_enquiries.php">Balances</a></li>
                                    <li class="breadcrumb-item active"><?php echo $row->name; ?> Accs</li>
                                </ol>
                            </div>
                        </div>
                    </div><!-- /.container-fluid -->
                </section>

                <!-- Main content -->
                <section class="content">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-12">
                                <!-- Main content -->
                                <div id="balanceSheet" class="invoice p-3 mb-3">
                                    <!-- title row -->
                                    <div class="row">
                                        <div class="col-12">
                                            <h4>
                                                <i class="fas fa-bank"></i> DigiBankX Balance Enquiry
                                                <small class="float-right">Date: <?php echo date('d/m/Y'); ?></small>
                                            </h4>
                                        </div>
                                        <!-- /.col -->
                                    </div><!-- Log on to codeastro.com for more projects! -->
                                    <!-- info row -->
                                    <div class="row invoice-info">
                                        <div class="col-sm-6 invoice-col">
                                            Account Holder
                                            <address>
                                                <strong><?php echo $row->name; ?></strong><br>
                                                <?php echo $row->client_number; ?><br>
                                                <?php echo $row->client_email; ?><br>
                                                Phone: <?php echo $row->client_phone; ?><br>
                                                <!-- ID No: <?php echo $row->client_national_id; ?> -->
                                            </address>
                                        </div>
                                        <!-- /.col -->
                                        <div class="col-sm-6 invoice-col">
                                            Account Details
                                            <address>
                                                <strong><?php echo $row->acc_name; ?></strong><br>
                                                Acc No: <?php echo $row->account_number; ?><br>
                                                Acc Type: <?php echo $row->acc_type; ?><br>
                                                Acc Rates: <?php echo $row->acc_rates; ?> %
                                            </address>
                                        </div>

                                    </div>
                                    <!-- /.row -->

                                    <!-- Table row -->
                                    <div class="row">
                                        <div class="col-12 table-responsive">
                                            <table class="table table-hover table-bordered table-striped">
                                                <thead>
                                                    <tr>
                                                        <th>Deposits</th>
                                                        <th>Withdrawals</th>
                                                        <th>Transfers</th>
                                                        <th>Subtotal</th>
                                                    </tr>
                                                </thead>
                                                <tbody>

                                                    <tr>
                                                        <td>Rs. <?php echo $deposit; ?></td>
                                                        <td>Rs. <?php echo $withdrawal; ?></td>
                                                        <td>Rs. <?php echo $Transfer; ?></td>
                                                        <td>Rs. <?php echo $money_in; ?></td>
                                                    </tr>

                                                </tbody>
                                            </table>
                                        </div>
                                        <!-- /.col -->
                                    </div>
                                    <!-- /.row -->

                                    <div class="row">
                                        <!-- accepted payments column -->
                                        <div class="col-6">
                                            <p class="lead"></p>

                                            <p class="text-muted well well-sm shadow-none" style="margin-top: 10px;">

                                            </p>
                                        </div>
                                        <!-- /.col -->
                                        <div class="col-6">
                                            <p class="lead">Balance Checked On : <?php echo date('d-M-Y'); ?></p>

                                            <div class="table-responsive">
                                                <table class="table table-bordered">
                                                    <tr>
                                                        <th style="width:50%">Funds In:</th>
                                                        <td>Rs. <?php echo $deposit; ?></td>
                                                    </tr>
                                                    <tr>
                                                        <th>Funds Out</th>
                                                        <td>Rs. <?php echo $money_out; ?></td>
                                                    </tr>
                                                    <tr>
                                                        <th>Sub Total:</th>
                                                        <td>Rs. <?php echo $money_in; ?></td>
                                                    </tr>
                                                    <!-- <tr>
                                                        <th>Banking Intrest:</th>
                                                        <td>Rs. <?php echo $rate_amt; ?></td>
                                                    </tr> -->
                                                    <tr>
                                                        <th>Total Balance:</th>
                                                        <td>Rs. <?php echo $totalMoney; ?></td>
                                                    </tr>
                                                </table>
                                            </div>
                                        </div>
                                        <!-- /.col -->
                                    </div>
                                    <!-- /.row -->

                                    <!-- this row will not appear when printing -->
                                    <div class="row no-print">
                                        <div class="col-12">

                                            <button type="button" id="print" onclick="printContent('balanceSheet');" class="btn btn-success float-right" style="margin-right: 5px;">
                                                <i class="fas fa-print"></i> Print
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                <!-- /.invoice -->
                            </div><!-- /.col -->
                        </div><!-- /.row -->
                    </div><!-- /.container-fluid -->
                </section>
                <!-- /.content -->
            </div>
        <?php } ?>
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
    <script>
        //print balance sheet
        function printContent(el) {
            var restorepage = $('body').html();
            var printcontent = $('#' + el).clone();
            $('body').empty().html(printcontent);
            window.print();
            $('body').html(restorepage);
        }
    </script>
</body>

</html>