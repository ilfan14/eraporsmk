<div class="row">
	<div class="col-md-12">
		<div class="box box-primary">
			<div class="box-body">
			<?php
			$hits = '';
			$filename = "./Changelog.txt";
			if (file_exists($filename)) {
				$hits = file_get_contents($filename);
			} else {
				$hits = file_put_contents($filename, '<div id="form">
<legend><h3>Versi 2.0</h3></legend>
<ol>
<li><b style="color:green">[Perbaikan]</b> Perbaikan semua temuan bugs</li>
<li><b style="color:blue">[Pembaruan]</b> Sinkronisasi dengan dapodik.</li>
</ol>
</div>');
				redirect('admin/changelog');
			}
			echo $hits;
			?>
			</div>		
		</div>
	</div>
</div>