<?php
// Enable error reporting (for debugging)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
include('conf/config.php');
include('conf/checklogin.php');
check_login();

// Ensure client session exists
if (!isset($_SESSION['client_id'])) {
    die("Client ID is not set in session.");
}

$client_id = $_SESSION['client_id'];

// Fetch complaints for the logged-in client
$query = "SELECT * FROM client_feedback WHERE client_id = ?";
$stmt = $mysqli->prepare($query);
$stmt->bind_param("i", $client_id);
$stmt->execute();
$result = $stmt->get_result();

// Function to truncate text
function truncateText($text, $length = 50) {
    return (strlen($text) > $length) ? substr($text, 0, $length) . '...' : $text;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My Complaints</title>
    <?php include("dist/_partials/head.php"); ?>
</head>
<body class="hold-transition sidebar-mini layout-fixed layout-navbar-fixed">
    <div class="wrapper">
        <?php include("dist/_partials/nav.php"); ?>
        <?php include("dist/_partials/sidebar.php"); ?>

        <div class="content-wrapper">
            <section class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1>My Complaints</h1>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="client_dashboard.php">Dashboard</a></li>
                                <li class="breadcrumb-item active">My Complaints</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </section>

            <section class="content">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">Your Complaints & Replies</h3>
                            </div>
                            <div class="card-body">
                                <?php if (isset($_SESSION['success'])): ?>
                                    <div class="alert alert-success"><?php echo $_SESSION['success']; unset($_SESSION['success']); ?></div>
                                <?php endif; ?>
                                <?php if (isset($_SESSION['error'])): ?>
                                    <div class="alert alert-danger"><?php echo $_SESSION['error']; unset($_SESSION['error']); ?></div>
                                <?php endif; ?>

                                <table id="complaintsTable" class="table table-bordered table-hover">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Subject</th>
                                            <th>Complaint</th>
                                            <th>Reply</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $cnt = 1;
                                        while ($row = $result->fetch_object()) {
                                            echo "<tr>
                                                <td>{$cnt}</td>
                                                <td>" . htmlspecialchars($row->subject) . "</td>
                                                <td>" . htmlspecialchars(truncateText($row->feedback_message, 100)) . "</td>
                                                <td>" . ($row->reply ? htmlspecialchars(truncateText($row->reply, 100)) : '<span class="text-muted">No reply yet</span>') . "</td>
                                                <td>
                                                    <button type='button' class='btn btn-info btn-sm' data-toggle='modal' data-target='#viewComplaintModal{$row->id}'>
                                                        <i class='fas fa-eye'></i> View
                                                    </button>
                                                </td>
                                            </tr>";

                                            // Modal for viewing full complaint and reply
                                            echo "<div class='modal fade' id='viewComplaintModal{$row->id}'>
                                                    <div class='modal-dialog'>
                                                        <div class='modal-content'>
                                                            <div class='modal-header'>
                                                                <h4 class='modal-title'>Complaint Details</h4>
                                                                <button type='button' class='close' data-dismiss='modal'>&times;</button>
                                                            </div>
                                                            <div class='modal-body'>
                                                                <p><strong>Subject:</strong> " . htmlspecialchars($row->subject) . "</p>
                                                                <p><strong>Complaint:</strong><br>" . nl2br(htmlspecialchars($row->feedback_message)) . "</p>
                                                                <p><strong>Reply:</strong><br>" . ($row->reply ? nl2br(htmlspecialchars($row->reply)) : '<span class="text-muted">No reply yet</span>') . "</p>
                                                            </div>
                                                            <div class='modal-footer'>
                                                                <button type='button' class='btn btn-secondary' data-dismiss='modal'>Close</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>";
                                            $cnt++;
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

    <script src="plugins/jquery/jquery.min.js"></script>
    <script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="plugins/datatables/jquery.dataTables.js"></script>
    <script src="plugins/datatables-bs4/js/dataTables.bootstrap4.js"></script>
    <script src="dist/js/adminlte.min.js"></script>
    <script>
        $(function() {
            $("#complaintsTable").DataTable();
        });
    </script>
</body>
</html>
