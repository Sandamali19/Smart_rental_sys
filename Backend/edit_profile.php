<?php
session_start();
require_once 'config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../Frontend/login.html");
    exit();
}

$user_id = intval($_SESSION['user_id']);
$message = "";


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';
    $phone = $_POST['phone'] ?? '';
    $address = $_POST['address'] ?? '';

    $sql = "UPDATE users SET email=?, phone=?, address=?, updated_at=NOW() WHERE user_id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssi", $email, $phone, $address, $user_id);

    if ($stmt->execute()) {
        $message = "Profile updated successfully!";
    } else {
        $message = " Error updating profile: " . $stmt->error;
    }

    $stmt->close();
}


    $sql = "SELECT username, email, phone, address FROM users WHERE user_id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $user = $stmt->get_result()->fetch_assoc();
    $stmt->close();

?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Edit Profile</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
</head>
<body>
    <div class="container">
        <h1>Edit My Profile</h1>
        <div class="profile-preview">
            <p><strong><?php echo htmlspecialchars($user['username'] ?? ''); ?></strong></p>
        </div>
        <form action="" method="POST" enctype="multipart/form-data">
            <label>Email:</label>
            <input type="email" name="email" value="<?php echo htmlspecialchars($user['email'] ?? ''); ?>" required>
            <label>Phone:</label>
            <input type="text" name="phone" value="<?php echo htmlspecialchars($user['phone'] ?? ''); ?>">
            <label>Address:</label>
            <textarea name="address" rows="3"><?php echo htmlspecialchars($user['address'] ?? ''); ?></textarea>
            <button type="submit">Save Changes</button>
        </form>
        <a href="user_profile.php" class="back">Back to Profile</a>
    </div>
    <style>
        *{
             margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: "Poppins", sans-serif;
        }
                
        body {
            background: linear-gradient(135deg, #667eea, #764ba2);
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            color: #333;
        }

        .container {
            background: #fff;
            width: 100%;
            max-width: 450px;
            padding: 40px 35px;
            border-radius: 16px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
            animation: fadeIn 0.8s ease-in-out;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        h1 {
            text-align: center;
            color: #4f46e5;
            margin-bottom: 20px;
            font-size: 26px;
            font-weight: 600;
        }

        .profile-preview {
            text-align: center;
            margin-bottom: 25px;
        }

        .profile-preview strong {
            font-size: 18px;
            color: #222;
        }

        form label {
            display: block;
            font-size: 14px;
            font-weight: 500;
            color: #444;
            margin-bottom: 6px;
            margin-top: 12px;
        }

        form input,
        form textarea {
            width: 100%;
            padding: 10px 12px;
            border: 1px solid #ccc;
            border-radius: 8px;
            outline: none;
            transition: all 0.3s ease;
            font-size: 14px;
        }

        form input:focus,
        form textarea:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.2);
        }

        button {
            width: 100%;
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: white;
            border: none;
            padding: 12px;
            border-radius: 10px;
            font-size: 15px;
            font-weight: 600;
            margin-top: 20px;
            cursor: pointer;
            transition: background 0.3s ease, transform 0.2s;
        }

        button:hover {
            background: linear-gradient(135deg, #5a67d8, #6b46c1);
            transform: scale(1.03);
        }

        .back {
            display: block;
            text-align: center;
            margin-top: 15px;
            text-decoration: none;
            color: #667eea;
            font-weight: 500;
            transition: color 0.3s;
        }

        .back:hover {
            color: #5a67d8;
        }

        @media (max-width: 480px) {
        .container {
            padding: 30px 25px;
        }
        }
    </style>
</body>
</html>
