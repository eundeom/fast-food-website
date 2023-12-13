<?php
// Start the session (if not already started)
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Replace with your authentication logic (e.g., database check)
    if ($_POST['user_type'] === 'C') {
        $username = $_POST['customer_username'];
        $password = $_POST['customer_password'];
        // Check customer credentials and perform authentication here
        // Example: (for demonstration only)
        if ($username === 'customer' && $password === 'password') {
            $_SESSION['user'] = 'customer';
            header("Location: customer_dashboard.php"); // Redirect after successful login
        } else {
            echo "Invalid customer credentials!";
        }
    } elseif ($_POST['user_type'] === 'A') {
        $username = $_POST['admin_username'];
        $password = $_POST['admin_password'];
        // Check admin credentials and perform authentication here
        // Example: (for demonstration only)
        if ($username === 'admin' && $password === 'password') {
            $_SESSION['user'] = 'admin';
            header("Location: admin_dashboard.php"); // Redirect after successful login
        } else {
            echo "Invalid admin credentials!";
        }
    }
}
?>
