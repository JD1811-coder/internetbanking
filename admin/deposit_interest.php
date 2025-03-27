<?php
session_start();
include('conf/config.php');
include('conf/checklogin.php');
check_login();
$admin_id = $_SESSION['admin_id'];

// Get current month and year
date_default_timezone_set('UTC');
$currentMonth = date('Y-m');

// Check if interest has already been deposited this month
$checkQuery = "SELECT COUNT(*) FROM interest_log WHERE month_year = ?";
$checkStmt = $mysqli->prepare($checkQuery);
$checkStmt->bind_param('s', $currentMonth);
$checkStmt->execute();
$checkStmt->bind_result($count);
$checkStmt->fetch();
$checkStmt->close();

if ($count > 0) {
    $_SESSION['error'] = "Interest has already been deposited for this month.";
    header("Location: pages_manage_acc_openings.php");
    exit();
}

// Fetch all active bank accounts
$accountsQuery = "SELECT ib_bankaccounts.account_id, ib_bankaccounts.acc_amount, ib_bankaccounts.acc_type_id
                   FROM ib_bankaccounts WHERE ib_bankaccounts.acc_status = 'Active'";
$result = $mysqli->query($accountsQuery);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $account_id = $row['account_id'];
        $balance = $row['acc_amount'];
        $acc_type_id = $row['acc_type_id'];

        // Fetch interest rate for the account type
        $rateQuery = "SELECT rate FROM ib_acc_types WHERE acctype_id = ?";
        $rateStmt = $mysqli->prepare($rateQuery);
        $rateStmt->bind_param('i', $acc_type_id);
        $rateStmt->execute();
        $rateStmt->bind_result($rate);
        $rateStmt->fetch();
        $rateStmt->close();

        if ($rate > 0) {
            // Calculate interest
            $interest = ($balance * $rate) / 100 / 12;
            $newBalance = $balance + $interest;

            // Update account balance
            $updateQuery = "UPDATE ib_bankaccounts SET acc_amount = ? WHERE account_id = ?";
            $updateStmt = $mysqli->prepare($updateQuery);
            $updateStmt->bind_param('di', $newBalance, $account_id);
            $updateStmt->execute();
            $updateStmt->close();
        }
    }
    
    // Log the transaction to prevent duplicate deposits
    $logQuery = "INSERT INTO interest_log (month_year, deposited_by) VALUES (?, ?)";
    $logStmt = $mysqli->prepare($logQuery);
    $logStmt->bind_param('si', $currentMonth, $admin_id);
    $logStmt->execute();
    $logStmt->close();

    $_SESSION['success'] = "Monthly interest deposited successfully!";
} else {
    $_SESSION['error'] = "No active accounts found.";
}

header("Location: pages_manage_acc_openings.php");
exit();
?>
