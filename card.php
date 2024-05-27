<?php
include'header.php';
//error_reporting(0);

$datee=date('Y-m-d H:i:s');



if(isset($_POST['generate'])){
	$numbers = [];
	for($i = 0; $i < 16; $i++){
		$numbers[] = rand(1,9);
		if($i % 4 == 3 && $i != 15){
			$numbers[] = ' ';
		}
	}

	$number = implode('', $numbers);
	echo $number;
}

if(isset($_POST['generate']))
{
	$cardnumber=trim(strip_tags(htmlspecialchars(mysqli_real_escape_string($con,$number))));

		$check=mysqli_query($con," SELECT * FROM card WHERE card_number='".$cardnumber."' AND user_id='".$_SESSION['user_id']."' ");

			if(mysqli_num_rows($check)==0)
			{
				$insert=mysqli_query($con," INSERT INTO card (card_number,datee,user_id)
				VALUES ('".$cardnumber."','".$datee."','".$_SESSION['user_id']."')");

				if($insert==true)
					{echo '<div class="alert alert-success" role="alert">Your card number! <br> "'.$cardnumber.'"</div>';}
				else
					{echo '<div class="alert alert-danger" role="alert">Error while signing up!</div>';}
			}
			else
				{echo '<div class="alert alert-warning" role="alert">This user has signed up!</div>';}
		}

if(!isset($_POST['edit'])){
    $check_card = mysqli_query($con, "SELECT * FROM card WHERE user_id='" . $_SESSION['user_id'] . "'");
    	if(mysqli_num_rows($check_card) > 0){
    		$info=mysqli_fetch_array($check_card);
    		include 'usercard.php';}
        	// echo "Your card:<br> '".$info['card_number']."' ";} 
    	else{
        	echo'<form method="post">
            	<button type="submit" name="generate" class="btn btn-primary btn-sm">Your card number!</button>
            	</form>';}
}

?>
