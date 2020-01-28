<?php
try {
	$db = new PDO('mysql:host=localhost; dbname=rush_shop', 'root', '123456');
} catch (PDOException $e) {
	exit($e->getMessage());
}
session_start();
if ($_POST['checkout'] == 'ok' && $_SESSION['cart'] && $_SESSION['user'])
{
	$cart = json_encode($_SESSION['cart']);
	$ok = $db->query('INSERT INTO `checkout`(`products`, `user_id`, `total`) VALUES(\''.$cart.'\', "'.$_SESSION['user']['id'].'", "'.$_SESSION['total'].'")');
	$ok->fetchAll();
	$id = $db->lastInsertId();
	unset($_SESSION['cart']);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Cart page</title>
	<link rel="stylesheet" href="css/styles.min.css">
	<link href="https://fonts.googleapis.com/css?family=Roboto&display=swap" rel="stylesheet">
</head>

<body>
	<?php include 'template/leftside.php'; ?>
	<div class="main">
		<?php include 'template/main-header.php'; ?>
		<div class="main-content checkout">
				<?php
				if (!empty($errors))
					echo '<div class="echo-error">'.array_shift($errors).'</div>';
				if (!empty($success))
					echo '<div class="echo-success">'.array_shift($success).'</div>';
				?>
				<?php if(isset($id)): ?>
				<p>Ваш заказ оформлен его индефикатор #<?php echo($id) ?>, ваш счёт прийдёт на почту в ближайшие время, спасибо за покупку.</p>
				<a href="/">Вернуться на главную страницу</a>
				<?php else: ?>
				<p>Ваша корзина пуста!</p>
				<a href="/">Вернуться на главную страницу</a>
				<?php endif; ?>
		</div>
	</div>
	<script src="js/scripts.min.js"></script>
</body>
</html>