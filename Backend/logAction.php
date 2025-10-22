<?php
session_start();
require_once 'config.php';

$errors = [];

if($_SERVER["REQUEST_METHOD"] == "POST"){
       
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);
    $query = "SELECT * FROM users WHERE username='$username' LIMIT 1";
    $result = mysqli_query($conn, $query);

    if ($result && mysqli_num_rows($result) == 1) {
        $user = mysqli_fetch_assoc($result);

        if ($password == $user['password'] || password_verify($password, $user['password'])) {
            
            $_SESSION['user_id'] = $user['user_id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = $user['role'];

            if ($user['username'] == 'admin') {
                header("Location: Admin/dashboard.php");
            } else {
                header("Location: ../index.php");
            }
            exit();
        } else {
            echo "<script>alert('Invalid password!'); window.location='login.html';</script>";
        }
    } else {
        echo "<script>alert('No user found with that email!'); window.location='login.html';</script>";
    }
}

?>