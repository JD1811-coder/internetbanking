<?php
session_start();
include('conf/config.php');
include('conf/checklogin.php');
check_login();
$admin_id = $_SESSION['admin_id'];
function generateEmiSchedule($mysqli, $loan_id, $application_date, $loan_amount, $interest_rate, $years, $months) {
    $total_months = ($years * 12) + $months;
    $duration_years = $years + ($months / 12);
    $total_interest = ($loan_amount * $interest_rate * $duration_years) / 100;
    $emi_amount = round(($loan_amount + $total_interest) / $total_months);

    $start_date = new DateTime($application_date);
    $start_date->modify('+1 month');

    for ($i = 1; $i <= $total_months; $i++) {
        $random_day = rand(1, 28);
        $due_date = $start_date->format("Y-m-") . str_pad($random_day, 2, '0', STR_PAD_LEFT);

        $stmt = $mysqli->prepare("INSERT INTO loan_emi_schedule (loan_id, emi_number, due_date, amount, status) 
                                  VALUES (?, ?, ?, ?, 'pending')");
        $stmt->bind_param('iisd', $loan_id, $i, $due_date, $emi_amount);
        $stmt->execute();

        $start_date->modify('+1 month');
    }
}

if (isset($_POST['approve_loan'])) {
    $loan_id = intval($_POST['loan_id']);
    $staff_id = $_SESSION['admin_id']; // Logged-in staff approving the loan

    // Fetch loan details
    $query = "SELECT la.loan_amount, la.client_id, ib.acc_amount, ib.account_id, la.application_date,
    lt.interest_rate, la.loan_duration_years, la.loan_duration_months
FROM loan_applications la
JOIN ib_bankaccounts ib ON la.client_id = ib.client_id
JOIN loan_types lt ON la.loan_type_id = lt.id
WHERE la.id = ?";

    $stmt = $mysqli->prepare($query);
    $stmt->bind_param('i', $loan_id);
    $stmt->execute();
    $stmt->bind_result($loan_amount, $client_id, $client_balance, $client_account_id, $application_date,
    $interest_rate, $loan_duration_years, $loan_duration_months);

    $stmt->fetch();
    $stmt->close();

    if ($loan_amount && $client_id && $client_account_id) {
        $mysqli->begin_transaction(); // Start Transaction

        try {
            // ✅ Update Loan Status to Approved
            $updateLoan = "UPDATE loan_applications SET status='approved', reviewed_by=? WHERE id=?";
            $stmt = $mysqli->prepare($updateLoan);
            $stmt->bind_param('ii', $staff_id, $loan_id);
            $stmt->execute();
            $stmt->close();

            // ✅ Credit Loan Amount to Client’s Account
            $new_client_balance = $client_balance + $loan_amount;
            $updateClientAccount = "UPDATE ib_bankaccounts SET acc_amount = ? WHERE client_id = ?";
            $stmt = $mysqli->prepare($updateClientAccount);
            $stmt->bind_param('di', $new_client_balance, $client_id);
            $stmt->execute();
            $stmt->close();

            // ✅ Debit Loan Amount from Bank’s Main Account
            $bank_account_id = 1; // Assuming Bank’s Main Account ID is 1
            $query = "SELECT acc_amount FROM ib_bankaccounts WHERE account_id = ?";
            $stmt = $mysqli->prepare($query);
            $stmt->bind_param('i', $bank_account_id);
            $stmt->execute();
            $stmt->bind_result($bank_balance);
            $stmt->fetch();
            $stmt->close();

            if ($bank_balance >= $loan_amount) {
                $new_bank_balance = $bank_balance - $loan_amount;
                $updateBankAccount = "UPDATE ib_bankaccounts SET acc_amount = ? WHERE account_id = ?";
                $stmt = $mysqli->prepare($updateBankAccount);
                $stmt->bind_param('di', $new_bank_balance, $bank_account_id);
                $stmt->execute();
                $stmt->close();
            } else {
                throw new Exception("Bank does not have enough balance to disburse loan.");
            }

            // ✅ Insert Loan Disbursement Transaction for Client
            $tr_code = strtoupper(uniqid('LN'));
            $tr_type = 'Deposit';
            $tr_desc = 'Loan Disbursement';
            $stmt = $mysqli->prepare("INSERT INTO iB_Transactions (tr_code, account_id, client_id, tr_type, transaction_amt, description) 
                                      VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->bind_param('siisds', $tr_code, $client_account_id, $client_id, $tr_type, $loan_amount, $tr_desc);
            $stmt->execute();
            $stmt->close();

            // ✅ Insert Loan Deduction Transaction for Bank
            $tr_code_bank = strtoupper(uniqid('BNK'));
            $tr_type_bank = 'Withdraw';
            $tr_desc_bank = 'Loan Disbursement Deduction';
            $stmt = $mysqli->prepare("INSERT INTO iB_Transactions (tr_code, account_id, client_id, tr_type, transaction_amt, description) 
                                      VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->bind_param('siisds', $tr_code_bank, $bank_account_id, $client_id, $tr_type_bank, $loan_amount, $tr_desc_bank);
            $stmt->execute();
            $stmt->close();
            $mysqli->commit(); // Commit Transaction
// ✅ Generate and Insert EMI Schedule
generateEmiSchedule($mysqli, $loan_id, $application_date, $loan_amount, $interest_rate, $loan_duration_years, $loan_duration_months);


            $_SESSION['loan_approved'] = "Loan of Rs. " . number_format($loan_amount, 2) . " has been disbursed.";
        } catch (Exception $e) {
            $mysqli->rollback(); // Rollback on failure
            $_SESSION['loan_error'] = "Error: " . $e->getMessage();
        }
    } else {
        $_SESSION['loan_error'] = "Error processing loan approval.";
    }

    // header("Location: pages_loans.php");
    exit();
}

// Fetch all loan applications
$loanQuery = "SELECT 
    la.id, 
    c.name AS applicant_name, 
    lt.type_name, 
    la.loan_amount, 
    la.income_salary, 
    la.application_date, 
    la.loan_duration_years, 
    la.loan_duration_months, 
    la.status, 
    s.name AS reviewer_name, 
    la.staff_remark 
FROM loan_applications la
JOIN ib_clients c ON la.client_id = c.client_id
JOIN loan_types lt ON la.loan_type_id = lt.id
LEFT JOIN ib_staff s ON la.reviewed_by = s.staff_id
WHERE la.status != 'pending'
ORDER BY la.application_date DESC";


$loanResult = $mysqli->query($loanQuery);
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Loan Applications</title>
    <?php include("dist/_partials/head.php"); ?>
</head>

<body class="hold-transition sidebar-mini layout-fixed layout-navbar-fixed">
    <!-- Include SweetAlert -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>


    <div class="wrapper">
        <!-- Navigation & Sidebar -->
        <?php include("dist/_partials/nav.php"); ?>
        <?php include("dist/_partials/sidebar.php"); ?>

        <div class="content-wrapper">
            <!-- Content Header -->
            <section class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1>Loan Applications</h1>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="pages_dashboard.php">Dashboard</a></li>
                                <li class="breadcrumb-item active">Loan Applications</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Main content -->
            <section class="content">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">Select any application to review</h3>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table id="example1" class="table table-striped table-bordered table-hover">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Applicant Name</th>
                                                <th>Loan Type</th>
                                                <th>Loan Amount</th>
                                                <th>Income/Salary</th>
                                                <th>Application Date</th>
                                                <th>Loan Duration</th>
                                                <th>Status</th>
                                                <th>Reviewed By</th>
                                                <th>Staff Remarks</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $cnt = 1;
                                            if ($loanResult->num_rows > 0) {
                                                while ($row = $loanResult->fetch_object()) {
                                                    echo "<tr>";
                                                    echo "<td>{$cnt}</td>";
                                                    echo "<td>" . htmlspecialchars($row->applicant_name ?? '') . "</td>";
                                                    echo "<td>" . htmlspecialchars($row->type_name ?? '') . "</td>";
                                                    echo "<td><strong>Rs. " . number_format($row->loan_amount, 2) . "</strong></td>";
                                                    echo "<td><strong>Rs. " . number_format($row->income_salary, 2) . "</strong></td>";
                                                    echo "<td>" . date('d/m/Y H:i', strtotime($row->application_date)) . "</td>";
                                                    echo "<td>" . htmlspecialchars($row->loan_duration_years ?? 0) . " Years " .
                                                        htmlspecialchars($row->loan_duration_months ?? 0) . " Months</td>";

                                                    $badgeClass = match ($row->status) {
                                                        'approved' => 'success',
                                                        'recommended', 'pending_admin' => 'warning',
                                                        'pending' => 'secondary',
                                                        default => 'danger',
                                                    };
                                                    echo "<td><span class=\"badge badge-$badgeClass\">" . ucfirst($row->status) . "</span></td>";

                                                    echo "<td>" . htmlspecialchars($row->reviewer_name ?? '') . "</td>";
                                                    echo "<td>" . htmlspecialchars($row->staff_remark ?? '') . "</td>";
                                                    echo "<td>";
                                                    if ($row->status === 'approved') {
                                                        echo "<button class=\"btn btn-secondary btn-sm\" disabled>
                                                                <i class=\"fas fa-check\"></i> Approved
                                                              </button>";
                                                    } else {
                                                        echo "<a href=\"pages_review_loan.php?id={$row->id}\" class=\"btn btn-primary btn-sm\">
                                                                <i class=\"fas fa-search\"></i> Review
                                                              </a>";
                                                    }
                                                    echo "</td>";
                                                    
                                                    echo "</tr>";
                                                    $cnt++;
                                                }
                                            } else {
                                                echo "<tr><td colspan=\"11\" class=\"text-center text-muted\">No loan applications found.</td></tr>";
                                            }
                                            ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
            </section>
        </div>

        <?php include("dist/_partials/footer.php"); ?>
    </div>

    <!-- jQuery -->
    <script src="plugins/jquery/jquery.min.js"></script>
    <!-- Bootstrap 4 -->
    <script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    <!-- DataTables -->
    <script src="plugins/datatables/jquery.dataTables.js"></script>
    <script src="plugins/datatables-bs4/js/dataTables.bootstrap4.js"></script>
    <!-- AdminLTE App -->
    <script src="dist/js/adminlte.min.js"></script>
    <!-- Page script -->
    <script>
        $(function () {
            $("#example1").DataTable({
                "paging": true,
                "lengthChange": true,
                "searching": true,
                "ordering": true,
                "info": true,
                "autoWidth": false,
            });
        });
    </script>
</body>
</html>
