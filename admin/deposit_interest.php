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

// Fetch main bank account balance
$mainBankQuery = "SELECT total_balance FROM ib_bank_main_account WHERE id = 1";
$mainBankResult = $mysqli->query($mainBankQuery);
$mainBankRow = $mainBankResult->fetch_assoc();
$mainBankBalance = $mainBankRow['total_balance'];

// Fetch all active bank accounts
$accountsQuery = "SELECT ib_bankaccounts.account_id, ib_bankaccounts.acc_amount, ib_bankaccounts.acc_type_id, ib_bankaccounts.client_id
                   FROM ib_bankaccounts WHERE ib_bankaccounts.is_active = '1'";
$result = $mysqli->query($accountsQuery);

$totalInterestPaid = 0;

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $account_id = $row['account_id'];
        $balance = $row['acc_amount'];
        $acc_type_id = $row['acc_type_id'];
        $client_id = $row['client_id'];

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
            $interest = number_format(($balance * $rate) / 100 / 12, 2, '.', '');
            $totalInterestPaid += $interest; // Track total interest paid

            // Update user's account balance
            $newBalance = $balance + $interest;
            $updateQuery = "UPDATE ib_bankaccounts SET acc_amount = ? WHERE account_id = ?";
            $updateStmt = $mysqli->prepare($updateQuery);
            $updateStmt->bind_param('di', $newBalance, $account_id);
            $updateStmt->execute();
            $updateStmt->close();

            // Insert transaction entry for the client
            $tr_code = bin2hex(random_bytes(10));
            $tr_type = "Deposit";
            $tr_status = "Success";
            $is_active = 1;
            $created_at = date('Y-m-d H:i:s');

            $transactionQuery = "INSERT INTO ib_transactions (tr_code, account_id, tr_type, tr_status, client_id, transaction_amt, created_at, is_active) 
                                 VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
            $trStmt = $mysqli->prepare($transactionQuery);
            $trStmt->bind_param('sissddsi', $tr_code, $account_id, $tr_type, $tr_status, $client_id, $interest, $created_at, $is_active);
            $trStmt->execute();
            $trStmt->close();
        }
    }

    // Deduct 2× total interest paid from the main bank account balance
    $deductionAmount = $totalInterestPaid * 2;
    $newMainBankBalance = $mainBankBalance - $deductionAmount;
    $updateMainBankQuery = "UPDATE ib_bank_main_account SET total_balance = ? WHERE id = 1";
    $updateMainBankStmt = $mysqli->prepare($updateMainBankQuery);
    $updateMainBankStmt->bind_param('d', $newMainBankBalance);
    $updateMainBankStmt->execute();
    $updateMainBankStmt->close();

    // Log the transaction to prevent duplicate deposits
    $logQuery = "INSERT INTO interest_log (month_year, deposited_by) VALUES (?, ?)";
    $logStmt = $mysqli->prepare($logQuery);
    $logStmt->bind_param('si', $currentMonth, $admin_id);
    $logStmt->execute();
    $logStmt->close();

    $_SESSION['success'] = "Monthly interest deposited successfully";
} else {
    $_SESSION['error'] = "No active accounts found.";
}

header("Location: pages_manage_acc_openings.php");
exit();
?>
