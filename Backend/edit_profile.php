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

   

    if ($profile_image_path) {
        $sql = "UPDATE users SET email=?, phone=?, address=?, profile_image=?, updated_at=NOW() WHERE user_id=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssssi", $email, $phone, $address, $profile_image_path, $user_id);
    } else {
        $sql = "UPDATE users SET email=?, phone=?, address=?, updated_at=NOW() WHERE user_id=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssi", $email, $phone, $address, $user_id);
    }

    if ($stmt->execute()) {
        $message = "Profile updated successfully!";
    } else {
        $message = " Error updating profile: " . $stmt->error;
    }

    $stmt->close();
}


$sql = "SELECT username, email, phone, address, profile_image FROM users WHERE user_id=?";
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
    <?php if ($message): ?>
        <div class="message"><?php echo $message; ?></div>
    <?php endif; ?>

    <div class="profile-preview">
        <img src="<?php echo htmlspecialchars($user['profile_image'] ?? '../Frontend/default_avatar.png'); ?>" alt="Profile Image">
        <p><strong><?php echo htmlspecialchars($user['username']); ?></strong></p>
    </div>

    <form action="" method="POST" enctype="multipart/form-data">
        <label>Email:</label>
        <input type="email" name="email" value="<?php echo htmlspecialchars($user['email'] ?? ''); ?>" required>

        <label>Phone:</label>
        <input type="text" name="phone" value="<?php echo htmlspecialchars($user['phone'] ?? ''); ?>">

        <label>Address:</label>
        <textarea name="address" rows="3"><?php echo htmlspecialchars($user['address'] ?? ''); ?></textarea>

        <label>Profile Picture:</label>
        <input type="file" name="profile_image" accept="image/*">

        <button type="submit">Save Changes</button>
    </form>

    <a href="" class="back">Back to Profile</a>
</div>
</body>
</html>
