<?php
include('conf/config.php');

if (isset($_POST['receiving_acc_name'])) {
    $receiving_acc_name = trim($_POST['receiving_acc_name']);

    // Search for matching account names
    $query = "SELECT account_number FROM ib_bankaccounts 
              JOIN ib_clients ON ib_bankaccounts.client_id = ib_clients.client_id 
              WHERE ib_clients.name LIKE CONCAT('%', ?, '%')";
    
    $stmt = $mysqli->prepare($query);
    $stmt->bind_param('s', $receiving_acc_name);
    $stmt->execute();
    $stmt->bind_result($account_number);

    $accounts = [];
    while ($stmt->fetch()) {
        $accounts[] = $account_number;
    }
    $stmt->close();

    // Return as JSON for AJAX response
    echo json_encode($accounts);
}
?>
