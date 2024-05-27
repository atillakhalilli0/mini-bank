<?php
include 'header.php';
// error_reporting(0);

if (!isset($_SESSION['user_id'])) {
    echo '<div class="alert alert-danger" role="alert">You must be logged in to make a transfer.</div>';
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['amount'])) {
    $sender_id = $_SESSION['user_id'];
    $recipient_card_number = trim(strip_tags(htmlspecialchars(mysqli_real_escape_string($con, $_POST['recipient_card_number']))));
    $amount = trim(strip_tags(htmlspecialchars(mysqli_real_escape_string($con, $_POST['amount']))));

    if (empty($recipient_card_number) || empty($amount) || !is_numeric($amount) || $amount <= 0) {
        echo '<div class="alert alert-warning" role="alert">Please provide a valid recipient card number and a positive numeric amount.</div>';
    } else {
        $recipient_query = mysqli_query($con, "SELECT * FROM card WHERE card_number='$recipient_card_number'");
        
        if (mysqli_num_rows($recipient_query) == 1) {
            $recipient_info = mysqli_fetch_assoc($recipient_query);
            $recipient_id = $recipient_info['user_id'];

            // Fetch recipient's user details
            $recipient_user_query = mysqli_query($con, "SELECT * FROM users WHERE id='$recipient_id'");
            $recipient_user_info = mysqli_fetch_assoc($recipient_user_query);

            if ($recipient_id == $sender_id) {
                echo '<div class="alert alert-warning" role="alert">You cannot transfer money to yourself.</div>';
            } else {
                $sender_balance_query = mysqli_query($con, "SELECT balance FROM card WHERE user_id='$sender_id'");
                $sender_balance_info = mysqli_fetch_assoc($sender_balance_query);
                $sender_balance = $sender_balance_info['balance'];

                if ($sender_balance >= $amount) {
                    $con->autocommit(FALSE);

                    $update_sender_balance = mysqli_query($con, "UPDATE card SET balance=balance-$amount WHERE user_id='$sender_id'");
                    $update_recipient_balance = mysqli_query($con, "UPDATE card SET balance=balance+$amount WHERE user_id='$recipient_id'");

                    if ($update_sender_balance && $update_recipient_balance) {
                        $con->commit();
                        
                        // Display receipt and redirect after 10 seconds
                        $sender_name = $_SESSION['name'] . ' ' . $_SESSION['surname'];
                        $recipient_name = $recipient_user_info['name'] . ' ' . $recipient_user_info['surname'];
                        echo '<div class="alert alert-success" role="alert">
                                Transfer successful!<br>
                                Amount Transferred: ' . $amount . '<br>
                                Sender: ' . $sender_name . '<br>
                                Recipient: ' . $recipient_name . '
                              </div>';
                        echo '<script>
                                setTimeout(function() {
                                    window.location.href = "transfer.php";
                                }, 10000); // 10 seconds
                              </script>';
                    } else {
                        $con->rollback();
                        echo '<div class="alert alert-danger" role="alert">Transfer failed. Please try again.</div>';
                    }

                    $con->autocommit(TRUE);
                } else {
                    echo '<div class="alert alert-warning" role="alert">Insufficient balance for the transfer.</div>';
                }
            }
        } else {
            echo '<div class="alert alert-warning" role="alert">Recipient not found. Please check the card number and try again.</div>';
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Transfer Money</title>
    <link rel="stylesheet" href="path_to_bootstrap_css">
</head>
<body>
<div class="container">
    <h2>Transfer Money</h2>
    <form method="POST" action="">
        <?php
        $check = mysqli_query($con, "SELECT * FROM card WHERE user_id='" . $_SESSION['user_id'] . "'");
        $infoo = mysqli_fetch_array($check);

        echo '
        <div class="alert alert-primary" role="alert">
            Current balance:<br>
            <input type="text" class="form-control" name="current_balance" value="' . $infoo['balance'] . '" readonly>
        </div>';
        ?>
        <div class="form-group">
            <label for="recipient_card_number">Recipient Card Number:</label>
            <input type="text" class="form-control" id="recipient_card_number" name="recipient_card_number" required>
        </div>
        <div class="form-group">
            <label for="amount">Amount:</label>
            <input type="text" class="form-control" id="amount" name="amount" required>
        </div>
        <button type="submit" class="btn btn-primary">Transfer</button>
    </form>
</div>
</body>
</html>
