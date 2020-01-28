<?php
try {
	$db = new PDO('mysql:host=localhost; dbname=rush_shop', 'root', '123456');
} catch (PDOException $e) {
	exit($e->getMessage());
}
if (isset($_POST['order']))
{
	$db->query('DELETE FROM `checkout` WHERE `id` = '.$_POST['order']);
}
session_start();
$orders = $db->query('SELECT * FROM `checkout`')->fetchAll();
$product = $db->query('SELECT * FROM `product`')->fetchAll();
if ($_SESSION['user']['admin'] != 1)
	header("Location: /");

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
		<div class="main-content main-orders">
			<?php
			if (!empty($errors))
				echo '<div class="echo-error">'.array_shift($errors).'</div>';
			if (!empty($success))
				echo '<div class="echo-success">'.array_shift($success).'</div>';
			?>
			<?php
			if (count($orders) > 0) {
				foreach ($orders as $value) {
					echo('<div class="orders">');
					$ls_prod = json_decode($value['products']);
					$total = $value['total'];
					$user = $db->query('SELECT `email` FROM `users` WHERE `id` = '.$value['user_id'])->fetchAll();
					echo('<span class="email">'.$user[0]['email'].'</span>');
					$x = 0;
					echo('<span class="product">');

					foreach ($ls_prod as $ls_val) {
						$ls_val = (array)$ls_val;
						foreach ($product as $prod_val) {
							if ($prod_val['id'] == $ls_val['id'])
								$x = $prod_val;
						}
						if ($x == 0)
							break ;
						echo("<span class='left_side'>");
						echo("<li>".$x['name']."</li>");
						echo("<li>".$ls_val['count']." | ".$x['cost']."x1 ($)</li>");
						echo("</span>");
						echo("<li class='img'><img src='".$x['img']."'></li>");
					}
					echo("</span>");
					echo("<li class='last'>".$total." ($) <form method='POST'><button type='submit' value='".$value['id']."' name='order'>Execute</button></form></li>");
					echo("</div>");
				}
			}else {
				echo("<h1 style='margin: auto;text-align: center;'>Orders is empty!</h1>");
			}
			?>
		</div>
	</div>
	<script src="js/scripts.min.js"></script>
</body>
</html>