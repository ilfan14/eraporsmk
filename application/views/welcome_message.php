<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?><!DOCTYPE html>
<html lang="en">
<head>
  <title>eRaporSMK 2017</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
  <link href="https://fonts.googleapis.com/css?family=Montserrat" rel="stylesheet">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
  <style>
  body {
      font: 20px Montserrat, sans-serif;
      line-height: 1.8;
      color: #f5f6f7;
  }
  p {font-size: 16px;}
  .margin {margin-bottom: 45px;}
  .bg-1 { 
      background-color: #1abc9c; /* Green */
      color: #ffffff;
  }
  .bg-2 { 
      background-color: #474e5d; /* Dark Blue */
      color: #ffffff;
  }
  .bg-3 { 
      background-color: #ffffff; /* White */
      color: #555555;
  }
  .bg-4 { 
      background-color: #2f2f2f; /* Black Gray */
      color: #fff;
  }
  .container-fluid {
      padding-top: 70px;
      padding-bottom: 70px;
  }
  .navbar {
      padding-top: 15px;
      padding-bottom: 15px;
      border: 0;
      border-radius: 0;
      margin-bottom: 0;
      font-size: 12px;
      letter-spacing: 5px;
  }
  .navbar-nav  li a:hover {
      color: #1abc9c !important;
  }
  </style>
</head>
<body>

<!-- Navbar -->
<nav class="navbar navbar-default">
  <div class="container">
    <div class="navbar-header">
      <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#myNavbar">
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>                        
      </button>
      <a class="navbar-brand" href="<?php echo site_url(); ?>">Home</a>
    </div>
    <div class="collapse navbar-collapse" id="myNavbar">
      <ul class="nav navbar-nav navbar-right">
        <li><a href="#download">Download</a></li>
        <li><a href="#faq">FAQ</a></li>
        <li><a href="#changelog">Changelog</a></li>
      </ul>
    </div>
  </div>
</nav>

<!-- First Container -->
<div class="container-fluid bg-1 text-center">
	<a id="download">&nbsp;</a>
  <h3>eRaporSMK 2017 (New Installer)</h3>
<?php
	$data_install = array(
		0 => array(
			'name' => 'Google Drive',
			'link' => ''
			),
		1 => array(
			'name' => 'Dropbox',
			'link' => 'https://www.dropbox.com/s/4xnng1g4pd5rq8w/eRaporSMK_2017.zip?dl=0'
			),
		2 => array(
			'name' => 'Mediafire',
			'link' => 'https://www.mediafire.com/?6xz3td4kkvydjg6'
			),
		3 => array(
			'name' => 'XAMPP 5.6.24',
			'link' => 'https://osdn.net/projects/sfnet_xampp/downloads/XAMPP%20Windows/5.6.24/xampp-win32-5.6.24-1-VC11-installer.exe/'
			),
	);
	$data_update = array(
		0 => array(
			'name' => 'Google Drive',
			'link' => ''
			),
		1 => array(
			'name' => 'Dropbox',
			'link' => 'https://www.dropbox.com/s/l9dmhf4k2hqgoiz/eRaporSMK-Updater.zip?dl=0'
			),
		2 => array(
			'name' => 'Mediafire',
			'link' => 'https://www.mediafire.com/?xtfh769kmv08vlb'
			),
	);
	foreach($data_install as $di){
?>
	<a class="btn btn-warning" href="<?php echo $di['link']; ?>" target="_blank"><span class="glyphicon glyphicon-download-alt"></span> <?php echo $di['name']; ?></a> 
<?php
}
?>
  <h3>eRaporSMK 2017 (Updater)</h3>
<?php
foreach($data_update as $du){
?>
	<a class="btn btn-warning" href="<?php echo $du['link']; ?>" target="_blank"><span class="glyphicon glyphicon-download-alt"></span> <?php echo $du['name']; ?></a> 
<?php
}
?>
</div>

<!-- Second Container -->
<div class="container-fluid bg-2" style="font-size:14px;">
	<a id="faq">&nbsp;</a>
	<h3 class="margin text-center">Frequently Asked Questions (FAQ)</h3>
	<ul style="list-style:none;">
		<?php
		$file_faq = "./FAQ.txt";
		$faq = file_get_contents($file_faq);
		echo $faq;
		?>
	</ul>
</div>

<!-- Third Container (Grid) -->
<div class="container-fluid bg-3" style="font-size:12px;"> 
	<a id="changelog">&nbsp;</a>
	<h3 class="margin text-center">Daftar Perubahan</h3>
	<?php
		$filename = "./Changelog.txt";
		$changelog = file_get_contents($filename);
		echo $changelog;
	?>
</div>

<!-- Footer -->
<footer class="container-fluid bg-4 text-center">
  <p>Bootstrap Theme Made By <a href="http://mas-adi.net">Mas-Adi.Net</a></p> 
</footer>

</body>
</html>
