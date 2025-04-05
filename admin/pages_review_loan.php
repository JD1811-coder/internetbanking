<?php
session_start();
include('conf/config.php');
include('conf/checklogin.php');
check_login();
$admin_id = $_SESSION['admin_id'];

// Determine the loan ID from GET or POST
if (isset($_GET['id']) || isset($_POST['id'])) {
    $id = isset($_GET['id']) ? intval($_GET['id']) : intval($_POST['id']);
} else {
    die("No loan ID provided.");
}

// Fetch loan details
$query = "SELECT la.*, lt.type_name, lt.max_amount, lt.interest_rate, 
                 la.loan_duration_years, la.loan_duration_months
          FROM loan_applications la
          LEFT JOIN loan_types lt ON la.loan_type_id = lt.id
          WHERE la.id = ?";

$stmt = $mysqli->prepare($query);
if (!$stmt) {
    error_log("Query Preparation Failed: " . $mysqli->error);
    die("An error occurred. Please try again later.");
}
$stmt->bind_param('i', $id);
$stmt->execute();
$result = $stmt->get_result();
$loan = $result->fetch_object();
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $decision = $_POST['decision'];
    $admin_remark = $_POST['admin_remark'];

    if ($decision !== 'approved' && $decision !== 'rejected') {
        die("Invalid decision.");
    }

    $update = "UPDATE loan_applications SET 
               status = ?, 
               admin_remark = ?, 
               admin_review_id = ?, 
               review_date = NOW() 
               WHERE id = ?";
    $stmt = $mysqli->prepare($update);
    $stmt->bind_param('ssii', $decision, $admin_remark, $admin_id, $id);
    $stmt->execute();

    if ($decision === 'approved') {
        // Fetch loan amount and client_id from loan
        $loan_amount = $loan->loan_amount;
        $client_id = $loan->client_id;
    
        // 1. Get client's bank account
        $acc_query = $mysqli->prepare("SELECT * FROM ib_bankaccounts WHERE client_id = ? AND is_active = 1 LIMIT 1");
        $acc_query->bind_param("i", $client_id);
        $acc_query->execute();
        $acc_result = $acc_query->get_result();
        $client_acc = $acc_result->fetch_object();
    
        if (!$client_acc) {
            die("Client bank account not found.");
        }
    
        $client_account_id = $client_acc->account_id;
        $client_account_number = $client_acc->account_number;
    
        // 2. Debit from bank main account
        $main_query = $mysqli->query("SELECT * FROM ib_bank_main_account WHERE id = 1");
        $main_account = $main_query->fetch_object();
    
        if ($main_account->total_balance < $loan_amount) {
            die("Insufficient funds in the bank main account.");
        }
    
        $new_main_balance = $main_account->total_balance - $loan_amount;
        $mysqli->query("UPDATE ib_bank_main_account SET total_balance = $new_main_balance WHERE id = 1");
    
        // 3. Credit to client bank account
        $new_client_balance = $client_acc->acc_amount + $loan_amount;
        $mysqli->query("UPDATE ib_bankaccounts SET acc_amount = $new_client_balance WHERE account_id = $client_account_id");

        
        $tr_code = bin2hex(random_bytes(10)); // 20-char unique code
        $tr_type = 'Deposit';
        $tr_status = 'Success';
        $is_active = 1;
        
        $stmt_txn = $mysqli->prepare("INSERT INTO ib_transactions 
            (tr_code, account_id, tr_type, tr_status, client_id, transaction_amt, receiving_acc_no, is_active) 
            VALUES (?, ?, ?, ?, ?, ?, NULL, ?)");
        
        $stmt_txn->bind_param("sissssi", $tr_code, $client_account_id, $tr_type, $tr_status, $client_id, $loan_amount, $is_active);
        $stmt_txn->execute();
        
    }
    
    header("Location: pages_review_loan_list.php");
    exit;
}

?>
<!DOCTYPE html>
<html>
<?php include("dist/_partials/head.php"); ?>

<body class="hold-transition sidebar-mini">
    <div class="wrapper">
        <?php include("dist/_partials/nav.php"); ?>
        <?php include("dist/_partials/sidebar.php"); ?>

        <div class="content-wrapper">
            <section class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1>Review Loan Application</h1>
                        </div>
                    </div>
                </div>
            </section>

            <section class="content">
                <div class="container-fluid">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Application Details</h3>
                        </div>
                        <div class="card-body">
                            <dl class="row">
                                <dt class="col-sm-3">Applicant Name:</dt>
                                <dd class="col-sm-9"><?php echo htmlspecialchars($loan->applicant_name); ?></dd>

                                <dt class="col-sm-3">Loan Type:</dt>
                                <dd class="col-sm-9">
                                    <?php echo htmlspecialchars($loan->type_name); ?>
                                    <!-- (Max: Rs. <?php echo number_format($loan->max_amount); ?>) -->
                                </dd>

                                <dt class="col-sm-3">Requested Amount:</dt>
                                <dd class="col-sm-9">Rs. <?php echo number_format($loan->loan_amount, 2); ?></dd>

                                <dt class="col-sm-3">Application Date:</dt>
                                <dd class="col-sm-9">
                                    <?php echo date('d/m/Y H:i', strtotime($loan->application_date)); ?>
                                </dd>
                                <dt class="col-sm-3">Loan Duration:</dt>
                                <dd class="col-sm-9">
                                    <?php
                                    $loanDuration = "";
                                    if (!empty($loan->loan_duration_years) && $loan->loan_duration_years > 0) {
                                        $loanDuration .= $loan->loan_duration_years . " Year" . ($loan->loan_duration_years > 1 ? "s" : "");
                                    }
                                    if (!empty($loan->loan_duration_months) && $loan->loan_duration_months > 0) {
                                        if (!empty($loanDuration)) {
                                            $loanDuration .= " ";
                                        }
                                        $loanDuration .= $loan->loan_duration_months . " Month" . ($loan->loan_duration_months > 1 ? "s" : "");
                                    }
                                    echo !empty($loanDuration) ? htmlspecialchars($loanDuration) : "N/A";
                                    ?>
                                </dd>


                                <dt class="col-sm-3">Staff Remarks:</dt>
                                <dd class="col-sm-9">
                                    <input type="text" class="form-control"
                                        value="<?php echo htmlspecialchars($loan->staff_remark); ?>" readonly>
                                </dd>
                            </dl>

                            <form method="post">
                                <!-- Pass the loan ID with a hidden input field -->
                                <input type="hidden" name="id" value="<?php echo htmlspecialchars($loan->id); ?>">

                                <div class="form-group">
                                    <label>Review Decision:</label>
                                    <div class="custom-control custom-radio">
                                        <input class="custom-control-input" type="radio" id="approved" name="decision"
                                            value="approved" required>
                                        <label class="custom-control-label" for="approved">Approve</label>
                                    </div>
                                    <div class="custom-control custom-radio">
                                        <input class="custom-control-input" type="radio" id="rejected" name="decision"
                                            value="rejected">
                                        <label class="custom-control-label" for="rejected">Reject</label>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label>Admin Remarks:</label>
                                    <textarea class="form-control" name="admin_remark" rows="3" required></textarea>
                                </div>

                                <button type="submit" class="btn btn-primary">Submit Review</button>
                            </form>
                        </div>
                    </div>
                </div>
            </section>
        </div>
        <?php include("dist/_partials/footer.php"); ?>
    </div>
</body>

</html>