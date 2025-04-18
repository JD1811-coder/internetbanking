
<?php
function isActive($pages = []) {
    $currentPage = basename($_SERVER['PHP_SELF']);
    return in_array($currentPage, $pages);
}
?>
<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <?php
    $currentPage = basename($_SERVER['PHP_SELF']);
    $admin_id = $_SESSION['admin_id'];
    $query = "SELECT * FROM iB_admin WHERE admin_id = ?";
    $stmt = $mysqli->prepare($query);
    $stmt->bind_param('i', $admin_id);
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
    ?>

            <!-- Brand Logo -->
            <a href="pages_dashboard.php" class="brand-link text-light">
                <img src="dist/img/<?php echo $sys->sys_logo; ?>" alt="Logo" class="brand-image img-circle elevation-3">
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

                        <!-- Main Bank Account -->
                        <li class="nav-item">
                            <a href="pages_bank_main_account.php" class="nav-link <?php echo $currentPage == 'pages_bank_main_account.php' ? 'active' : ''; ?>">
                                <i class="nav-icon fas fa-user-tie"></i>
                                <p>Main Bank Account</p>
                            </a>
                        </li>

                        <!-- Account -->
                        <li class="nav-item">
                            <a href="pages_account.php" class="nav-link <?php echo $currentPage == 'pages_account.php' ? 'active' : ''; ?>">
                                <i class="nav-icon fas fa-user-secret"></i>
                                <p>Account</p>
                            </a>
                        </li>

                        <!-- Staff -->
                        <li class="nav-item has-treeview <?php echo isActive(['pages_add_staff.php', 'pages_manage_staff.php']) ? 'menu-open' : ''; ?>">
    <a href="#" class="nav-link <?php echo isActive(['pages_add_staff.php', 'pages_manage_staff.php']) ? 'active' : ''; ?>">
        <i class="nav-icon fas fa-user-tie"></i>
        <p>Staff <i class="fas fa-angle-left right"></i></p>
    </a>
    <ul class="nav nav-treeview">
        <li class="nav-item">
            <a href="pages_add_staff.php" class="nav-link <?php echo $currentPage == 'pages_add_staff.php' ? 'active' : ''; ?>">
                <i class="fas fa-user-plus nav-icon"></i> Add Staff
            </a>
        </li>
        <li class="nav-item">
            <a href="pages_manage_staff.php" class="nav-link <?php echo $currentPage == 'pages_manage_staff.php' ? 'active' : ''; ?>">
                <i class="fas fa-user-cog nav-icon"></i> Manage Staff
            </a>
        </li>
    </ul>
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

                        <!-- Account Types -->
                        <li class="nav-item has-treeview <?php if (in_array($currentPage, ['pages_add_acc_type.php', 'pages_manage_accs.php', 'pages_manage_acc_openings.php'])) echo 'menu-open'; ?>">
                            <a href="#" class="nav-link">
                                <i class="nav-icon fas fa-briefcase"></i>
                                <p>Account Types <i class="fas fa-angle-left right"></i></p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item"><a href="pages_add_acc_type.php" class="nav-link <?php echo $currentPage == 'pages_add_acc_type.php' ? 'active' : ''; ?>"><i class="fas fa-plus nav-icon"></i> Add Acc Type</a></li>
                                <li class="nav-item"><a href="pages_manage_accs.php" class="nav-link <?php echo $currentPage == 'pages_manage_accs.php' ? 'active' : ''; ?>"><i class="fas fa-cogs nav-icon"></i> Manage Acc Types</a></li>
                                <li class="nav-item"><a href="pages_manage_acc_openings.php" class="nav-link <?php echo $currentPage == 'pages_manage_acc_openings.php' ? 'active' : ''; ?>"><i class="fas fa-cog nav-icon"></i> Manage Acc Openings</a></li>
                            </ul>
                        </li>

                        <!-- Loans -->
                        <li class="nav-item has-treeview <?php if (in_array($currentPage, ['pages_review_loan_list.php', 'pages_add_loan_types.php', 'pages_manage_loan_types.php'])) echo 'menu-open'; ?>">
                            <a href="#" class="nav-link">
                                <i class="nav-icon fas fa-hand-holding-usd"></i>
                                <p>Loans <i class="fas fa-angle-left right"></i></p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item"><a href="pages_review_loan_list.php" class="nav-link <?php echo $currentPage == 'pages_review_loan_list.php' ? 'active' : ''; ?>"><i class="fas fa-clipboard-list nav-icon"></i> Review Application</a></li>
                                <li class="nav-item"><a href="pages_add_loan_types.php" class="nav-link <?php echo $currentPage == 'pages_add_loan_types.php' ? 'active' : ''; ?>"><i class="fas fa-plus nav-icon"></i> Add Loan Types</a></li>
                                <li class="nav-item"><a href="pages_manage_loan_types.php" class="nav-link <?php echo $currentPage == 'pages_manage_loan_types.php' ? 'active' : ''; ?>"><i class="fas fa-cogs nav-icon"></i> Manage Loan Types</a></li>
                            </ul>
                        </li>

                        <!-- Deposit Interest -->
                        <li class="nav-item">
                            <a href="deposit_interest.php" class="nav-link <?php echo $currentPage == 'deposit_interest.php' ? 'active' : ''; ?>">
                                <i class="nav-icon fas fa-percent"></i>
                                <p class="ml-2">Deposit Interest</p>
                            </a>
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
pages_add_acc_type
</style>
<?php }
    }?>
    