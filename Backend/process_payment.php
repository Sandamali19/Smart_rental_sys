<?php
include('config.php');
session_start();

if (isset($_POST['pay_now'])) {
    $phone = mysqli_real_escape_string($conn, $_POST['phone']);
    $address = mysqli_real_escape_string($conn, $_POST['address']);

    if (!isset($_SESSION['payment_data'])) {
        echo "<script>alert('No booking data found!'); window.location='../all_items.php';</script>";
        exit();
    }
    $user_id = $_SESSION['user_id']; // here check if user is logged in
    $data = $_SESSION['payment_data'];

    $item_id = $data['item_id'];
    $start_date = $data['start_date'];
    $end_date = $data['end_date'];
    $total_price = $data['total_payment'];

    // update user's phone and address
    $update_user = "UPDATE users 
                    SET phone='$phone', address='$address' 
                    WHERE user_id='$user_id'";
    mysqli_query($conn, $update_user);


    // add booking info to the booking table
    $insert_booking = "INSERT INTO bookings (item_id, user_id, start_date, end_date, total_price, status)
                       VALUES ('$item_id', '$user_id', '$start_date', '$end_date', '$total_price', 'confirmed')";
    mysqli_query($conn, $insert_booking);
    $booking_id = mysqli_insert_id($conn);

    //record transaction id
    $transaction_id = 'TXN-' . strtoupper(uniqid());

    // add payment info to the payments table
    $insert_payment = "INSERT INTO payments (booking_id, amount, payment_method, payment_status, transaction_id,payment_type)
                       VALUES ('$booking_id', '$total_price', 'Card', 'completed', '$transaction_id','normal')";
    mysqli_query($conn, $insert_payment);

    // update item availability
    $period = new DatePeriod(
        new DateTime($start_date),
        new DateInterval('P1D'),
        (new DateTime($end_date))->modify('+1 day') // include end date
    );

    foreach ($period as $date) {
        $d = $date->format("Y-m-d");

        //look for existing record
        $check = "SELECT * FROM item_availability WHERE item_id='$item_id' AND available_date='$d'";
        $res = mysqli_query($conn, $check);

        if (mysqli_num_rows($res) > 0) {
            mysqli_query($conn, "UPDATE item_availability SET is_available=0 WHERE item_id='$item_id' AND available_date='$d'");
        } else {
            mysqli_query($conn, "INSERT INTO item_availability (item_id, available_date, is_available) VALUES ('$item_id', '$d', 0)");
        }
    }
    unset($_SESSION['payment_data']);

    echo "<script>alert('Payment Successful! Transaction ID: $transaction_id'); location='../index.php';</script>";

} else {
    header("Location: all_items.php");
    exit();
}
?>
