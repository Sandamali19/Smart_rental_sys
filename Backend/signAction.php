<?php
require_once "../Backend/config.php";
$errors = [];

if($_SERVER["REQUEST_METHOD"] == "POST"){

    $username = trim($_POST['username'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';

    
    if(empty($username)){
        $errors[] = "Please enter a username.";
    } else {
        $sql = "SELECT user_id FROM users WHERE username = ?";
        if($statement = mysqli_prepare($conn, $sql)){
            mysqli_stmt_bind_param($statement,"s",$param_username);
            $param_username = $username;
         
            if(mysqli_stmt_execute($statement)){
                mysqli_stmt_store_result($statement);
                if(mysqli_stmt_num_rows($statement) == 1){
                    $errors[] = "This username is already taken.";
                }
            } else {
                $errors[] = "Something went wrong with username check.";
            }
            mysqli_stmt_close($statement);
        }
    }
    
  
    if(empty($email)){
        $errors[] = "Please enter an email.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email format.";
    } else {
        $sql = "SELECT user_id FROM users WHERE email = ?";
        if($statement = mysqli_prepare($conn, $sql)){
            mysqli_stmt_bind_param($statement, "s", $param_email);
            $param_email = $email;
            
            if(mysqli_stmt_execute($statement)){
                mysqli_stmt_store_result($statement);
                if(mysqli_stmt_num_rows($statement) == 1){
                    $errors[] = "This email is already registered.";
                }
            } else {
                $errors[] = "Oops! Something went wrong with email check.";
            }
           
            mysqli_stmt_close($statement);
        }
    }

   
    if(empty($password)){
        $errors[] = "Please enter a password.";
    } elseif(strlen($password) < 6){
        $errors[] = "Password must have at least 6 characters.";
    }


    if(empty($confirm_password)){
        $errors[] = "Please confirm password.";
    } elseif($password != $confirm_password){
        $errors[] = "Password did not match.";
    }


    
    if(empty($errors)){
        $sql = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";
        
        if($statement = mysqli_prepare($conn, $sql)){
          
            mysqli_stmt_bind_param($statement, "sss", $param_username, $param_email, $param_password_hashed);
            $param_password_hashed = password_hash($password, PASSWORD_DEFAULT); 
            $param_username = $username;
            $param_email = $email;
            
            if(mysqli_stmt_execute($statement)){
                header("location: login.html");
                exit; 
            } else {
                $errors[] = "Something went wrong. Please try again later. (" . mysqli_error($conn) . ")";
            }
            mysqli_stmt_close($statement);
        }
    }
    
    if (!empty($errors)) {
        echo "<h2>Registration Failed!</h2>";
        echo "<ul>";
        foreach ($errors as $error) {
            echo "<li>" . htmlspecialchars($error) . "</li>";
        }
        echo "</ul>";
        echo '<p><a href="signup.html">Go back to Sign Up Form</a></p>';
    }
    $conn->close();

} else {
    header("location: signup.html");
    exit;
}
?>