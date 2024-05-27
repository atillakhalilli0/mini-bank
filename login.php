<?php
session_start();
if(isset($_SESSION['email']) && isset($_SESSION['password']))
{echo '<meta http-equiv="refresh" content="0; url=card.php">'; exit;}
$datee=date('Y-m-d H:i:s');

?>

<meta charset="UTF-8">

<meta name="viewport" content="width=device-width, initial-scale=1.0">

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/css/bootstrap.min.css" integrity="sha384-zCbKRCUGaJDkqS1kPbPd7TveP5iyJE0EjAuZQTgFLD2ylzuqKfdKlfG/eSrtxUkn" crossorigin="anonymous">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.3.0/font/bootstrap-icons.css">

<script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/js/bootstrap.min.js" integrity="sha384-VHvPCCyXqtD5DqJeNxl2dtTyhF78xXNXdkwX1CZeRusQfRKp+tA7hAShOK/B/fQ2" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-fQybjgWLrvvRgtW6bFlB7jaZrFsaBXjsOMm/tB9LTS58ONXgqbR9W8oWht/amnpF" crossorigin="anonymous"></script>



<nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
	<a class="navbar-brand" href="#">Nigga bank</a>
	<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
		<span class="navbar-toggler-icon"></span>
	</button>

	<div class="collapse navbar-collapse" id="navbarSupportedContent">
		
		<ul class="navbar-nav mr-auto">
		 
			<li class="nav-item active">
				<a class="nav-link" href="#">About</a>
			</li>

			<li class="nav-item active">
				<a class="nav-link" href="#">Contact</a>
			</li>

		 
		</ul>


		
	</div>
</nav>


<br><br><br><br>


<?php

//error_reporting(0);
$con=mysqli_connect('localhost','atilla1212','12345','bank');

if(isset($_POST['login']))
{
	$email=trim(strip_tags(htmlspecialchars(mysqli_real_escape_string($con,$_POST['email']))));
	$password=trim(strip_tags(htmlspecialchars(mysqli_real_escape_string($con,$_POST['password']))));

	$yoxla=mysqli_query($con," SELECT * FROM users WHERE email='".$email."' AND password='".$password."' ");
	if(mysqli_num_rows($yoxla)>0)
	{
		$info=mysqli_fetch_array($yoxla);

		$_SESSION['user_id']=$info['id'];
		$_SESSION['name']=$info['name'];
		$_SESSION['surname']=$info['surname'];
		$_SESSION['phone']=$info['phone'];
		$_SESSION['email']=$info['email'];
		$_SESSION['password']=$info['password'];
		echo '<meta http-equiv="refresh" content="0; url=card.php">'; exit;
	}
}

if(isset($_POST['d']))
{
	$name=trim(strip_tags(htmlspecialchars(mysqli_real_escape_string($con,$_POST['name']))));
	$surname=trim(strip_tags(htmlspecialchars(mysqli_real_escape_string($con,$_POST['surname']))));
	$phone=trim(strip_tags(htmlspecialchars(mysqli_real_escape_string($con,$_POST['phone']))));
	$email=trim(strip_tags(htmlspecialchars(mysqli_real_escape_string($con,$_POST['email']))));
	$password=trim(strip_tags(htmlspecialchars(mysqli_real_escape_string($con,$_POST['password']))));

	if(!empty($name) && !empty($surname) && !empty($phone) && !empty($email) && !empty($password))
		{
			$yoxla=mysqli_query($con," SELECT * FROM users WHERE phone='".$phone."' OR email='".$email."' ");

			if(mysqli_num_rows($yoxla)==0)
			{
				$daxilet=mysqli_query($con," INSERT INTO users (name,surname,phone,email,password,datee)
				VALUES ('".$name."','".$surname."','".$phone."','".$email."','".$password."','".$datee."')");

				if($daxilet==true)
					{echo '<div class="alert alert-success" role="alert">Successfully signed up!</div>';}
				else
					{echo '<div class="alert alert-danger" role="alert">Error while signing up!</div>';}
			}
			else
				{echo '<div class="alert alert-warning" role="alert">This user has signed up!</div>';}
		}
		else
			{echo '<div class="alert alert-warning" role="alert">Please fill all gaps!</div>';}
}

			

if(!isset($_POST['edit']))
{
	echo'
	<div class="container" style="width: 900px;text-align:center;">

	<div class="alert alert-warning" role="alert">Welcome to Nigga bank! Sign up and be our customer!</div>
			<div class="alert alert-info" style="display: flex;" role="alert">
				<form method="post" style="margin-left:60px" enctype="multipart/form-data">
					<h3>Sign Up!</h3>	
					Name:<br>
					<input type="text" name="name" class="form-control" placeholder="Name..." autocomplete="off" value="'.$_POST['name'].'">
					Surname:<br>
					<input type="text" name="surname" class="form-control" placeholder="Surname..." autocomplete="off" value="'.$_POST['surname'].'">
					Phone:<br>
					<input type="text" name="phone" class="form-control" placeholder="Phone..." autocomplete="off" value="'.$_POST['phone'].'">
					Email:<br>
					<input type="email" name="email" class="form-control" placeholder="Email..." autocomplete="off" value="'.$_POST['email'].'">
					Password:<br>
					<input type="password" name="password" class="form-control" placeholder="Password..." autocomplete="off" value="'.$_POST['password'].'"><br>
					<button type="submit" name="d"  class="btn btn-primary btn-sm ">Sign Up!</button>
				</form>

				<div class="alert alert-info" role="alert">
				<form method="post" style="margin-left:150px;">
					<h3>Login dear customer!</h3>
					Email:<br>
					<input class="form-control mr-sm-2" type="email" placeholder="Email..." name="email"><br>
					Password:
					<input class="form-control mr-sm-2" type="password" placeholder="Password..." name="password"><br>
					<button class="btn btn-success my-2 my-sm-0" type="submit" name="login">Login!</button>
				</form></div>
			</div>';
}


?>