<?php
session_start();
include('conf/config.php');
include('conf/checklogin.php');
check_login();

$client_id = $_SESSION['client_id'];

if (isset($_POST['deposit']) && isset($_GET['account_id'], $_GET['  '])) {   
    $tr_code = $_POST['tr_code'];
    $account_id = $_GET['account_id'];  // Sender's Account ID
    $client_id = $_GET['client_id'];
    $transaction_amt = $_POST['transaction_amt'];
    $receiving_acc_no = $_POST['receiving_acc_no'];

    // Validate input amount
    if (!is_numeric($transaction_amt) || $transaction_amt <= 0) {
        $err = "Invalid amount. Please enter a valid amount.";
    } else {
        // Start Transaction
        $mysqli->autocommit(FALSE);

        // Fetch Sender & Receiver Account Details in a Single Query
        $query = "
            SELECT 
                sender.account_id AS sender_id, sender.acc_amount AS sender_balance, 
                sender.client_id AS sender_client_id, acc_type.name AS acc_type, acc_type.min_balance,
                receiver.account_id AS receiver_id, receiver.acc_amount AS receiver_balance
            FROM ib_bankaccounts sender
            JOIN ib_acc_types acc_type ON sender.acc_type_id = acc_type.acctype_id
            LEFT JOIN ib_bankaccounts receiver ON receiver.account_number = ?
            WHERE sender.account_id = ?;
        ";

        $stmt = $mysqli->prepare($query);
        $stmt->bind_param('si', $receiving_acc_no, $account_id);
        $stmt->execute();
        $stmt->bind_result($sender_id, $sender_balance, $sender_client_id, $acc_type, $min_balance, $receiver_id, $receiver_balance);
        $stmt->fetch();
        $stmt->close();

        if (!$sender_id) {
            $err = "Sender account not found.";
        } elseif (!$receiver_id) {
            $err = "Transaction Failed! Receiving account not found.";
        } elseif ($receiving_acc_no == $_POST['account_number']) {
            $err = "Transaction Failed! You cannot transfer money to your own account.";
        } elseif ($transaction_amt > $sender_balance) {
            $err = "Insufficient Balance! Your Current Balance is Rs. $sender_balance";
        } elseif (($sender_balance - $transaction_amt) < $min_balance) {
            $err = "Transaction Failed! You must maintain a minimum balance of Rs. $min_balance in your $acc_type account.";
        } else {
            // Deduct from Sender
            $new_sender_balance = $sender_balance - $transaction_amt;
            $update_sender_query = "UPDATE ib_bankaccounts SET acc_amount = ? WHERE account_id = ?";
            $stmt = $mysqli->prepare($update_sender_query);
            $stmt->bind_param('di', $new_sender_balance, $sender_id);
            $stmt->execute();
            $stmt->close();

            // Credit to Receiver
            $new_receiver_balance = $receiver_balance + $transaction_amt;
            $update_receiver_query = "UPDATE ib_bankaccounts SET acc_amount = ? WHERE account_id = ?";
            $stmt = $mysqli->prepare($update_receiver_query);
            $stmt->bind_param('di', $new_receiver_balance, $receiver_id);
            $stmt->execute();
            $stmt->close();

            // Insert Transaction
            $insert_transaction = "INSERT INTO ib_transactions (tr_code, account_id, tr_type, tr_status, client_id, transaction_amt, receiving_acc_no, created_at, is_active) 
                                    VALUES (?, ?, 'Transfer', 'Success', ?, ?, ?, NOW(), 1)";
            $stmt = $mysqli->prepare($insert_transaction);
            $stmt->bind_param('siisi', $tr_code, $sender_id, $sender_client_id, $transaction_amt, $receiving_acc_no);
            $stmt->execute();
            $stmt->close();

            // Commit Changes
            $mysqli->commit();
            $success = "Money Transferred Successfully!";
        }

        // Rollback on error
        if (isset($err)) {
            $mysqli->rollback();
        }

        // Enable Autocommit Again
        $mysqli->autocommit(TRUE);
    }
}
?>
<!DOCTYPE html>
<html>
<meta http-equiv="content-type" content="text/html;charset=utf-8" />
<?php include("dist/_partials/head.php"); ?>
<link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">

<body class="hold-transition sidebar-mini layout-fixed layout-navbar-fixed">
    <div class="wrapper">
        <!-- Navbar -->
        <?php include("dist/_partials/nav.php"); ?>
        <!-- /.navbar -->

        <!-- Main Sidebar Container -->
        <?php include("dist/_partials/sidebar.php"); ?>

        <!-- Content Wrapper. Contains page content -->
        <?php
        $account_id = $_GET['account_id'];
        $ret = "SELECT a.*, c.name AS client_name, c.phone AS client_phone, t.name AS acc_type 
        FROM iB_bankAccounts a 
        JOIN iB_clients c ON a.client_id = c.client_id 
        JOIN ib_acc_types t ON a.acc_type_id = t.acctype_id 
        WHERE a.account_id = ?";
        $stmt = $mysqli->prepare($ret);
        $stmt->bind_param('i', $account_id);
        $stmt->execute(); //ok
        $res = $stmt->get_result();
        $cnt = 1;
        while ($row = $res->fetch_object()) {

            ?>
            <div class="content-wrapper">
                <!-- Content Header (Page header) -->
                <section class="content-header">
                    <div class="container-fluid">
                        <div class="row mb-2">
                            <div class="col-sm-6">
                                <h1>Transfer Money</h1>
                            </div>
                            <div class="col-sm-6">
                                <ol class="breadcrumb float-sm-right">
                                    <li class="breadcrumb-item"><a href="pages_dashboard.php">Dashboard</a></li>
                                    <li class="breadcrumb-item"><a href="pages_transfer_money.php">Finances</a></li>
                                    <li class="breadcrumb-item"><a href="pages_transfer_money.php">Transfer</a></li>
                                    <li class="breadcrumb-item active"><?php echo $row->acc_name; ?></li>
                                </ol>
                            </div>
                        </div>
                    </div><!-- /.container-fluid -->
                </section>

                <!-- Main content -->
                <section class="content">
                    <div class="container-fluid">
                        <div class="row">
                            <!-- left column -->
                            <div class="col-md-12">
                                <!-- general form elements -->
                                <div class="card card-purple">
                                    <div class="card-header">
                                        <h3 class="card-title">Fill All Fields</h3>
                                    </div>
                                    <!-- form start -->
                                    <form method="post" enctype="multipart/form-data" role="form">
                                        <div class="card-body">

                                            <div class="row">
                                                <!-- <div class=" col-md-4 form-group">
                                                    <label for="exampleInputEmail1">Client Name</label>
                                                    <input type="text" readonly name="client_name"
                                                        value="<?php echo $row->name; ?>" required class="form-control"
                                                        id="exampleInputEmail1">
                                                </div> -->

                                                <!-- <div class=" col-md-8 form-group">
                                                    <label for="exampleInputEmail1">Client Phone Number</label>
                                                    <input type="text" readonly name="client_phone"
                                                        value="<?php echo $row->phone; ?>" required class="form-control"
                                                        id="exampleInputEmail1">
                                                </div> -->
                                            </div>

                                            <div class="row">
                                                <div class=" col-md-4 form-group">
                                                    <label for="exampleInputEmail1">Account Name</label>
                                                    <input type="text" readonly name="acc_name"
                                                        value="<?php echo $row->acc_name; ?>" required class="form-control"
                                                        id="exampleInputEmail1">
                                                </div>
                                                <div class=" col-md-4 form-group">
                                                    <label for="exampleInputPassword1">Account Number</label>
                                                    <input type="text" readonly value="<?php echo $row->account_number; ?>"
                                                        name="account_number" required class="form-control"
                                                        id="exampleInputEmail1">
                                                </div>
                                                <div class=" col-md-4 form-group">
                                                    <label for="exampleInputEmail1">Account Type | Category</label>
                                                    <input type="text" readonly name="acc_type"
                                                        value="<?php echo $row->acc_type; ?>" required class="form-control"
                                                        id="exampleInputEmail1">
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class=" col-md-6 form-group">
                                                    <label for="exampleInputEmail1">Transaction Code</label>
                                                    <?php
                                                    //PHP function to generate random account number
                                                    $length = 20;
                                                    $_transcode = substr(str_shuffle('0123456789QWERgfdsazxcvbnTYUIOqwertyuioplkjhmPASDFGHJKLMNBVCXZ'), 1, $length);
                                                    ?>
                                                    <input type="text" name="tr_code" readonly
                                                        value="<?php echo $_transcode; ?>" required class="form-control"
                                                        id="exampleInputEmail1">
                                                </div>

                                                <div class=" col-md-6 form-group">
                                                    <label for="exampleInputPassword1">Amount Transfered(Rs.)</label>
                                                    <input type="text" name="transaction_amt" required class="form-control"
                                                        id="exampleInputEmail1">
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-md-4 form-group">
                                                    <label for="receiving_acc_name">Receiving Account Name</label>
                                                    <input type="text" name="receiving_acc_name" id="receiving_acc_name"
                                                        required class="form-control" placeholder="Search account name...">
                                                </div>

                                                <div class="col-md-4 form-group">
                                                    <label for="receiving_acc_no">Receiving Account Number</label>
                                                    <input type="text" name="receiving_acc_no" id="receiving_acc_no"
                                                        readonly required class="form-control">
                                                </div>



                                                <!-- <div class=" col-md-4 form-group">
                                                    <label for="exampleInputPassword1">Receiving Account Holder</label>
                                                    <input type="text" name="receiving_acc_holder" required class="form-control" id="AccountHolder">
                                                </div> -->

                                                <div class=" col-md-4 form-group" style="display:none">
                                                    <label for="exampleInputPassword1">Transaction Type</label>
                                                    <input type="text" name="tr_type" value="Transfer" required
                                                        class="form-control" id="exampleInputEmail1">
                                                </div>
                                                <div class=" col-md-4 form-group" style="display:none">
                                                    <label for="exampleInputPassword1">Transaction Status</label>
                                                    <input type="text" name="tr_status" value="Success " required
                                                        class="form-control" id="exampleInputEmail1">
                                                </div>

                                            </div>

                                        </div>
                                        <!-- /.card-body -->
                                        <div class="card-footer">
                                            <button type="submit" name="deposit" class="btn btn-success">Transfer
                                                Funds</button>
                                        </div>
                                    </form>
                                </div>
                                <!-- /.card -->
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
    <!-- Include jQuery and jQuery UI for Autocomplete -->

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <!-- Bootstrap 4 -->
    <script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    <!-- bs-custom-file-input -->
    <script src="plugins/bs-custom-file-input/bs-custom-file-input.min.js"></script>
    <!-- AdminLTE App -->
    <script src="dist/js/adminlte.min.js"></script>
    <!-- AdminLTE for demo purposes -->
    <script src="dist/js/demo.js"></script>
    <script type="text/javascript">
        $(document).ready(function () {
            bsCustomFileInput.init();       
        });
        document.addEventListener("DOMContentLoaded", function () {
            document.querySelector("form").addEventListener("submit", function (event) {
                var senderAccount = "<?php echo $row->account_number; ?>"; // Fetch sender's account number
                var receivingAccount = document.getElementById("receiving_acc_no").value;

                if (receivingAccount === senderAccount) {
                    alert("Transaction Failed! You cannot transfer money to your own account.");
                    event.preventDefault(); // Prevent form submission
                }
            });
        });

    </script>
    <script>
        $(document).ready(function () {
            $("#receiving_acc_no").change(function () {
                var accountNumber = $(this).val();

                if (accountNumber) {
                    $("#selected_acc_no").val(accountNumber);
                } else {
                    $("#selected_acc_no").val("");
                }
            });
        });
    </script>
    <script>
        $(document).ready(function () {
            $("#receiving_acc_name").autocomplete({
                source: function (request, response) {
                    $.ajax({
                        url: "search_accounts.php",
                        type: "POST",
                        dataType: "json",
                        data: { search: request.term },
                        success: function (data) {
                            let filteredData = data.filter(item => item.value !== $("#logged_in_acc_no").val());
                            response(filteredData);
                        }
                    });
                },
                minLength: 1,
                select: function (event, ui) {
                    $("#receiving_acc_name").val(ui.item.label);
                    $("#receiving_acc_no").val(ui.item.value);
                    return false;
                }
            });
        });
    </script>
</body>

</html>