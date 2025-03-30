<?php
session_start();
include('conf/config.php');
include('conf/checklogin.php');
check_login();
$staff_id = $_SESSION['staff_id'];

if (isset($_POST['deposit'])) {
    if (isset($_GET['account_id']) && isset($_GET['client_id'])) {

        $tr_code = $_POST['tr_code'];
        $account_id = $_GET['account_id'];
        $client_id = $_GET['client_id'];
        $transaction_amt = $_POST['transaction_amt'];
        $receiving_acc_no = $_POST['receiving_acc_no'];

        // Validate input
        if (!is_numeric($transaction_amt) || $transaction_amt <= 0) {
            $err = "Invalid amount. Please enter an amount greater than zero.";
        } else {
            // Start Database Transaction
            $mysqli->autocommit(FALSE);

            // Fetch Sender's Current Balance
            $query = "SELECT acc_amount FROM ib_bankaccounts WHERE account_id = ?";
            $stmt = $mysqli->prepare($query);
            $stmt->bind_param('i', $account_id);
            $stmt->execute();
            $stmt->bind_result($sender_balance);
            $stmt->fetch();
            $stmt->close();

            // Fetch Receiver's Account ID & Current Balance
            $query = "SELECT account_id, acc_amount FROM ib_bankaccounts WHERE account_number = ?";
            $stmt = $mysqli->prepare($query);
            $stmt->bind_param('s', $receiving_acc_no);
            $stmt->execute();
            $stmt->bind_result($receiver_account_id, $receiver_balance);
            $stmt->fetch();
            $stmt->close();

            $account_id = $_GET['account_id']; // Get account_id from URL

            $query = "SELECT b.acc_amount, t.name AS acc_type 
          FROM ib_bankaccounts b
          JOIN ib_acc_types t ON b.acc_type_id = t.acctype_id
          WHERE b.account_id = ?";


            $stmt = $mysqli->prepare($query);
            $stmt->bind_param('i', $account_id);
            $stmt->execute();
            $stmt->bind_result($acc_amount, $acc_type);
            $stmt->fetch();
            $stmt->close();
            $query = "SELECT acc_amount FROM ib_bankaccounts WHERE account_id = ?";
            $stmt = $mysqli->prepare($query);
            $stmt->bind_param('i', $account_id);
            $stmt->execute();
            $stmt->bind_result($acc_amount);
            $stmt->fetch();
            $stmt->close();

            // Ensure $acc_amount is not null
            if ($acc_amount === null) {
                $acc_amount = 0;  // Default to 0 if account is not found
            }


            $query = "SELECT acc_amount FROM ib_bankaccounts WHERE account_id = ?";
            $stmt = $mysqli->prepare($query);
            $stmt->bind_param('i', $account_id);
            $stmt->execute();
            $stmt->bind_result($acc_amount);
            $stmt->fetch();
            $stmt->close();

            // Ensure $acc_amount is not null
            if ($acc_amount !== null) {
                $acc_amount = 0;  // Default to 0 if account is not found
            }

            // Fetch Minimum Balance for the Account Type
            $query = "SELECT min_balance FROM ib_acc_types WHERE name = ?";
            $stmt = $mysqli->prepare($query);
            $stmt->bind_param('s', $acc_type);
            $stmt->execute();
            $stmt->bind_result($min_balance);
            $stmt->fetch();
            $stmt->close();

            // Calculate the new sender balance after deduction
            $new_sender_balance = $sender_balance - $transaction_amt;

            // Validate if sender maintains the required minimum balance
            if ($new_sender_balance < $min_balance) {
                echo '<script>
                document.addEventListener("DOMContentLoaded", function() {
                    Swal.fire({
                        icon: "error",
                        title: "Transaction Failed!",
                        text: "You must maintain a minimum balance of Rs. ' . $min_balance . ' in your ' . $acc_type . ' account.",
                        confirmButtonText: "OK"
                    });
                });
                </script>';
            } elseif ($transaction_amt > $sender_balance) {
                echo '<script>
                document.addEventListener("DOMContentLoaded", function() {
                    Swal.fire({
                        icon: "error",
                        title: "Insufficient Balance!",
                        text: "Your Current Balance is Rs. ' . $sender_balance . '",
                        confirmButtonText: "OK"
                    });
                });
                </script>';
            } elseif (!is_numeric($transaction_amt) || $transaction_amt <= 0) {
                echo '<script>
                document.addEventListener("DOMContentLoaded", function() {
                    Swal.fire({
                        icon: "error",
                        title: "Invalid Amount!",
                        text: "Please enter an amount greater than zero.",
                        confirmButtonText: "OK"
                    });
                });
                </script>';
            } elseif ($account_id == $receiver_account_id) {
                echo '<script>
                document.addEventListener("DOMContentLoaded", function() {
                    Swal.fire({
                        icon: "error",
                        title: "Transaction Failed!",
                        text: "You cannot transfer money to your own account.",
                        confirmButtonText: "OK"
                    });
                });
                </script>';
            } 
            else
            {


                // Deduct amount from sender
                $new_sender_balance = $sender_balance - $transaction_amt;
                $update_sender_query = "UPDATE ib_bankaccounts SET acc_amount = ? WHERE account_id = ?";
                $stmt = $mysqli->prepare($update_sender_query);
                $stmt->bind_param('di', $new_sender_balance, $account_id);
                $stmt->execute();
                $stmt->close();

                // Add amount to receiver
                $new_receiver_balance = $receiver_balance + $transaction_amt;
                $update_receiver_query = "UPDATE ib_bankaccounts SET acc_amount = ? WHERE account_id = ?";
                $stmt = $mysqli->prepare($update_receiver_query);
                $stmt->bind_param('di', $new_receiver_balance, $receiver_account_id);
                $stmt->execute();
                $stmt->close();

                // Insert Transaction Record
                $insert_transaction = "INSERT INTO ib_transactions (tr_code, account_id, tr_type, tr_status, client_id, transaction_amt, receiving_acc_no, created_at, is_active) 
                                       VALUES (?, ?, 'Transfer', 'Success', ?, ?, ?, NOW(), 1)";
                $stmt = $mysqli->prepare($insert_transaction);
                $stmt->bind_param('siisi', $tr_code, $account_id, $client_id, $transaction_amt, $receiving_acc_no);
                $stmt->execute();
                $stmt->close();

                // Commit Changes
                $mysqli->commit();
                echo '<script>
                document.addEventListener("DOMContentLoaded", function() {
                    Swal.fire({
                        icon: "success",
                        title: "Success!",
                        text: "Money Transferred Successfully!",
                        confirmButtonText: "OK"
                    }).then(() => {
                        window.location.href = "pages_transfers.php";
                    });
                });
                </script>';


            }

            // Enable Autocommit Again
            $mysqli->autocommit(TRUE);
        }
    } else {
        $err = "Required parameters not set.";
    }
}
?>

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
                                                </div>

                                                <div class=" col-md-8 form-group">
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
    <!-- Bootstrap 4 -->
    <script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    <!-- bs-custom-file-input -->
    <script src="plugins/bs-custom-file-input/bs-custom-file-input.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- AdminLTE App -->
    <script src="dist/js/adminlte.min.js"></script>
    <!-- AdminLTE for demo purposes -->
    <script src="dist/js/demo.js"></script>
    <script>
    document.addEventListener("DOMContentLoaded", function () {
    document.querySelector("form").addEventListener("submit", function (event) {
        var senderAccountNumber = "<?php echo $row->account_number; ?>"; // Get sender's account number
        var receivingAccountNumber = document.getElementById("receiving_acc_no").value;
        var enteredAmount = parseFloat(document.querySelector('input[name="transaction_amt"]').value);

        if (receivingAccountNumber === senderAccountNumber) {
            Swal.fire({
                icon: "error",
                title: "Invalid Transfer",
                text: "You cannot transfer money to your own account.",
                confirmButtonText: "OK"
            });
            event.preventDefault();
            return;
        }

        if (enteredAmount <= 0 || isNaN(enteredAmount)) {
            Swal.fire({
                icon: "error",
                title: "Invalid Amount",
                text: "Please enter a valid amount greater than zero.",
                confirmButtonText: "OK"
            });
            event.preventDefault();
        }
    });
});

</script>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            document.querySelector("form").addEventListener("submit", function (event) {
                var transactionAmount = parseFloat(document.querySelector('input[name="transaction_amt"]').value);

                if (isNaN(transactionAmount) || transactionAmount <= 0) {
                    Swal.fire({
                        icon: "error",
                        title: "Invalid Amount!",
                        text: "Amount must be greater than zero.",
                        confirmButtonText: "OK"
                    });
                    event.preventDefault();
                }
            });
        });

    </script>
    <script>
document.addEventListener("DOMContentLoaded", function () {
    document.getElementById("receiving_acc_name").addEventListener("input", function () {
        var inputValue = this.value;
        var regex = /^[A-Za-z\s]*$/; // Only allows letters and spaces

        if (!regex.test(inputValue)) {
            Swal.fire({
                icon: "error",
                title: "Invalid Input",
                text: "Receiving Account Name can only contain letters.",
                confirmButtonText: "OK"
            });

            // Remove the last entered invalid character
            this.value = inputValue.replace(/[^A-Za-z\s]/g, '');
        }
    });
});
</script>
    <script>
        $(document).ready(function () {
            $('#receiving_acc_name').on('input', function () {
                var receivingAccName = $(this).val();

                if (receivingAccName.length >= 2) {
                    $.ajax({
                        url: 'fetch_accounts.php',
                        method: 'POST',
                        data: { receiving_acc_name: receivingAccName },
                        success: function (data) {
                            const accounts = JSON.parse(data);

                            if (accounts.length > 0) {
                                $('#receiving_acc_no').val(accounts[0]); // Auto-fill the first match
                            } else {
                                $('#receiving_acc_no').val(''); // Clear the account number if no match
                            }
                        },
                        error: function () {
                            Swal.fire({
                                icon: "error",
                                title: "Error",
                                text: "Failed to fetch account details. Please try again.",
                                confirmButtonText: "OK"
                            });
                        }
                    });
                } else {
                    $('#receiving_acc_no').val(''); // Clear if input is less than 2 characters
                }
            });
        });
    </script>


    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</body>

</html>