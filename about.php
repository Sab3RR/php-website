<?php
session_start();
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
			<div class="about" style="padding: 0 15px">
				<h1>About</h1>
				<ul>
					<li>We work from 10:00 to 18:00, sub. - Sun output.</li>
					<li>We are located in Ukraine, Kyiv, Dorohozhytska St, 3 (campus entrance near Dorohozhytska Str, 1).</li>
				</ul>
				<h1>Interesting</h1>
				<?php
				$youtube = file_get_contents('https://www.googleapis.com/youtube/v3/search?part=snippet&channelId=UCE_M8A5yxnLfW0KghEeajjw&key=AIzaSyA-r3owMYZbOA5uKgilxzRP0XYnH-Eq5kw&maxResults=12&relevanceLanguage=en');
				$youtube = json_decode($youtube);
				foreach ($youtube as $key => $value) {
					$value = (array)$value;
					foreach ($value as $k => $b) {
						if ($b->snippet)
						{
							echo('<div class="block">
								<ul><a href="https://www.youtube.com/watch?v='.$b->id->videoId.'">
								<li><img src="'.$b->snippet->thumbnails->medium->url.'" alt=""></li>
								<li>'.$b->snippet->title.'</li>
								</ul></a>
								</div>');
						}
					}
				}
				?>
			</div>
		</div>
	</div>
	<script src="js/scripts.min.js"></script>
</body>
</html>