<?php session_start(); ?>
<?php
if (isset($_SESSION['use'])) {
	session_destroy();
	header("Location:index.php");
}

if (isset($_POST['user'])) {
	$user = $_POST['user'];
	$pass = $_POST['password'];
	$check = '$2y$12$n5bAVjKYvmDg2LRCzJXlFO02atQz9XpWs3MGISLBt1dmDAle4FU16';
	if ($user == "admin") {
		if (password_verify($pass, $check)) {
			$_SESSION['use'] = $user;
			echo '<script type="text/javascript"> window.open("admin.php","_self");</script>';
		}
	} else {
	}
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Andrew Burt's Portfolio</title>
	<link href="css/style.css" rel="stylesheet">
	<link href="css/lightbox.min.css" rel="stylesheet">
	<script src="js/lightbox-plus-jquery.min.js"></script>
	<link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
	<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.bundle.min.js" integrity="sha384-1CmrxMRARb6aLqgBO7yyAxTOQE2AKb9GfXnEo760AUcUmFx3ibVJJAzGytlQcNXd" crossorigin="anonymous">
	</script>
	<script src="js/index-script.js"></script>
	<link rel="apple-touch-icon" sizes="180x180" href="assets/apple-touch-icon.png">
	<link rel="icon" type="image/png" sizes="32x32" href="assets/favicon-32x32.png">
	<link rel="icon" type="image/png" sizes="16x16" href="assets/favicon-16x16.png">
	<link rel="manifest" href="assets/site.webmanifest">
	<link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500&display=swap" </head> <body>
	<nav class="navbar sticky-top bg-light">
		<a class="navbar-brand" href="https://andrewburt.dev/">Return to Resume</a>
	</nav>
	<div class="jumbotron jumbotron-fluid" style="background-color:#f8f9fa">
		<div class="container">
			<h1 class="display-4">Andrew's Portfolio</h1>
			<p class="lead">Here you can view my work!</p>
		</div>
	</div>
	<div class="container-fluid">
		<div class="row">
			<div class="col-lg-12 col-sm-12 col-12">
				<div class="popup-btn" onclick="document.getElementById('login').style.display='block'">
					<a href="#login">Upload</a>
				</div>
				<div id="login" class="modal">
					<div class="offset-lg-4 col-lg-4 offset-sm-1 col-sm-10 offset-1 col-10">
						<div class="modal-body">
							<form class="modal-content animate" name="login" method="post" action="">
								<span onclick="document.getElementById('login').style.display='none'" class="close" title="Close Login">&times;</span>
								<br>
								<p align="center"><input type="text" id="login" placeholder="Username" name="user"></p>
								<p align="center"><input type="password" id="password" placeholder="Password" name="password"></p>
								<p align="center"><input type="submit" value="Log In"></p>
								<h6 align="center">Username: admin Password: 1234 </h6>

							</form>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="gallery">
		<?php
		$folder_path = 'images/';
		$num_files = glob($folder_path . "*.{JPG,jpg,gif,png}", GLOB_BRACE);
		$folder = opendir($folder_path);
		if ($num_files > 0) {
			while (false !== ($file = readdir($folder))) {
				$file_path = $folder_path . $file;
				$extension = strtolower(pathinfo($file, PATHINFO_EXTENSION));
				if ($extension == 'jpg' || $extension == 'png' || $extension == 'gif') {
					if (strpos($file_path, '-hidden') !== false) {
						continue;
					}
		?>
					<a href="<?php echo $file_path; ?>" data-lightbox="gallery"><img loading="lazy" class="center-cropped" src="<?php echo $file_path; ?>" height="250"></a>
		<?php
				}
			}
		} else {
			echo "The folder was empty!";
		}
		closedir($folder);
		?>
	</div>
	</body>

</html>