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

    //profile image upload (optional)
    // $profile_image_path = "../Uploads";
    // if (!empty($_FILES['profile_image']['name'])) {
    //     $target_dir = "../Uploads";
    //     if (!is_dir($target_dir)) {
    //         mkdir($target_dir, 0777, true);
    //     }
    //     $file_name = time() . "_" . basename($_FILES["profile_image"]["name"]);
    //     $target_file = $target_dir . $file_name;
    //     if (move_uploaded_file($_FILES["profile_image"]["tmp_name"], $target_file)) {
    //         $profile_image_path = $target_file;
    //     }
    
   

   
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
        
        <p><strong><?php echo htmlspecialchars($user['username']); ?></strong></p>
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
</body>
</html>
