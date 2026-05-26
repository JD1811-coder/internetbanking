# DigiBankX — Internet Banking Management System

> A secure, full-featured internet banking platform with role-based dashboards for Admins, Staff, and Clients. Built as a final-year BCA project at C.K. Pithawalla College of Commerce, Management & Computer Application, Surat.

---

## Table of Contents

- [Overview](#overview)
- [Features](#features)
- [Tech Stack](#tech-stack)
- [System Architecture](#system-architecture)
- [Database Schema](#database-schema)
- [Screenshots](#screenshots)
- [Installation](#installation)
- [Usage](#usage)
- [Project Structure](#project-structure)
- [Limitations & Future Enhancements](#limitations--future-enhancements)
- [Team](#team)

---

## Overview

DigiBankX is a web-based Internet Banking Management System designed to replace traditional branch-based banking with a secure, 24/7 accessible online platform. It supports three distinct user roles — **Admin**, **Staff**, and **Client** — each with a tailored dashboard and set of permissions.

The system handles everything from account opening and fund transfers to loan applications, complaint management, and financial reporting.

---

## Features

### Admin Panel
- Full user management — create, activate/deactivate, and delete Staff and Clients
- Account type management (Savings, Current, Joint, Recurring Deposit, etc.)
- Loan type creation and application review (approve / reject)
- Full transaction history with rollback capability
- Financial reports — deposits, withdrawals, and transfers (exportable to CSV / Excel / PDF)
- Balance enquiries across all client accounts
- System settings — company name, tagline, logo
- OTP-based password recovery

### Staff Panel
- Client profile management
- Account opening and account type conversion
- Deposit, withdrawal, and fund transfer processing
- Loan application review and recommendation
- Complaint viewing and response
- Financial report access (deposits, withdrawals, transfers)

### Client Panel
- Self-registration and secure login with OTP password recovery
- Open and manage internet banking accounts
- Deposit, withdraw, and transfer funds between accounts
- Apply for loans (Home, Business, Two-Wheeler, and more)
- Track loan application status and EMI schedule
- Add and manage nominees
- View full transaction history with colour-coded labels
- Submit and track complaints
- Balance enquiry with printable statement

---

## Tech Stack

| Layer | Technology |
|---|---|
| Frontend | HTML5, CSS3, JavaScript, AJAX, jQuery |
| Backend | PHP |
| Database | MySQL |
| Server | XAMPP (Apache + MySQL) |
| UI Libraries | SweetAlert, jQuery |
| Dev Tools | Visual Studio Code, Postman |
| Documentation | MS Word, MS PowerPoint |

---

## System Architecture

DigiBankX follows a three-tier architecture:

```
┌─────────────────────────────────────┐
│           Presentation Layer         │
│   HTML / CSS / JS / AJAX / jQuery   │
└────────────────┬────────────────────┘
                 │
┌────────────────▼────────────────────┐
│           Application Layer          │
│              PHP Backend             │
│   Admin │ Staff │ Client Controllers │
└────────────────┬────────────────────┘
                 │
┌────────────────▼────────────────────┐
│             Data Layer               │
│         MySQL via XAMPP              │
└─────────────────────────────────────┘
```

**User Scope:**

```
SCOPE
├── Admin      → Full system control
├── Staff      → Client & transaction management
├── Client     → Personal banking services
└── Visitor    → Public landing page + registration
```

---

## Database Schema

The system uses **12 relational tables**:

| Table | Description |
|---|---|
| `ib_admin` | Admin credentials and profile |
| `ib_staff` | Staff accounts with Aadhaar / PAN verification |
| `ib_clients` | Registered banking clients |
| `ib_acc_types` | Account categories (Savings, Current, etc.) |
| `ib_bankaccounts` | Individual client bank accounts |
| `ib_transaction` | All deposit / withdrawal / transfer records |
| `ib_loan_application` | Client loan requests with status tracking |
| `loan_types` | Loan categories with interest rates and limits |
| `loan_payments` | EMI payment records per loan |
| `ib_nominees` | Client nominee details |
| `client_feedback` | Client complaint submissions |
| `ib_systemsetting` | System-wide configuration (name, logo, tagline) |
| `interest_log` | Monthly interest deposit history |
| `password_reset` | Admin password reset tokens |

---

## Screenshots

### Visitor Landing Page
The public-facing homepage with Get Started and Open an Account CTAs, displaying DigiBankX's core services: Secure Transactions, Loan Management, 24/7 Banking, EMI Management, Easy Bill Payments, and User-Friendly Interface.

### Admin Dashboard
Displays real-time metrics: total Clients, Staff, Account Types, Deposits (Rs. 2,292,100), Withdrawals (Rs. 241,222), Transfers (Rs. 130,447), and Wallet Balance (Rs. 920,244). Includes pie charts for A/C Types and Transactions breakdown.

### Staff Dashboard
Shows client and account counts alongside deposit, withdrawal, transfer totals and wallet balance with the same advanced analytics charts.

### Client Dashboard
Personal banking overview with account balance, transaction analytics, and quick access to Finances, Accounts, Loans, and Complaints.

### Transaction History
Colour-coded transaction log: 🟢 Green = Deposit, 🔴 Red = Withdrawal, 🟡 Yellow = Transfer — with rollback support for Admins and Staff.

### Balance Enquiry
Detailed account statement showing Funds In, Funds Out, Sub Total, Banking Interest, and Total Balance — printable directly from the browser.

---

## Installation

### Prerequisites
- [XAMPP](https://www.apachefriends.org/) (PHP 8.0+, MySQL 5.5+)
- Web browser (developed and tested on Google Chrome)
- Minimum 1 GB disk space

### Steps

1. **Clone the repository**
   ```bash
   git clone https://github.com/JD1811-coder/DigiBankX.git
   cd DigiBankX
   ```

2. **Move to XAMPP web root**
   ```bash
   # Windows
   xcopy /E /I DigiBankX C:\xampp\htdocs\internetBanking

   # macOS / Linux
   cp -r DigiBankX /opt/lampp/htdocs/internetBanking
   ```

3. **Start XAMPP**
   - Open the XAMPP Control Panel
   - Start **Apache** and **MySQL**

4. **Import the database**
   - Open `http://localhost/phpmyadmin`
   - Create a new database named `internetBanking`
   - Click **Import** and select the provided `.sql` file from the repo

5. **Configure the database connection**
   - Open `config/db.php` (or equivalent config file)
   - Update credentials if needed:
     ```php
     $host = 'localhost';
     $dbname = 'internetBanking';
     $username = 'root';
     $password = '';
     ```

6. **Launch the application**
   ```
   http://localhost/internetBanking/
   ```

### Default Login Portals
| Role | URL |
|---|---|
| Admin | `/admin/` |
| Staff | `/staff/` |
| Client | `/client/` |

---

## Usage

### As Admin
1. Log in at the Admin Portal
2. Create Staff accounts via **Staff → Add Staff**
3. Set up account types via **Account → Add Account Category**
4. Create loan types via **Loans → Add Loan Type**
5. Monitor all transactions via **Transactions History**
6. Approve or reject loan applications via **Loans → Loan Applications → Review**
7. Generate financial reports via **Financial Reports** (CSV / Excel / Print)

### As Staff
1. Log in at the Staff Portal
2. Register clients and open their bank accounts
3. Process deposits, withdrawals, and fund transfers via **Finances**
4. Review and recommend loan applications
5. Respond to client complaints

### As Client
1. Register via the **Sign Up** page on the landing page
2. Log in at the Client Portal
3. Open a bank account via **iBank Accounts → Open Account**
4. Perform transactions via **Finances**
5. Apply for loans via **Finances → Apply for Loan**
6. Track loan status and EMI schedule
7. Submit complaints via the **Complaints** section

---

## Project Structure

```
internetBanking/
├── admin/
│   ├── pages/
│   │   ├── dashboard.php
│   │   ├── staff.php
│   │   ├── clients.php
│   │   ├── accounts.php
│   │   ├── loans.php
│   │   ├── transactions.php
│   │   ├── reports.php
│   │   └── system_settings.php
│   └── index.php
├── staff/
│   ├── pages/
│   │   ├── dashboard.php
│   │   ├── clients.php
│   │   ├── finances/
│   │   │   ├── deposits.php
│   │   │   ├── withdrawals.php
│   │   │   └── transfers.php
│   │   ├── loans.php
│   │   ├── complaints.php
│   │   └── reports.php
│   └── index.php
├── client/
│   ├── pages/
│   │   ├── dashboard.php
│   │   ├── accounts.php
│   │   ├── finances/
│   │   ├── loans.php
│   │   ├── nominees.php
│   │   ├── emi.php
│   │   ├── transactions.php
│   │   └── complaints.php
│   └── index.php
├── config/
│   └── db.php
├── assets/
│   ├── css/
│   ├── js/
│   └── images/
├── includes/
│   └── header.php, footer.php, ...
└── index.php  ← Visitor landing page
```

---

## Limitations & Future Enhancements

### Current Limitations
- Supports **domestic transactions only** — no international transfers
- Loan approvals require **manual staff/admin intervention**
- Complaint resolution may slow during high-volume periods
- No mobile app — web-only access

### Planned Enhancements
- **Automated loan approvals** using eligibility algorithms
- **Biometric authentication** (fingerprint / face ID) for login
- **Multilingual support** — Hindi, Gujarati, and other regional languages
- **Dedicated mobile app** (Android / iOS)
- **Enhanced financial reporting** with visual charts and trend analysis
- **Two-factor authentication (2FA)** for all user roles
- **International transaction support**

---

## Team

| Name | Role | ID |
|---|---|---|
| **Jenil Dhola** | Developer | 7298 |
| Harsh Lakhani | Developer | 7313 |
| Darshan Lakhani | Developer | 7312 |
| Utsav Chheta | Developer | 7294 |

**Guided by:** Dr. Ami Desai & Mr. Juned Ansari  
**Institution:** C.K. Pithawalla College of Commerce, Management & Computer Application, Surat  
**University:** Veer Narmad South Gujarat University  
**Academic Year:** 2024–2025  

---

## References

**Books**
- PHP Manual — php.net
- *PHP 5 Fast & Easy Web Development* — Julie C. Meloni, 2nd Ed, 2002

**Web Resources**
- [php.net](https://www.php.net)
- [w3schools.com/php](https://www.w3schools.com/php/)
- [dev.mysql.com/doc](https://dev.mysql.com/doc)
- [jquery.com](https://jquery.com)
- [css-tricks.com](https://css-tricks.com)

---

<p align="center">
  Built with PHP &amp; MySQL · Developed at C.K. Pithawalla College · 2024–2025
</p>
