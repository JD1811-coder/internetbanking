<?php
function isActive($pages = []) {
    $currentPage = basename($_SERVER['PHP_SELF']);
    return in_array($currentPage, $pages);
}
?>
<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <?php
    $staff_id = $_SESSION['staff_id'];
    $query = "SELECT * FROM iB_staff WHERE staff_id = ?";
    $stmt = $mysqli->prepare($query);
    $stmt->bind_param('i', $staff_id);
    $stmt->execute();
    $res = $stmt->get_result();
    while ($row = $res->fetch_object()) {
        $profile_picture = $row->profile_pic
            ? "<img src='../admin/dist/img/$row->profile_pic' class='img-fluid rounded-circle elevation-2' alt='User Image' style='width: 40px; height: 40px; object-fit: cover;'>"
            : "<img src='../admin/dist/img/user_icon.png' class='img-fluid rounded-circle elevation-2' alt='User Image' style='width: 40px; height: 40px; object-fit: cover;'>";

        $sys_query = "SELECT * FROM iB_SystemSettings";
        $stmt = $mysqli->prepare($sys_query);
        $stmt->execute();
        $sys_res = $stmt->get_result();
        while ($sys = $sys_res->fetch_object()) {
            $currentPage = basename($_SERVER['PHP_SELF']);
    ?>
    <!-- Brand Logo -->
    <a href="pages_dashboard.php" class="brand-link text-light">
        <img src="../admin/dist/img/<?php echo $sys->sys_logo; ?>" alt="Logo" class="brand-image img-circle elevation-3">
        <span class="brand-text font-weight-light"><?php echo $sys->sys_name; ?></span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
        <!-- User Panel -->
        <div class="user-panel mt-3 pb-3 mb-3 d-flex align-items-center">
            <div class="image"><?php echo $profile_picture; ?></div>
            <div class="info">
                <a href="#" class="d-block"><?php echo $row->name; ?></a>
            </div>
        </div>

        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu">

                <!-- Dashboard -->
                <li class="nav-item">
                    <a href="pages_dashboard.php" class="nav-link <?php echo $currentPage == 'pages_dashboard.php' ? 'active' : ''; ?>">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <p>Dashboard</p>
                    </a>
                </li>

                <!-- Account -->
                <li class="nav-item">
                    <a href="pages_account.php" class="nav-link <?php echo $currentPage == 'pages_account.php' ? 'active' : ''; ?>">
                        <i class="nav-icon fas fa-user-tie"></i>
                        <p>Account</p>
                    </a>
                </li>

                <!-- Clients -->
                <li class="nav-item has-treeview <?php echo isActive(['pages_manage_clients.php']) ? 'menu-open' : ''; ?>">
                    <a href="#" class="nav-link <?php echo isActive(['pages_manage_clients.php']) ? 'active' : ''; ?>">
                        <i class="nav-icon fas fa-users"></i>
                        <p>Clients <i class="fas fa-angle-left right"></i></p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="pages_manage_clients.php" class="nav-link <?php echo $currentPage == 'pages_manage_clients.php' ? 'active' : ''; ?>">
                                <i class="fas fa-user-cog nav-icon"></i> Manage Clients
                            </a>
                        </li>
                    </ul>
                </li>

                <!-- Account Openings -->
                <li class="nav-item has-treeview <?php echo isActive(['pages_manage_acc_openings.php']) ? 'menu-open' : ''; ?>">
                    <a href="#" class="nav-link <?php echo isActive(['pages_manage_acc_openings.php']) ? 'active' : ''; ?>">
                        <i class="nav-icon fas fa-briefcase"></i>
                        <p>Accounts <i class="fas fa-angle-left right"></i></p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="pages_manage_acc_openings.php" class="nav-link <?php echo $currentPage == 'pages_manage_acc_openings.php' ? 'active' : ''; ?>">
                                <i class="fas fa-cog nav-icon"></i> Manage Acc Openings
                            </a>
                        </li>
                    </ul>
                </li>

                <!-- Finances -->
                <li class="nav-item has-treeview <?php echo isActive(['pages_deposits.php', 'pages_withdrawals.php', 'pages_transfers.php', 'pages_loans.php']) ? 'menu-open' : ''; ?>">
                    <a href="#" class="nav-link <?php echo isActive(['pages_deposits.php', 'pages_withdrawals.php', 'pages_transfers.php', 'pages_loans.php']) ? 'active' : ''; ?>">
                        <i class="nav-icon fas fa-rupee-sign"></i>
                        <p>Finances <i class="fas fa-angle-left right"></i></p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="pages_deposits.php" class="nav-link <?php echo $currentPage == 'pages_deposits.php' ? 'active' : ''; ?>">
                                <i class="fas fa-upload nav-icon"></i> Deposits
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="pages_withdrawals.php" class="nav-link <?php echo $currentPage == 'pages_withdrawals.php' ? 'active' : ''; ?>">
                                <i class="fas fa-download nav-icon"></i> Withdrawals
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="pages_transfers.php" class="nav-link <?php echo $currentPage == 'pages_transfers.php' ? 'active' : ''; ?>">
                                <i class="fas fa-random nav-icon"></i> Transfers
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="pages_loans.php" class="nav-link <?php echo $currentPage == 'pages_loans.php' ? 'active' : ''; ?>">
                                <i class="fas fa-cart-arrow-down nav-icon"></i> Loan Applications
                            </a>
                        </li>
                    </ul>
                </li>

                      <!-- Advanced Modules -->
                      <li class="nav-header">Advanced Modules</li>

<li class="nav-item">
    <a href="pages_transactions_engine.php" class="nav-link <?php echo $currentPage == 'pages_transactions_engine.php' ? 'active' : ''; ?>">
        <i class="nav-icon fas fa-exchange-alt"></i>
        <p>Transactions History</p>
    </a>
</li>

<li class="nav-item has-treeview <?php if (in_array($currentPage, ['pages_financial_reporting_deposits.php', 'pages_financial_reporting_withdrawals.php', 'pages_financial_reporting_transfers.php'])) echo 'menu-open'; ?>">
    <a href="#" class="nav-link">
        <i class="nav-icon fas fa-rupee-sign"></i>
        <p>Financial Reports <i class="fas fa-angle-left right"></i></p>
    </a>
    <ul class="nav nav-treeview">
        <li class="nav-item"><a href="pages_financial_reporting_deposits.php" class="nav-link <?php echo $currentPage == 'pages_financial_reporting_deposits.php' ? 'active' : ''; ?>"><i class="fas fa-file-upload nav-icon"></i> Deposits</a></li>
        <li class="nav-item"><a href="pages_financial_reporting_withdrawals.php" class="nav-link <?php echo $currentPage == 'pages_financial_reporting_withdrawals.php' ? 'active' : ''; ?>"><i class="fas fa-cart-arrow-down nav-icon"></i> Withdrawals</a></li>
        <li class="nav-item"><a href="pages_financial_reporting_transfers.php" class="nav-link <?php echo $currentPage == 'pages_financial_reporting_transfers.php' ? 'active' : ''; ?>"><i class="fas fa-random nav-icon"></i> Transfers</a></li>
    </ul>
</li>

<!-- Settings -->
<li class="nav-item">
    <a href="pages_system_settings.php" class="nav-link <?php echo $currentPage == 'pages_system_settings.php' ? 'active' : ''; ?>">
        <i class="nav-icon fas fa-cogs"></i>
        <p>System Settings</p>
    </a>
</li>

<!-- Logout -->
<li class="nav-item">
    <a href="pages_logout.php" class="nav-link">
        <i class="nav-icon fas fa-power-off"></i>
        <p>Log Out</p>
    </a>
</li>
            </ul>
        </nav>
    </div>
    <?php } } ?>
</aside>

<style>
    .user-panel .info,
    .nav-sidebar .nav-item > a {
        text-align: left;
        padding-left: 15px;
    }

    .nav-sidebar .nav-treeview .nav-item > a {
        padding-left: 30px;
    }

    .nav-sidebar .nav-item > a {
        cursor: pointer;
    }
</style>
