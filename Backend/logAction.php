<?php
session_start();
require_once 'config.php';

$errors = [];

if($_SERVER["REQUEST_METHOD"] == "POST"){
    
    $username = trim($_POST["username"] ?? '');
    $password = $_POST["password"] ?? '';

    if(empty($username)){
        $errors[] = "Please enter username.";
    } 
    
    if(empty($password)){
        $errors[] = "Please enter your password.";
    }
    
    if(empty($errors)){
        $sql = "SELECT user_id, username, `password` FROM users WHERE username = ?";
        if($stmt = mysqli_prepare($conn, $sql)){ 
            $stmt->bind_param("s", $param_username);
            $param_username = $username;
            
            if($stmt->execute()){
                $stmt->store_result();
                
                if($stmt->num_rows == 1){ 
                    $stmt->bind_result($id, $username, $hashed_password);
                    if($stmt->fetch()){
                        if(password_verify($password, $hashed_password)){
                            $_SESSION["loggedin"] = true;
                            $_SESSION["user_id"] = $id;
                            $_SESSION["username"] = $username; 
                           
                            header("location: ../index.php"); 
                            exit;
                        } else{
                            $errors[] = "Invalid username or password.";
                        }
                    }
                } else{
                    $errors[] = "Invalid username or password.";
                }
            } else{
                $errors[] = "Oops! Something went wrong. Please try again later.";
            }
            
            $stmt->close(); 
        }
    }
    
    $conn->close();

    if (!empty($errors)) {
        echo "<h2>Login Failed!</h2>";
        echo "<ul>";
        foreach ($errors as $error) {
            echo "<li>" . htmlspecialchars($error) . "</li>";
        }
        echo "</ul>";
        echo '<p><a href="../Frontend/login.html">Go back to Login Form</a></p>';
    }

} else {
    header("location: ../Frontend/login.html");
    exit;
}
?>