<?php
defined('BASEPATH') OR exit('No direct script access allowed');
//$session = $this->session->userdata();
?><!DOCTYPE html>
<html>
<head>
  <title><?php echo $title; ?></title>
  <script src="assets/js/jquery-2.0.3.min.js"></script>
  <style>
	body {
    font-family: 'Source Sans Pro','Helvetica Neue',Helvetica,Arial,sans-serif; text-align:center;}
.loader {
margin:50px auto 20px auto;
  border: 46px solid #f3f3f3;
  border-radius: 50%;
  border-top: 46px solid blue;
  border-right: 46px solid green;
  border-bottom: 46px solid red;
  border-left: 46px solid pink;
  width: 120px;
  height: 120px;
  -webkit-animation: spin 2s linear infinite;
  animation: spin 2s linear infinite;
}

@-webkit-keyframes spin {
  0% { -webkit-transform: rotate(0deg); }
  100% { -webkit-transform: rotate(360deg); }
}

@keyframes spin {
  0% { transform: rotate(0deg); }
  100% { transform: rotate(360deg); }
}
  </style>
</head>
<body>
<div class="loader"></div>
<p>Mohon menunggu, sedang menambah data <strong><?php echo ucwords($title); ?></strong></p>
<p>Laman ini akan otomatis beralih ke laman login </p>
<script>
function refreshProgress(){
	location.replace("<?php echo site_url(); ?>");
}
$(document).ready(function(){
	$.ajax({
		url: "<?php echo site_url('core/process/'); ?>",
		type: 'post',
		data: {id:'<?php echo $table ?>'},
		success:function(data){
			console.log(data);
		}
	}).done(function() {
		refreshProgress();
	});
});
</script>
</body>
</html>
