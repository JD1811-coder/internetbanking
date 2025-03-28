<?php
/* Persist System Settings On Brand */
$ret = "SELECT * FROM `iB_SystemSettings`";
$stmt = $mysqli->prepare($ret);
$stmt->execute();
$res = $stmt->get_result();
$sys = $res->fetch_object(); // Fetch only once
?>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title><?php echo $sys->sys_name; ?> - <?php echo $sys->sys_tagline; ?></title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- overlayScrollbars -->
    <link rel="stylesheet" href="plugins/overlayScrollbars/css/OverlayScrollbars.min.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
    <!-- Ionicons -->
    <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
    <!-- icheck bootstrap -->
    <link rel="stylesheet" href="plugins/icheck-bootstrap/icheck-bootstrap.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="dist/css/adminlte.min.css">
    <!-- Google Font: Source Sans Pro -->
    <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">
    <!-- Data tables CSS -->
    <link rel="stylesheet" href="plugins/datatables-bs4/css/dataTables.bootstrap4.css">
    <!-- Load Swal JS -->
    <script src="dist/js/swal.js"></script>
    <!-- Data Tables CSS -->
    <link rel="stylesheet" type="text/css" href="plugins/datatable/custom_dt_html5.css">
    <!-- Favicon -->
    <link rel="icon" type="image/png" sizes="16x16" href="../admin/dist/img/<?php echo $sys->sys_logo; ?>">
    <?php
    error_reporting(E_ALL);
ini_set('display_errors', 1);
?>
    <!-- Inject SWAL Alerts -->
    <?php if (isset($success)) { ?>
        <script>
            setTimeout(function() {
                swal.fire("Success", "<?php echo $success; ?>", "success");
            }, 100);
        </script>
    <?php } ?>

    <?php if (isset($err)) { ?>
        <script>
            setTimeout(function() {
                swal.fire("Failed", "<?php echo $err; ?>", "error");
            }, 100);
        </script>
    <?php } ?>

    <?php if (isset($info)) { ?>
        <script>
            setTimeout(function() {
                swal.fire("Warning", "<?php echo $info; ?>", "warning");
            }, 100);
        </script>
    <?php } ?>

    <script>
        function getiBankAccs(val) {
            $.ajax({
                type: "POST",
                url: "pages_ajax.php",
                data: { iBankAccountType: val },
                success: function(data) {
                    $('#AccountRates').val(data);
                }
            });

            $.ajax({
                type: "POST",
                url: "pages_ajax.php",
                data: { iBankAccNumber: val },
                success: function(data) {
                    $('#ReceivingAcc').val(data);
                }
            });

            $.ajax({
                type: "POST",
                url: "pages_ajax.php",
                data: { iBankAccHolder: val },
                success: function(data) {
                    $('#AccountHolder').val(data);
                }
            });
        }
    </script>
</head>

