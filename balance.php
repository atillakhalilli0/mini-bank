<?php
include 'header.php';
// error_reporting(0);

$check = mysqli_query($con, "SELECT * FROM card WHERE user_id='" . $_SESSION['user_id'] . "'");

if (mysqli_num_rows($check) > 0) {
    $info = mysqli_fetch_array($check);
    if (!isset($_POST['edit'])) {
        echo '
        <form method="post">
            <div class="alert alert-primary" role="alert">
                Balance:<br>"' . $info['balance'] . '"<br>
                <button type="submit" name="step1" class="btn btn-primary btn-sm">Increase Balance</button>
            </div>
        </form>';
    }
} else {
    echo '<div class="alert alert-warning" role="alert">No balance information found for this user.</div>';
}

if (isset($_POST['step1'])) {
    $check = mysqli_query($con, "SELECT * FROM card WHERE user_id='" . $_SESSION['user_id'] . "'");
    $infoo = mysqli_fetch_array($check);

    echo '
    <div class="alert alert-primary" role="alert">
        <form method="post">
            Current balance:<br>
            <input type="text" class="form-control" name="current_balance" value="' . $infoo['balance'] . '" readonly>
            Amount:<br>
            <input type="text" class="form-control" name="new_balance">
            <input type="hidden" name="id" value="' . $infoo['id'] . '">
            <button type="submit" class="btn btn-primary btn-sm" name="step2">OK</button>
        </form>
    </div>';
}

if (isset($_POST['step2'])) {
    $new_balance = trim(strip_tags(htmlspecialchars(mysqli_real_escape_string($con, $_POST['new_balance']))));
    $current_balance = trim(strip_tags(htmlspecialchars(mysqli_real_escape_string($con, $_POST['current_balance']))));
    
    if (is_numeric($new_balance) && !empty($new_balance)) {
        $total_balance = $new_balance + $current_balance;

        $update = mysqli_query($con, "UPDATE card SET balance='" . $total_balance . "' WHERE id='" . $_POST['id'] . "'");
        if ($update == true) {
            echo '<div class="alert alert-success" role="alert">Balance successfully updated!</div>';
            header("Refresh: 0");
        } else {
            echo '<div class="alert alert-danger" role="alert">Balance update failed!</div>';
        }
    } else {
        echo '<div class="alert alert-warning" role="alert">Please enter a valid amount to increase the balance!<form method="post"><button type="submit" name="step1" class="btn btn-primary btn-sm">OK</button></button></div>';
    }
}
?>
