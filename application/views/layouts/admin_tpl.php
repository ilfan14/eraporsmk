<?php
$settings = $this->settings->get(1);
$title = $settings->site_title;
$version = $settings->version;
if($version == '1.0'){
	$update = array('version'=> '1.0.1');
	$this->settings->update(1,$update);
}
if($this->session->userdata('template')){
	$template_aktif = $this->session->userdata('template');
} else{
	$template_aktif = 'blue';
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title><?php echo $template['title'].' | '.$title; ?> | <?php echo $version;// .'='. CI_VERSION; ?></title>
    <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
    <?php echo $template['partials']['styles'] ?>
	<!--style>
	.content-wrapper{background:#ecf0f5 url(<?php echo base_url(); ?>assets/img/login-page.png);}
	</style-->
</head>
<body class="hold-transition skin-<?php echo $template_aktif; ?> sidebar-mini">
    <!-- Top header section. Contains the profile details -->
    <div class="wrapper">
		<?php echo $template['partials']['header'] ?>
        <!-- Left side column. Contains the navbar and content of the page -->
        <?php echo $template['partials']['sidebar'] ?>
        <div class="content-wrapper">                
            <!-- Content Header (Page header) -->
            <section class="content-header" style="margin-bottom:40px;">
                <h1 style="float:left;"><?php echo $page_title; ?></h1>
				<?php if(isset($pilih_rombel)){ ?>
					<?php echo $pilih_rombel; ?>
				<?php } 
				?>
				<ol class="breadcrumb" style="display:none;">
					<li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
					<li class="active">Dashboard</li>
				</ol>
            </section>
            <!-- Main content -->
            <section class="content">
            <?php echo $template['body'] ?>
            </section><!-- /.content -->
        </div><!-- /.content-wrapper -->
		<footer class="main-footer">
    
  </footer>
  <div class="control-sidebar-bg"></div>
    </div>
    <?php echo $template['partials']['footer'] ?>
</body>
</html>
