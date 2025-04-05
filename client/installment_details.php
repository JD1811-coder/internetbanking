<?php
session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include('conf/config.php');
include('conf/checklogin.php');
check_login();

if (!isset($_SESSION['client_id'])) {
    header('Content-Type: application/json');
    echo json_encode(['status' => 'error', 'message' => 'Session expired. Please log in again.']);
    exit;
}

$client_id = $_SESSION['client_id'];

// Fetch client balance
$account_query = "SELECT acc_amount FROM ib_bankaccounts WHERE client_id = ? AND is_active = 1";
$stmt = $mysqli->prepare($account_query);
$stmt->bind_param('i', $client_id);
$stmt->execute();
$account_result = $stmt->get_result();
$account_row = $account_result->fetch_assoc();
$client_balance = $account_row ? $account_row['acc_amount'] : 0;

// Validate loan ID
if (!isset($_GET['loan_id']) || !is_numeric($_GET['loan_id'])) {
    die("Invalid loan ID!");
}
$loan_id = $_GET['loan_id'];

// Fetch approved loan details
$query = "SELECT la.id, la.loan_type_id, lt.interest_rate, la.loan_amount, 
                 la.loan_duration_years, la.loan_duration_months, la.application_date 
          FROM loan_applications la
          INNER JOIN loan_types lt ON la.loan_type_id = lt.id 
          WHERE la.id = ? AND la.status = 'approved'";
$stmt = $mysqli->prepare($query);
$stmt->bind_param('i', $loan_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    die("Loan not found or not approved!");
}

$loan_data = $result->fetch_object();

// EMI schedule generator
function generate_due_dates($start_date, $months)
{
    $due_dates = [];
    $current_date = new DateTime($start_date);
    $current_date->modify('+1 month');

    for ($i = 0; $i < $months; $i++) {
        $random_day = rand(1, 28);
        $due_date = $current_date->format("Y-m-") . str_pad($random_day, 2, '0', STR_PAD_LEFT);
        $due_dates[] = $due_date;
        $current_date->modify('+1 month');
    }
    return $due_dates;
}

// EMI Payment AJAX Handler
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['pay_emi'])) {
    file_put_contents('log.txt', "Request received at " . date("Y-m-d H:i:s") . "\n", FILE_APPEND);
    // your existing code continues...


    $loan_id = $_POST['loan_id'];
    $emi_amount = $_POST['emi_amount'];
    $emi_date = $_POST['emi_date'];

    if ($client_balance >= $emi_amount) {
        // Step 1: Deduct from client account
        $stmt = $mysqli->prepare("UPDATE ib_bankaccounts SET acc_amount = acc_amount - ? WHERE client_id = ? AND is_active = 1");
        $stmt->bind_param('di', $emi_amount, $client_id);
        if (!$stmt->execute()) {
            echo json_encode(['status' => 'error', 'message' => 'Failed to update balance!']);
            exit;
        }

        // Step 2: Credit to admin account
        $stmt = $mysqli->prepare("UPDATE ib_bank_main_account SET total_balance = total_balance + ? WHERE id = ?");
        $admin_account_id = 1;
        $stmt->bind_param('di', $emi_amount, $admin_account_id);
        $stmt->execute();
        

        // Fetch the actual account_id for the client
        $stmt = $mysqli->prepare("SELECT account_id FROM ib_bankaccounts WHERE client_id = ? AND is_active = 1 LIMIT 1");
        $stmt->bind_param("i", $client_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $acc_row = $result->fetch_assoc();
        $account_id = $acc_row['account_id'] ?? null;

        if ($account_id) {
            $tr_code_client = bin2hex(random_bytes(10));
            $tr_type = 'Loan EMI';
            $tr_status = 'Success';
            $is_active = 1;
            $receiving_acc = null;
            $emi_amount_str = number_format($emi_amount, 2, '.', '');

            $stmt = $mysqli->prepare("INSERT INTO ib_transactions 
        (tr_code, account_id, tr_type, tr_status, client_id, transaction_amt, receiving_acc_no, is_active) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("sisssssi", $tr_code_client, $account_id, $tr_type, $tr_status, $client_id, $emi_amount_str, $receiving_acc, $is_active);
            $stmt->execute();
        }



        // Insert/update EMI payment
        $stmt = $mysqli->prepare("INSERT INTO loan_payments (client_id, loan_id, emi_date, amount, status) 
            VALUES (?, ?, ?, ?, 'paid') 
            ON DUPLICATE KEY UPDATE status = 'paid', amount = VALUES(amount)");
        $stmt->bind_param('iisd', $client_id, $loan_id, $emi_date, $emi_amount);
        header('Content-Type: application/json; charset=utf-8');
        ob_clean(); // clear any prior output

        if ($stmt->execute()) {
            echo json_encode(['status' => 'success', 'message' => "Payment successful! ₹$emi_amount has been deducted."]);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Payment processing failed!', 'sql_error' => $stmt->error]);
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Insufficient balance! Please deposit money.']);
    }
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>EMI Schedule</title>
    <?php include("dist/_partials/head.php"); ?>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body>
    <div class="wrapper">
        <?php include("dist/_partials/nav.php"); ?>
        <?php include("dist/_partials/sidebar.php"); ?>

        <div class="content-wrapper">
            <section class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1>EMI Schedule</h1>
                            <input type='hidden' name='loan_id' value='<?= htmlspecialchars($loan_data->id) ?>'>
                        </div>
                    </div>
                </div>
            </section>

            <section class="content">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card card-purple">
                                <div class="card-header">
                                    <h3 class="card-title">EMI Breakdown (Month-wise)</h3>
                                </div>
                                <div class="card-body">
                                    <table class="table table-bordered table-striped">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Month</th>
                                                <th>Due Date</th>
                                                <th>EMI Amount (₹)</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $principal = $loan_data->loan_amount;
                                            $years = $loan_data->loan_duration_years;
                                            $months = ($years * 12) + $loan_data->loan_duration_months;
                                            $rate = $loan_data->interest_rate;
                                            $total_duration_years = $years + ($loan_data->loan_duration_months / 12);
                                            $total_interest = ($principal * $rate * $total_duration_years) / 100;
                                            $emi = round(($principal + $total_interest) / $months);
                                            $due_dates = generate_due_dates($loan_data->application_date, $months);
                                            $current_date = new DateTime();

                                            for ($i = 0; $i < $months; $i++) {
                                                $due_date = new DateTime($due_dates[$i]);
                                                $due_date_str = $due_date->format("Y-m-d");

                                                $stmt = $mysqli->prepare("SELECT status FROM loan_payments 
    WHERE client_id = ? AND loan_id = ? AND emi_date = ? LIMIT 1");
                                                $stmt->bind_param('iis', $client_id, $loan_data->id, $due_date_str);
                                                $stmt->execute();
                                                $result_check = $stmt->get_result();
                                                $row = $result_check->fetch_assoc();
                                                $is_paid = $row && $row['status'] === 'paid';


                                                echo "<tr>";
                                                echo "<td>" . ($i + 1) . "</td>";
                                                echo "<td>Month " . ($i + 1) . "</td>";
                                                echo "<td>" . $due_date_str . "</td>";
                                                echo "<td>" . number_format($emi, 2) . "</td>";
                                                echo "<td>";

                                                if ($is_paid) {
                                                    echo "<button class='btn btn-success btn-sm' disabled>Paid</button>";
                                                } elseif ($due_date->format("Y-m") == $current_date->format("Y-m")) {
                                                    echo "<form id='paymentForm-" . $i . "' method='POST' action=''>
                                                    <input type='hidden' name='loan_id' value='" . htmlspecialchars($loan_data->id) . "'>
                                                    <input type='hidden' name='emi_date' value='" . $due_date_str . "'>
                                                    <input type='hidden' name='emi_amount' value='" . $emi . "'>
                                                  </form>
                                                  <button class='btn btn-primary btn-sm' 
                                                    data-loan-id='" . $loan_data->id . "' 
                                                    data-emi-index='" . $i . "' 
                                                    onclick='confirmPayment(" . $loan_data->id . ", " . $i . ", " . $emi . ", \"" . $due_date_str . "\")'>
                                                    Pay Now
                                                  </button>";
                                                } else {
                                                    echo "<button class='btn btn-secondary btn-sm' disabled>Upcoming</button>";
                                                }

                                                echo "</td></tr>";
                                            }
                                            ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>
        <?php include("dist/_partials/footer.php"); ?>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script>
            function confirmPayment(loanId, emiIndex, emiAmount, emiDate) {
                Swal.fire({
                    title: "Processing Payment...",
                    allowOutsideClick: false,
                    didOpen: () => Swal.showLoading()
                });

                $.ajax({
                    url: "", // same file
                    type: "POST",
                    data: {
                        pay_emi: '1',
                        loan_id: loanId,
                        emi_date: emiDate,
                        emi_amount: emiAmount
                    },
                    dataType: "json",
                    cache: false,
                    success: function (response) {
                        if (response.status === "success") {
                            Swal.fire("Success!", response.message, "success").then(() => {
                                let button = $("button[data-loan-id='" + loanId + "'][data-emi-index='" + emiIndex + "']");
                                button.replaceWith("<button class='btn btn-success btn-sm' disabled>Paid</button>");
                            });
                        } else {
                            Swal.fire("Error!", response.message, "error");
                        }
                    },
                    error: function (xhr, status, error) {
                        console.log("XHR:", xhr.responseText);
                        console.log("Status:", status);
                        console.log("Error:", error);
                        Swal.fire("Error!", "Something went wrong! Try again.", "error");
                    }
                });
            }

        </script>
</body>

</html>