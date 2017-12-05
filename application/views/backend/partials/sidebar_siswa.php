<?php
$settings 	= $this->settings->get(1);
$loggeduser = $this->ion_auth->user()->row();
$uri = $this->uri->segment_array();
$user = $this->ion_auth->user()->row();
$ajaran = get_ta();
?>
<aside class="main-sidebar">              
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">
        <!-- Sidebar user panel -->
        <div class="user-panel">
            <div class="pull-left image">
                <?php $img = ($user->photo!= '')  ? site_url(PROFILEPHOTOSTHUMBS.$user->photo) : site_url('assets/img/avatar3.png'); ?>
                <img src="<?php echo $img;?>" class="img-circle" alt="User Image" />
            </div>
            <div class="pull-left info">
                <p>Selamat Datang<br /><?php echo $user->username; ?></p>
            </div>
        </div>
        <!-- sidebar menu: : style can be found in sidebar.less -->
        <ul class="sidebar-menu">
			<li class="header">Periode Aktif : <?php echo $ajaran->tahun.' SMT '.$ajaran->semester;?></li>
            <li class="treeview <?php echo (isset($activemenu) && $activemenu == 'dashboard') ?  'active' : ''; ?>">
                <a href="<?php echo site_url('admin/dashboard'); ?>">
                    <i class="fa fa-dashboard"></i> <span>Beranda</span>
                </a>
            </li>
			<li class="treeview <?php echo (isset($activemenu) && $activemenu == 'monitoring') ?  'active' : ''; ?>">
                <a href="<?php echo site_url('admin/monitoring/prestasi'); ?>">
                    <i class="fa fa-hand-o-right"></i> <span>Prestasi Individu Siswa</span>
                </a>
            </li>
			<li class="treeview <?php echo (isset($activemenu) && $activemenu == 'profil') ?  'active' : ''; ?>">
                <a href="<?php echo site_url('admin/profil/user'); ?>">
                    <i class="fa fa-user"></i> <span>Profil</span>
                </a>
            </li>
			<li class="treeview active">
                <a href="<?php echo site_url('admin/auth/logout'); ?>">
                    <i class="fa fa-power-off"></i> <span>Keluar dari Aplikasi</span>
                </a>
            </li>
        </ul>
    </section>
    <!-- /.sidebar -->
</aside>