<?php
try {
	$db = new PDO('mysql:host=localhost; dbname=rush_shop', 'root', '123456');
} catch (PDOException $e) {
	exit($e->getMessage());
}
session_start();
if ($_SESSION['user']) {
	header("Location: /");
}
if (isset($_POST['register']))
{
	$errors = array();
	$success = array();
	if (iconv_strlen($_POST['email']) > 0 && iconv_strlen($_POST['email']) < 32)
	{
		$val = $db->query('SELECT * FROM `users` WHERE `email` = \''.$_POST['email'].'\'');
		$val = $val->fetchAll();
		if (empty($val))
		{
			$add = $db->query('INSERT INTO `users`(`email`, `password`) VALUES (\''.$_POST['email'].'\', \''. password_hash($_POST['password'], PASSWORD_DEFAULT) .'\')');
			$add = $add->fetchAll();
			$_SESSION['user'] = $db->query('SELECT * FROM `users` WHERE `email` = \''.$_POST['email'].'\'')->fetchAll()[0];
			$success[] = 'You have successfully registered!';
		} else
		$errors[] = 'User is already registered!';
	}
	else {
		$errors[] = 'Your email is too short or long!';
	}
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Register page</title>
	<link rel="stylesheet" href="css/styles.min.css">
	<link href="https://fonts.googleapis.com/css?family=Roboto&display=swap" rel="stylesheet">
</head>
<body>
	<?php include 'template/leftside.php'; ?>
	<div class="main">
		<?php include 'template/main-header.php'; ?>
		<div class="main-content main-register">
			<?php
			if (!empty($errors))
				echo '<div class="echo-error">'.array_shift($errors).'</div>';
			if (!empty($success))
				echo '<div class="echo-success">'.array_shift($success).'</div>';
			?>
			<div class="register">
				<form action="/register.php" method="POST">
					<ul>
						<li><span>Enter your email:</span></li>
						<li><input type="email" name="email" placeholder="email..." required></li>
						<li><span>Enter your password:</span></li>
						<li><input type="password" name="password" placeholder="password..." required></li>
						<li><button type="submit" name="register">Register</button></li>
					</ul>
				</form>
			</div>
		</div>
	</div>
	<script src="js/scripts.min.js"></script>
</body>
</html>