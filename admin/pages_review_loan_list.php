<?php
session_start();
include('conf/config.php');
include('conf/checklogin.php');
check_login();
$admin_id = $_SESSION['admin_id'];

if (isset($_POST['approve_loan'])) {
    $loan_id = intval($_POST['loan_id']);
    $staff_id = $_SESSION['admin_id']; // Logged-in staff approving the loan

    // Fetch loan details
    $query = "SELECT la.loan_amount, la.client_id, ib.acc_amount
              FROM loan_applications la
              JOIN ib_bankaccounts ib ON la.client_id = ib.client_id
              WHERE la.id = ?";

    $stmt = $mysqli->prepare($query);
    $stmt->bind_param('i', $loan_id);
    $stmt->execute();
    $stmt->bind_result($loan_amount, $client_id, $account_id);
    $stmt->fetch();
    $stmt->close();

    if ($loan_amount && $client_id && $account_id) {
        // Update loan status to 'approved'
        $updateLoan = "UPDATE loan_applications SET status='approved', reviewed_by=? WHERE id=?";
        $stmt = $mysqli->prepare($updateLoan);
        $stmt->bind_param('ii', $staff_id, $loan_id);
        $stmt->execute();
        $stmt->close();

        // Insert a transaction for loan disbursement
        $tr_code = strtoupper(uniqid('LN')); // Unique transaction code
        $tr_type = 'Deposit';
        $tr_desc = 'Loan Disbursement';
        $stmt = $mysqli->prepare("INSERT INTO iB_Transactions (tr_code, account_id, client_id, tr_type, transaction_amt, description) 
                                  VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param('siisds', $tr_code, $account_id, $client_id, $tr_type, $loan_amount, $tr_desc);
        $stmt->execute();
        $stmt->close();

        $info = "Loan Approved and Credited to Client's Account";
    } else {
        $err = "Error processing loan approval.";
    }
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
LEFT JOIN ib_staff s ON la.reviewed_by = s.id
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
                                                    echo "<td>
                                    <a href=\"pages_review_loan.php?id={$row->id}\" class=\"btn btn-primary btn-sm\">
                                        <i class=\"fas fa-search\"></i> Review
                                    </a>
                                  </td>";
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
