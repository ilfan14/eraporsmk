
<div class="container">
	<div class="row vertical-offset-100">
		<div class=" col-xs-10 col-xs-offset-1 col-sm-6 col-sm-offset-3  col-md-5 col-md-offset-4 col-lg-4 col-lg-offset-4">
			<div class="panel panel-default">
				<div class="panel-heading">
					<img src="<?php echo base_url(); ?>assets/img/logo.png" alt="logo" class="img-responsive center-block" width="100" />
					<!--a href="<?php echo site_url(); ?>"><b>e-Rapor SMK</b></a-->
				</div>
				<div class="panel-body">
					<h3 class="panel-title text-center">Sistem Informasi Dan E-rapor SMK Amaliyah Sekadau</h3><p></p>
					<?php echo ($this->session->flashdata('error')) ? error_msg($this->session->flashdata('error')) : ''; ?>
					<?php echo isset($error) ? error_msg($error) : ''; ?>
					<?php echo ($this->session->flashdata('success')) ? success_msg($this->session->flashdata('success')) : ''; ?>
					<form action="<?php echo site_url('admin/auth/'); ?>" method="post">
						<div class="form-group has-feedback">
							<input type="text" name="email" class="form-control" placeholder="Email/NUPTK/NISN"/>
							<span class="glyphicon glyphicon-envelope form-control-feedback"></span>
						</div>
						<div class="form-group has-feedback">
							<input type="password" name="password" class="form-control" placeholder="Password"/>
							<span class="glyphicon glyphicon-lock form-control-feedback"></span>
						</div>
						<div class="row">
							<div class="col-xs-8">
								
							</div>
							<!-- /.col -->
							<div class="col-xs-4">
								<button type="submit" class="btn btn-primary btn-block btn-flat">Masuk</button>
							</div>
							<!-- /.col -->
						</div>
					</form>
				</div>


			</div>
		</div>




	</div>
</div>
