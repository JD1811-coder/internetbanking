<?php
include('conf/config.php');

if (isset($_POST['search'])) {
    $search = "%" . $_POST['search'] . "%";

    $query = "SELECT    , acc_name FROM ib_bankaccounts WHERE acc_name LIKE ?";
    $stmt = $mysqli->prepare($query);
    $stmt->bind_param('s', $search);
    $stmt->execute();
    $result = $stmt->get_result();

    $accounts = [];
    while ($row = $result->fetch_assoc()) {
        $accounts[] = [
            "label" => $row['acc_name'], // Account name for display
            "value" => $row['account_number'] // Account number for input field
        ];
    }

    echo json_encode($accounts);
}
?>
