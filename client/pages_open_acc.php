<?php
session_start();
include('conf/config.php');
include('conf/checklogin.php');
check_login();
$client_id = $_SESSION['client_id'];

if (isset($_POST['open_account'])) {
    $acc_name = trim($_POST['acc_name']);
    $account_number = $_POST['account_number'];
    $acc_type = trim($_POST['acc_type']);
    $acc_rates = $_POST['acc_rates'];
    $acc_status = $_POST['acc_status'];
    $acc_amount = $_POST['acc_amount'];
    $client_id = $_SESSION['client_id'];

    // Validate account holder name (only letters allowed)
    if (!preg_match("/^[a-zA-Z ]+$/", $acc_name)) {
        $err = "Account Holder Name should only contain letters.";
    }

    // Validate account type selection
    if ($acc_type == "Select Any iBank Account types" || empty($acc_type)) {
        $err = "Please select a valid account type.";
    }

    if (!isset($err)) {
        // Check existing accounts
        $check_query = "
    SELECT t.name FROM iB_bankAccounts b
    JOIN iB_Acc_types t ON b.acc_type_id = t.acctype_id
    WHERE b.client_id = ?
";

        $stmt_check = $mysqli->prepare($check_query);
        $stmt_check->bind_param('i', $client_id);
        $stmt_check->execute();
        $stmt_check->store_result();
        $stmt_check->bind_result($existing_acc_type);

        $existing_accounts = [];
        $joint_acc_count = 0;

        while ($stmt_check->fetch()) {
            $existing_accounts[] = trim($existing_acc_type);
            if (trim($existing_acc_type) === "Joint Account") {
                $joint_acc_count++;
            }
        }
        $stmt_check->close();

        $total_accounts = count($existing_accounts);
        $allow_account = false;

        if ($total_accounts == 0) {
            $allow_account = true;
        } elseif ($total_accounts == 1 && $existing_accounts[0] !== "Joint Account") {
            $err = "You can only open one non-joint account. Additional accounts must be Joint Accounts.";
        } elseif ($total_accounts >= 1 && in_array("Joint Account", $existing_accounts)) {
            if (strcasecmp($acc_type, "Joint Account") !== 0) {
                $err = "You are only allowed to open Joint Accounts now.";
            } elseif ($joint_acc_count >= 3) {
                $err = "You can only have a maximum of 3 Joint Accounts.";
            } else {
                $allow_account = true;
            }
        }

        if ($allow_account) {
            $query = "INSERT INTO iB_bankAccounts 
                      (acc_name, account_number, acc_type, acc_rates, acc_status, acc_amount, client_id) 
                      VALUES (?, ?, ?, ?, ?, ?, ?)";
            $stmt = $mysqli->prepare($query);
            $stmt->bind_param('ssssssd', $acc_name, $account_number, $acc_type, $acc_rates, $acc_status, $acc_amount, $client_id);
            $stmt->execute();

            if ($stmt) {
                $success = "Account Opened Successfully";
            } else {
                $err = "Please Try Again Later";
            }
        }
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
        $client_id = $_SESSION['client_id'];
        $ret = "SELECT * FROM  iB_clients WHERE client_id = ? ";
        $stmt = $mysqli->prepare($ret);
        $stmt->bind_param('i', $client_id);
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
                                <h1>Open <?php echo $row->name; ?> iBanking Account</h1>
                            </div>
                            <div class="col-sm-6">
                                <ol class="breadcrumb float-sm-right">
                                    <li class="breadcrumb-item"><a href="pages_dashboard.php">Dashboard</a></li>
                                    <li class="breadcrumb-item"><a href="pages_open_acc.php">iBanking Accounts</a></li>
                                    <li class="breadcrumb-item"><a href="pages_open_acc.php">Open </a></li>
                                    <li class="breadcrumb-item active"><?php echo $row->name; ?></li>
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
                                                <div class=" col-md-6 form-group">
                                                    <label for="exampleInputEmail1">Client Name</label>
                                                    <input type="text" readonly name="client_name"
                                                        value="<?php echo $row->name; ?>" required class="form-control"
                                                        id="exampleInputEmail1">
                                                </div>
                                                <div class=" col-md-6 form-group">
                                                    <label for="exampleInputPassword1">Client Number</label>
                                                    <input type="text" readonly name="client_number"
                                                        value="<?php echo $row->client_number; ?>" class="form-control"
                                                        id="exampleInputPassword1">
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class=" col-md-12 form-group">
                                                    <label for="exampleInputEmail1">Client Phone Number</label>
                                                    <input type="text" readonly name="client_phone"
                                                        value="<?php echo $row->phone; ?>" required class="form-control"
                                                        id="exampleInputEmail1">
                                                </div>

                                            </div>

                                            <div class="row">
                                                <div class=" col-md-6 form-group">
                                                    <label for="exampleInputEmail1">Client Email</label>
                                                    <input type="email" readonly name="client_email"
                                                        value="<?php echo $row->email; ?>" required class="form-control"
                                                        id="exampleInputEmail1">
                                                </div>
                                                <div class=" col-md-6 form-group">
                                                    <label for="exampleInputEmail1">Client Address</label>
                                                    <input type="text" name="client_adr" readonly
                                                        value="<?php echo $row->address; ?>" required class="form-control"
                                                        id="exampleInputEmail1">
                                                </div>
                                            </div>
                                            <!-- ./End Personal Details -->

                                            <!--Bank Account Details-->
                                            <div class="row">
                                                <div class="col-md-6 form-group">
                                                    <label for="exampleInputEmail1">iBank Account Type</label>
                                                    <select class="form-control" name="acc_type" id="acc_type" required>
                                                        <option value="">Select Any iBank Account Type</option>
                                                        <?php
                                                        $ret = "SELECT * FROM iB_Acc_types WHERE is_active = 1 ORDER BY RAND()";
                                                        $stmt = $mysqli->prepare($ret);
                                                        $stmt->execute();
                                                        $res = $stmt->get_result();
                                                        while ($row = $res->fetch_object()) {
                                                            echo "<option value='$row->name' data-rate='$row->rate'>$row->name</option>";
                                                        }
                                                        ?>
                                                    </select>
                                                </div>
                                                <div class=" col-md-6 form-group">
                                                    <label for="exampleInputEmail1">Account Type Rates (%)</label>
                                                    <input type="text" name="acc_rates" readonly required
                                                        class="form-control" id="AccountRates">
                                                </div>

                                                <div class=" col-md-6 form-group" style="display:none">
                                                    <label for="exampleInputEmail1">Account Status</label>
                                                    <input type="text" name="acc_status" value="Active" readonly required
                                                        class="form-control">
                                                </div>

                                                <div class=" col-md-6 form-group" style="display:none">
                                                    <label for="exampleInputEmail1">Account Amount</label>
                                                    <input type="text" name="acc_amount" value="0" readonly required
                                                        class="form-control">
                                                </div>

                                            </div>
                                            <div class="row">

                                                <div class="col-md-6 form-group">
                                                    <label for="exampleInputEmail1">Account Holder Name</label>
                                                    <input type="text" name="acc_name" required pattern="[A-Za-z ]+"
                                                        title="Only letters and spaces are allowed" class="form-control">
                                                </div>

                                                <div class=" col-md-6 form-group">
                                                    <label for="exampleInputEmail1">Account Number</label>
                                                    <?php
                                                    //PHP function to generate random account number
                                                    $length = 12;
                                                    $_accnumber = substr(str_shuffle('0123456789'), 1, $length);
                                                    ?>
                                                    <input type="text" readonly name="account_number"
                                                        value="<?php echo $_accnumber; ?>" required class="form-control"
                                                        id="exampleInputEmail1">
                                                </div>
                                            </div>
                                        </div>
                                        <!-- /.card-body -->
                                        <div class="card-footer">
                                            <button type="submit" name="open_account" class="btn btn-success">Open iBanking
                                                Account</button>
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
    <!-- AdminLTE App -->
    <script src="dist/js/adminlte.min.js"></script>
    <!-- AdminLTE for demo purposes -->
    <script src="dist/js/demo.js"></script>
    <script type="text/javascript">
        $(document).ready(function () {
            bsCustomFileInput.init();
        });
    </script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function () {
        $("#acc_type").change(function () {
            var selectedOption = $(this).find(":selected");
            var rate = selectedOption.data("rate"); // Get rate from the selected option
            $("#AccountRates").val(rate); // Set the value
        });
    });
</script>

</body>

</html>