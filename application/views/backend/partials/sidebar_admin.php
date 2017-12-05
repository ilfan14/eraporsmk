<?php
$settings 	= $this->settings->get(1);
$loggeduser = $this->ion_auth->user()->row();
$cari_rombel = $this->rombongan_belajar->find_by_guru_id($loggeduser->guru_id);
$uri = $this->uri->segment_array();
$user = $this->ion_auth->user()->row();
$semester = get_ta();
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
			<li class="header">Periode Aktif : <?php echo $semester->tahun.' SMT '.$semester->semester;?></li>
            <li class="treeview <?php echo (isset($activemenu) && $activemenu == 'dashboard') ?  'active' : ''; ?>">
                <a href="<?php echo site_url('admin/dashboard'); ?>">
                    <i class="fa fa-home"></i> <span>Beranda</span>
                </a>
            </li>
			<?php if($settings->sinkronisasi == 1){?>
			<li class="treeview <?php echo (isset($activemenu) && $activemenu == 'sinkronisasi') ?  'active' : ''; ?>">
                <a href="<?php echo site_url('admin/sinkronisasi'); ?>">
                    <i class="fa fa-refresh"></i> <span>Sinkronisasi</span>
                </a>
            </li>
			<?php if($semester->semester == 2){?>
			<li class="treeview <?php echo (isset($activemenu) && $activemenu == 'kirim_nilai') ?  'active' : ''; ?>">
                <a href="<?php echo site_url('admin/kirim-nilai'); ?>">
                    <i class="fa fa-th"></i> <span>Nilai Rapor Dapodik</span>
                </a>
            </li>
			<?php } ?>
			<?php } ?>
			<li class="treeview <?php echo (isset($activemenu) && $activemenu == 'config') ?  'active' : ''; ?>">
                <a href="#">
                    <i class="fa fa-cogs"></i> <span>Konfigurasi</span>
					<span class="pull-right-container">
						<i class="fa fa-angle-left pull-right"></i>
					</span>
                </a>
                <ul class="treeview-menu">
					<li<?php echo (isset($activemenu) && $activemenu == 'config' && isset($uri[3]) && $uri[3] == 'general') ?  ' class="active"' : ''; ?>>
						<a href="<?php echo site_url('admin/config/general'); ?>">
                	    <i class="fa fa-exchange"></i> <span>Konfigurasi Umum</span>
		                </a>
        		    </li>
					<li<?php echo (isset($activemenu) && $activemenu == 'config' && isset($uri[3]) && $uri[3] == 'backup') ?  ' class="active"' : ''; ?>>
        		        <a href="<?php echo site_url('admin/config/backup'); ?>">
                	    <i class="fa fa-exchange"></i> <span>Backup &amp; Restore</span>
		                </a>
        		    </li>
					<li<?php echo (isset($activemenu) && $activemenu == 'config' && isset($uri[3]) && $uri[3] == 'users' || 
					isset($uri[3]) && $uri[3] == 'edit') ?  ' class="active"' : ''; ?>>
        		        <a href="<?php echo site_url('admin/config/users'); ?>">
                	    <i class="fa fa-user"></i> <span>Hak Akses Pengguna</span>
		                </a>
        		    </li>
				</ul>
			</li>
			<li class="treeview <?php echo (isset($activemenu) && $activemenu == 'referensi') ?  'active' : ''; ?>">
                <a href="#">
                    <i class="fa fa-list"></i> <span>Referensi</span>
                    <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
                </a>
                <ul class="treeview-menu">
					<!--li<?php echo (isset($activemenu) && $activemenu == 'referensi' && 
					!isset($uri[2])
					|| 
					isset($uri[2]) && $uri[2] == 'kompetensi_keahlian'
					) ?  ' class="active"' : ''; ?>>
        		        <a href="<?php echo site_url('admin/referensi'); ?>">
                	    <i class="fa fa-hand-o-right"></i> <span>Referensi Kompetensi Keahlian</span>
		                </a>
        		    </li-->
					<li<?php echo (isset($activemenu) && $activemenu == 'referensi' && 
					isset($uri[2]) && $uri[2] == 'data_guru'
					|| 
					isset($uri[3]) && $uri[3] == 'tambah_guru'
					) ?  ' class="active"' : ''; ?>>
        		        <a href="<?php echo site_url('admin/data_guru'); ?>">
                	    <i class="fa fa-hand-o-right"></i> <span>Referensi Guru</span>
		                </a>
        		    </li>
					<li<?php echo (isset($activemenu) && $activemenu == 'referensi' && 
					isset($uri[2]) && $uri[2] == 'rombel'
					|| 
					isset($uri[3]) && $uri[3] == 'tambah_rombel'
					) ?  ' class="active"' : ''; ?>>
        		        <a href="<?php echo site_url('admin/rombel'); ?>">
                	    <i class="fa fa-hand-o-right"></i> <span>Referensi Rombel</span>
		                </a>
        		    </li>
					<li<?php echo (isset($activemenu) && $activemenu == 'referensi' && 
					isset($uri[2]) && $uri[2] == 'data_siswa'
					|| 
					isset($uri[3]) && $uri[3] == 'tambah_siswa'
					) ?  ' class="active"' : ''; ?>>
        		        <a href="<?php echo site_url('admin/data_siswa'); ?>">
                	    <i class="fa fa-hand-o-right"></i> <span>Referensi Siswa</span>
		                </a>
        		    </li>
					<li<?php echo (isset($activemenu) && $activemenu == 'referensi' && 
					isset($uri[3]) && $uri[3] == 'mata_pelajaran'
					|| 
					isset($uri[3]) && $uri[3] == 'tambah_mapel'
					) ?  ' class="active"' : ''; ?>>
        		        <a href="<?php echo site_url('admin/referensi/mata_pelajaran'); ?>">
                	    <i class="fa fa-hand-o-right"></i> <span>Referensi Mata Pelajaran</span>
		                </a>
        		    </li>
					<li<?php echo (isset($activemenu) && $activemenu == 'referensi' && 
					isset($uri[3]) && $uri[3] == 'kkm' 
					|| 
					isset($uri[3]) && $uri[3] == 'add_kkm' 
					|| 
					isset($uri[3]) && $uri[3] == 'edit_kkm'
					) ?  ' class="active"' : ''; ?>>
        		        <a href="<?php echo site_url('admin/referensi/kkm'); ?>">
                	    <i class="fa fa-hand-o-right"></i> <span>Referensi KB (KKM)</span>
		                </a>
        		    </li>
					<li<?php echo (isset($activemenu) && $activemenu == 'referensi' && 
					isset($uri[3]) && $uri[3] == 'ekskul' 
					|| 
					isset($uri[3]) && $uri[3] == 'add_ekskul' 
					|| 
					isset($uri[3]) && $uri[3] == 'edit_ekskul'
					) ?  ' class="active"' : ''; ?>>
        		        <a href="<?php echo site_url('admin/referensi/ekskul'); ?>">
                	    <i class="fa fa-hand-o-right"></i> <span>Referensi Ekstrakurikuler</span>
		                </a>
        		    </li>
					<li<?php echo (isset($activemenu) && $activemenu == 'referensi' && 
					isset($uri[3]) && $uri[3] == 'metode' 
					|| 
					isset($uri[3]) && $uri[3] == 'add_metode' 
					|| 
					isset($uri[3]) && $uri[3] == 'edit_metode'
					) ?  ' class="active"' : ''; ?>>
        		        <a href="<?php echo site_url('admin/referensi/metode'); ?>">
                	    <i class="fa fa-hand-o-right"></i> <span>Referensi Teknik Penilaian</span>
		                </a>
        		    </li>
					<li<?php echo (isset($activemenu) && $activemenu == 'referensi' && 
					isset($uri[3]) && $uri[3] == 'sikap' 
					|| 
					isset($uri[3]) && $uri[3] == 'add_sikap' 
					|| 
					isset($uri[3]) && $uri[3] == 'edit_sikap'
					) ?  ' class="active"' : ''; ?>>
        		        <a href="<?php echo site_url('admin/referensi/sikap'); ?>">
                	    <i class="fa fa-hand-o-right"></i> <span>Acuan Sikap</span>
		                </a>
        		    </li>
				</ul>
			</li>
			<li class="treeview <?php echo (isset($activemenu) && $activemenu == 'profil') ?  'active' : ''; ?>">
                <a href="#">
                    <i class="fa fa-user"></i> <span>Profil</span>
                    <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
                </a>
                <ul class="treeview-menu">
					<li<?php echo (isset($activemenu) && $activemenu == 'profil' && isset($uri[3]) && $uri[3] == 'sekolah') ?  ' class="active"' : ''; ?>>
						<a href="<?php echo site_url('admin/profil/sekolah'); ?>"><i class="fa fa-hand-o-right"></i> Profil Sekolah</a>
					</li>
					<li<?php echo (isset($activemenu) && $activemenu == 'profil' && isset($uri[3]) && $uri[3] == 'user') ?  ' class="active"' : ''; ?>>
						<a href="<?php echo site_url('admin/profil/user'); ?>"><i class="fa fa-hand-o-right"></i> Profil User</a>
					</li>
                </ul>
            </li>
			<!-- <li class="treeview <?php echo (isset($activemenu) && $activemenu == 'changelog') ?  'active' : ''; ?>">
                <a href="<?php echo site_url('admin/changelog'); ?>">
                    <i class="fa fa-check-square-o"></i> <span>Daftar Perubahan</span>
                </a>
            </li> -->
			<!-- <li class="treeview <?php echo (isset($activemenu) && $activemenu == 'update') ?  'active' : ''; ?>">
                <a href="<?php echo site_url('admin/check_update'); ?>">
                    <i class="fa fa-refresh"></i> <span>Cek Pembaharuan</span>
                </a>
            </li> -->

            <li class="treeview <?php echo (isset($activemenu) && $activemenu == 'smsgateway') ?  'active' : ''; ?>">
                <a href="<?php echo site_url('admin/smsgateway'); ?>">
                    <i class="fa fa-paper-plane-o"></i> <span>Sms Gateway</span>
                </a>
            </li>


			<li class="treeview">
                <a href="<?php echo site_url('admin/auth/logout'); ?>">
                    <i class="fa fa-power-off"></i> <span>Keluar dari Aplikasi</span>
                </a>
            </li>
        </ul>
		<!-- <div class="text-center" style="margin-top:10px;"><img src="<?php echo base_url(); ?>assets/img/smk-bisa-smk-hebat.png" width="150" /></div> -->
    </section>
    <!-- /.sidebar -->
</aside>