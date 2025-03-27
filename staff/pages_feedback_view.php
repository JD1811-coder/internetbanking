<?php
// Enable error reporting (for debugging)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
include('conf/config.php');
include('conf/checklogin.php');
check_login();

if (!isset($_SESSION['staff_id'])) {
    die("Staff ID is not set in session.");
}

$staff_id = $_SESSION['staff_id'];

$query = "SELECT cf.*, c.name AS client_name, c.email AS client_email 
          FROM client_feedback cf
          LEFT JOIN ib_clients c ON cf.client_id = c.client_id";

$feedbackResult = $mysqli->query($query);

if (!$feedbackResult) {
    die("SQL Error: " . $mysqli->error);
}

function truncateText($text, $length = 50) {
    return (strlen($text) > $length) ? substr($text, 0, $length) . '...' : $text;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Client Complaints</title>
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
                            <h1>Client Complaints</h1>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="pages_dashboard.php">Dashboard</a></li>
                                <li class="breadcrumb-item active">Client Complaints</li>
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
                                <h3 class="card-title">Client Complaints History</h3>
                            </div>
                            <div class="card-body">
                                <?php if (isset($_SESSION['success'])): ?>
                                    <div class="alert alert-success"><?php echo $_SESSION['success']; unset($_SESSION['success']); ?></div>
                                <?php endif; ?>
                                <?php if (isset($_SESSION['error'])): ?>
                                    <div class="alert alert-danger"><?php echo $_SESSION['error']; unset($_SESSION['error']); ?></div>
                                <?php endif; ?>

                                <table id="feedbackTable" class="table table-bordered table-hover">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Client Name</th>
                                            <th>Email</th>
                                            <th>Subject</th>
                                            <th>Complaint</th>
                                            <th>Reply</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $cnt = 1;
                                        while ($row = $feedbackResult->fetch_object()) {
                                            echo "<tr>
                                                <td>{$cnt}</td>
                                                <td>" . htmlspecialchars($row->client_name ?? 'N/A') . "</td>
                                                <td>" . htmlspecialchars($row->client_email ?? 'N/A') . "</td>
                                                <td>" . htmlspecialchars($row->subject ?? '') . "</td>
                                                <td>" . htmlspecialchars(truncateText($row->feedback_message, 100)) . "</td>
                                                <td>" . ($row->reply ? htmlspecialchars($row->reply) : '<span class="text-muted">No reply yet</span>') . "</td>
                                                <td>
                                                    <button type='button' class='btn btn-info btn-sm' data-toggle='modal' data-target='#viewModal{$row->id}'>
                                                        <i class='fas fa-eye'></i> View
                                                    </button>
                                                    <button type='button' class='btn btn-primary btn-sm' data-toggle='modal' data-target='#replyModal{$row->id}'>
                                                        <i class='fas fa-reply'></i> Reply
                                                    </button>
                                                </td>
                                            </tr>";

                                            // Modal for viewing full complaint
                                            echo "<div class='modal fade' id='viewModal{$row->id}'>
                                                    <div class='modal-dialog'>
                                                        <div class='modal-content'>
                                                            <div class='modal-header'>
                                                                <h4 class='modal-title'>Complaint Details</h4>
                                                                <button type='button' class='close' data-dismiss='modal'>&times;</button>
                                                            </div>
                                                            <div class='modal-body'>
                                                                <p><strong>Client Name:</strong> " . htmlspecialchars($row->client_name) . "</p>
                                                                <p><strong>Email:</strong> " . htmlspecialchars($row->client_email) . "</p>
                                                                <p><strong>Subject:</strong> " . htmlspecialchars($row->subject) . "</p>
                                                                <p><strong>Complaint:</strong><br>" . nl2br(htmlspecialchars($row->feedback_message)) . "</p>
                                                                <p><strong>Reply:</strong><br>" . ($row->reply ? nl2br(htmlspecialchars($row->reply)) : "<span class='text-muted'>No reply yet</span>") . "</p>
                                                            </div>
                                                            <div class='modal-footer'>
                                                                <button type='button' class='btn btn-secondary' data-dismiss='modal'>Close</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>";

                                            // Modal for replying
                                            echo "<div class='modal fade' id='replyModal{$row->id}'>
                                                    <div class='modal-dialog'>
                                                        <div class='modal-content'>
                                                            <div class='modal-header'>
                                                                <h4 class='modal-title'>Reply to Complaint</h4>
                                                                <button type='button' class='close' data-dismiss='modal'>&times;</button>
                                                            </div>
                                                            <form method='POST' action='submit_reply.php'>
                                                                <div class='modal-body'>
                                                                    <input type='hidden' name='feedback_id' value='{$row->id}'>
                                                                    <div class='form-group'>
                                                                        <label>Your Reply:</label>
                                                                        <textarea class='form-control' name='reply' rows='4' required>" . htmlspecialchars($row->reply) . "</textarea>
                                                                    </div>
                                                                </div>
                                                                <div class='modal-footer'>
                                                                    <button type='submit' class='btn btn-success'>Submit</button>
                                                                    <button type='button' class='btn btn-secondary' data-dismiss='modal'>Cancel</button>
                                                                </div>
                                                            </form>
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
            $("#feedbackTable").DataTable();
        });
    </script>
</body>
</html>
