<?php
try {
	$db = new PDO('mysql:host=localhost; dbname=rush_shop', 'root', '123456');
} catch (PDOException $e) {
	exit($e->getMessage());
}
session_start();
$errors = array();
$success = array();
$target_dir = "img/";
function add_product($post, $target_file, $db)
{
	$id_cat = ($post['new_category'] != "") ? add_category($post['new_category'], $db) : $post['category'];
	if ($id_cat == -1)
		$id_cat = 1;
	$add_product = $db->query('INSERT INTO `product`(`name`, `cost`, `img`, `category`) VALUES("'.$post['name'].'", "'.$post['cost'].'", "'.$target_file.'", "'.$id_cat.'")');
	$add_product->fetchAll();
}

function add_category($name, $db)
{
	$add_category = $db->query('INSERT INTO `category`(`name`) VALUES("'.$name.'")')->fetchAll();
	$res = $db->lastInsertId();
	return $res;
}

function add_user($email, $passwd, $admin, $db, &$errors, &$success)
{
	if (iconv_strlen($email) > 0 && iconv_strlen($email) < 32)
	{
		$val = $db->query('SELECT * FROM `users` WHERE `email` = \''.$email.'\'')->fetchAll();
		if (empty($val))
		{
			$add = $db->query('INSERT INTO `users`(`email`, `password`, `admin`) VALUES (\''.$email.'\', \''. password_hash($passwd, PASSWORD_DEFAULT) .'\', \''.$admin.'\')');
			$add = $add->fetchAll();
			$success[] = 'You have successfully added!';
		} else
		$errors[] = 'User is already registered!';
	}
	else {
		$errors[] = 'Your email is too short or long!';
	}
}

if ($_POST['submit'] == "add_product")
{
	if ($_FILES["fileToUpload"]["name"] != "")
	{
		$target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
		$imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
		$check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
		if($check !== false && !file_exists($target_file))
			$uploadOk = 1;
		else
			$uploadOk = 0;

		if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif" ) {
			$errors[] = "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
			$uploadOk = 0;
		}

		if ($uploadOk == 0)
			$errors[] = "Sorry, your file was not uploaded.";
		else {
			if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
				$success[] = "The file ". basename( $_FILES["fileToUpload"]["name"]). " has been uploaded.";
				add_product($_POST, $target_file, $db);
			} else {
				$errors[] = "Sorry, there was an error uploading your file.";
			}
		}
	} else {
		add_product($_POST, '/img/error.svg', $db);
		$success[] = "Procudt added!";
	}
}
else if ($_POST['submit'] && $_POST['submit'] == "add_user")
{
	if ($_POST["email"] && $_POST["passwd"] && ($_POST["privilage"] == 0 || $_POST["privilage"] == 1))
	{
		$email = $_POST["email"];
		$passwd = $_POST["passwd"];
		$privilage = $_POST["privilage"];
		add_user($email, $passwd, $privilage, $db, $errors, $success);
	}
	else
	{
		$errors[] = 'To few arguments';
	}
	
}
else if ($_POST['submit'] && $_POST['submit'] == "dell_user")
{
	if ($_POST['id_user'] == "-1")
	{
		$errors[] = "Select User";
	}
	else if ($_POST['id_user'])
	{
		$dell = $db->query('DELETE FROM `users` WHERE `id` = '.$_POST['id_user']);
		$success[] = "User Deleted";
	}
	else
	{
		$errors[] = "User not found";
	}
}
else if ($_POST['submit'] && $_POST['submit'] == "dell_product")
{
	if ($_POST['id_product'] == "-1")
	{
		$errors[] = "Select Product";
	}
	else if ($_POST['id_product'])
	{
		$dell = $db->query('DELETE FROM `product` WHERE `id` = '.$_POST['id_product']);
		$success[] = "Product Deleted";
	}
	else
	{
		$errors[] = "Product not found";
	}
}
else if ($_POST['submit'] && $_POST['submit'] == "dell_category")
{
	if ($_POST['id_category'] == "-1")
	{
		$errors[] = "Select Category";
	}
	else if ($_POST['id_category'])
	{
		$db->query('UPDATE `product` set `category` = 1 WHERE `category` = '.$_POST['id_category']);
		$db->query('UPDATE `product` set `category_2` = 1 WHERE `category` = '.$_POST['id_category']);
		$db->query('UPDATE `product` set `category_3` = 1 WHERE `category` = '.$_POST['id_category']);
		$dell = $db->query('DELETE FROM `category` WHERE `id` = '.$_POST['id_category']);
		$success[] = "Category Deleted";
	}
	else
	{
		$errors[] = "Category not found";
	}
}
if ($_SESSION['user']['admin'] != 1)
	header("Location: /");

$category = $db->query('SELECT * FROM `category`')->fetchAll();
$product = $db->query('SELECT * FROM `product`')->fetchAll();
$users = $db->query('SELECT * FROM `users`')->fetchAll();

?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Admin page</title>
	<link rel="stylesheet" href="css/styles.min.css">
	<link href="https://fonts.googleapis.com/css?family=Roboto&display=swap" rel="stylesheet">
</head>
<body>
	<?php include 'template/leftside.php'; ?>
	<div class="main">
		<?php include 'template/main-header.php'; ?>
		<div class="main-content main-admin">
			<!-- <div class="admin"> -->
				<?php if($_SESSION['user']['admin'] == 1): ?>
					<?php
					if (!empty($errors))
						echo '<div class="echo-error">'.array_shift($errors).'</div>';
					if (!empty($success))
						echo '<div class="echo-success">'.array_shift($success).'</div>';
					?>
					<div class="block admin">
						<form method="POST" enctype="multipart/form-data">
							<ul>
								<li>
									<h4>Add product and category:</h4>
								</li>
								<li>
									<input type="text" name="name" required="" placeholder="Name product">
								</li>
								<li>
									<input type="number" name="cost" required="" min="0" placeholder="Cost pruduct">
								</li>
								<li>
									<span>Category: </span><select name="category">
										<option value="-1">none</option>
										<?php foreach ($category as $key => $value) {
											echo("<option value='".$value['id']."'>".$value['name']."</option>");
										} ?>
									</select>
								</li>
								<li>
									<input type="text" name="new_category" value="" placeholder="or new category">
								</li>
								<li>
									<span>Img: </span><input type="file" name="fileToUpload" id="fileToUpload">
								</li>
								<li>
									<button type="submit" name="submit" value="add_product">Add product</button>
								</li>
							</ul>
						</form>
					</div>
					<div class="block admin">
						<form method="POST" enctype="multipart/form-data">
							<ul>
								<li>
									<h4>Delete product:</h4>
								</li>
								<li><h4>Name product:</h4></li>
								<li>
									<span></span><select name="id_product">
										<option value="-1">none</option>
										<?php foreach ($product as $key => $value) {
											echo("<option value='".$value['id']."'>".$value['name']."</option>");
										} ?>
									</select>
								</li>
								<li>___</li>
								<li>___</li>
								<li>___</li>
								<li>___</li>
								<li>___</li>
								<li>
									<button type="submit" name="submit" value="dell_product">Delete product</button>
								</li>
							</ul>
						</form>
					</div>
					<div class="block admin">
						<form method="POST" enctype="multipart/form-data">
							<ul>
								<li>
									<h4>Add user:</h4>
								</li>
								<li>
									<input name="email" type="email" required="" placeholder="email...">
								</li>
								<li>
									<input type="text" name="passwd" required="" placeholder="password...">
								</li>
								<li>
									<select name="privilage">
										<option value="0">User</option>
										<option value="1">Admin</option>
									</select>
								</li>
								<li>___</li>
								<li>___</li>
								<li>___</li>
								<li>
									<button type="submit" name="submit" value="add_user">Add user</button>
								</li>
							</ul>
						</form>
					</div>
					<div class="block admin">
						<form method="POST" enctype="multipart/form-data">
							<ul>
								<li>
									<h4>Delete user:</h4>
								</li>
								<li>
									<select name="id_user">
										<option value="-1">none</option>
										<?php foreach ($users as $key => $value) {
											echo("<option value='".$value['id']."'>".$value['email']."</option>");
										} ?>
									</select>
								</li>
								<li>___</li>
								<li>___</li>
								<li>___</li>
								<li>___</li>
								<li>___</li>
								<li>___</li>
								<li>
									<button type="submit" name="submit" value="dell_user">Delete user</button>
								</li>
							</ul>
						</form>
					</div>
					<div class="block admin">
						<form method="POST" enctype="multipart/form-data">
							<ul>
								<li>
									<h4>Delete category:</h4>
								</li>
								<li>
									<select name="id_category">
										<option value="-1">none</option>
										<?php foreach ($category as $key => $value) {
											echo("<option value='".$value['id']."'>".$value['name']."</option>");
										} ?>
									</select>
								</li>
								<li>___</li>
								<li>___</li>
								<li>___</li>
								<li>___</li>
								<li>___</li>
								<li>___</li>
								<li>
									<button type="submit" name="submit" value="dell_category">Delete category</button>
								</li>
							</ul>
						</form>
					</div>
					<div class="block admin">
						<form method="GET" action="/edit_product.php">
							<ul>
								<li>
									<h4>Edit product:</h4>
								</li>
								<li>
									<select name="id">
										<option value="-1">none</option>
										<?php foreach ($product as $key => $value) {
											echo("<option value='".$value['id']."'>".$value['name']."</option>");
										} ?>
									</select>
								</li>
								<li>___</li>
								<li>___</li>
								<li>___</li>
								<li>___</li>
								<li>___</li>
								<li>___</li>
								<li>
									<button type="submit" >Edit product</button>
								</li>
							</ul>
						</form>
					</div>
				<?php endif; ?>
			</div>
		<!-- </div> -->
	</div>
	<script src="js/scripts.min.js"></script>
</body>
</html>