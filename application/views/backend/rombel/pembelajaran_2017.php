<style>
.modal-body {
    max-height: calc(100vh - 210px);
    overflow-y: auto;
}
</style>
<div class="row">
	<div class="col-xs-12">
	<form id="pembelajaran">
	<input type="hidden" name="rombel_id" value="<?php echo $data_rombel->id; ?>" />
	<input type="hidden" name="keahlian_id" id="keahlian_id" value="<?php echo $data_rombel->kurikulum_id; ?>" />
	<input type="hidden" name="query" id="query" value="pembelajaran" />
	<?php
	$tingkat = $data_rombel->tingkat;
	$kurikulum_id = $data_rombel->kurikulum_id;
	$all_mapel = $this->mata_pelajaran_kurikulum->with('mata_pelajaran')->find_all("kurikulum_id = $kurikulum_id AND tingkat_pendidikan_id = $tingkat");
	//test($all_mapel);
	//Matpelkomp::find('all', array("conditions" => "kurikulum_id = $kurikulum_id AND  $query_kelas = 1"));
	?>
	<table class="table table-bordered table-hover" id="pembelajaran">
		<thead>
			<th class="text-center" width="5%">No</th>
			<th width="40%">Mata Pelajaran</th>
			<th width="35%">Guru Mata Pelajaran</th>
			<th width="25%">Kelompok</th>
		</thead>
		<tbody id="editable">
	<?php $i=1;
		if($all_mapel){
		foreach($all_mapel as $mapel){
			//test($mapel);
			$query = 'kurikulum';
	?>
		<tr>
			<td><div class="text-center"><?php echo $i; ?></div></td>
			<td>
				<input type="hidden" class="nama_mapel_alias" name="nama_mapel_alias" value="<?php echo get_nama_mapel($mapel->mata_pelajaran_id); ?>" />
				<a class="nama_mapel" href="javascript:void(0)" data-name="nama_mapel_alias" data-value="<?php echo get_nama_mapel($mapel->mata_pelajaran_id); ?>" data-pk="<?php echo $mapel->mata_pelajaran_id; ?>" data-title="Edit Nama Mapel" title="Edit Nama Mapel"><?php echo get_nama_mapel($mapel->mata_pelajaran_id); ?> (<?php echo $mapel->mata_pelajaran_id; ?>)</a>
				<input type="hidden" name="mapel" id="mapel" value="<?php echo $mapel->mata_pelajaran_id; ?>" class="form-control" />
			</td>
			<td>
				<input type="hidden" class="guru" name="guru" value="<?php echo get_guru_mapel($ajaran_aktif,$data_rombel->id,$mapel->mata_pelajaran_id,'id'); ?>" />
				<a class="guru" href="javascript:void(0)" id="country" data-type="select2" data-name="guru" data-value="<?php echo get_guru_mapel($ajaran_aktif,$data_rombel->id,$mapel->mata_pelajaran_id, 'id');?>" title="Pilih Guru"></a>
			</td>
			<td>
				<input type="hidden" name="matpel_kur_id" id="matpel_kur_id" value="<?php echo $mapel->id; ?>" />
				<input type="hidden" class="kelompok_id" name="kelompok_id" value="<?php echo $mapel->kelompok_id; ?>" />
				<a class="kelompok_id" href="javascript:void(0)" id="kelompok_id" data-type="select2" data-name="kelompok_id" data-value="<?php echo $mapel->kelompok_id; ?>" title="Pilih Kelompok"></a>
			</td>
		</tr>
	<?php $i++;}
	} else { ?>
		<tr class="tr_a">
			<td colspan="4">Mata Pelajaran belum tersedia. Silahkan tambah mata pelajaran di menu referensi mata pelajaran</td>
		</tr>
	<?php } ?>
		</tbody>
	</table>
	</form>
	</div>
</div>
<?php echo link_tag('assets/plugins/bootstrap-editable/css/bootstrap-editable.css', 'stylesheet', 'text/css'); ?>
<script src="<?php echo base_url(); ?>assets/plugins/bootstrap-editable/js/jquery.mockjax.js"></script>
<script src="<?php echo base_url(); ?>assets/plugins/bootstrap-editable/js/bootstrap-editable.min.js"></script>
<script src="<?php echo base_url(); ?>assets/js/plugins/jquery-noty/packaged/jquery.noty.packaged.js"></script>
<script>
$.fn.serializeObject = function(){
    var o = {};
    var a = this.serializeArray();
    $.each(a, function() {
        if (o[this.name] !== undefined) {
            if (!o[this.name].push) {
                o[this.name] = [o[this.name]];
            }
            o[this.name].push(this.value || '');
        } else {
            o[this.name] = this.value || '';
        }
    });
    return o;
};
$(function(){
	$.fn.editable.defaults.mode = 'inline';
	$.fn.editable.defaults.url = '<?php echo site_url('admin/rombel/tambah_alias'); ?>';
	$.get('<?php echo site_url('admin/rombel/guru/'); ?>', function( response ) {
		var data = $.parseJSON(response);
		var guru = [];
		$.each(data, function(i, item) {
        	guru.push({id: item.id, text: item.text});
    	});
		$('tbody#editable tr td a.nama_mapel').editable({
				type: 'text',
				pk: 1,
				name: 'nama_mapel_alias',
				title: 'Edit Nama Mapel',
				success: function(response, newValue) {
					$(this).prev().val(newValue);
				}
			});
		$('tbody#editable tr td a.guru').editable({
	        source: guru,
			emptytext : 'Pilih Guru',
    	    select2: {
				dropdownAutoWidth : true,
        	    //width: 300,
            	placeholder: '== Pilih Guru ==',
	            allowClear: true
    	    },
		    success: function(response, newValue) {
				$(this).prev().val(newValue);
    		}
	    });   
	});
	$.get('<?php echo site_url('admin/rombel/kelompok/'.$data_rombel->kurikulum_id); ?>', function( response ) {
		var data = $.parseJSON(response);
		var kelompok = [];
		$.each(data, function(i, item) {
        	kelompok.push({id: item.id, text: item.text});
    	});
		$('tbody#editable tr td a.kelompok_id').editable({
	        source: kelompok,
			emptytext : 'Pilih Kelompok',
    	    select2: {
				dropdownAutoWidth : true,
        	    //width: 300,
            	placeholder: '== Pilih Kelompok ==',
	            allowClear: true
    	    },
		    success: function(response, newValue) {
				$(this).prev().val(newValue);
    		}
	    });   
	});
	$('a.simpan_pembelajaran').click(function(){
		var data = $("form#pembelajaran").serializeObject();
		var result = $.parseJSON(JSON.stringify(data));
		var array_guru = Array.isArray(result.guru);
		if(!array_guru){
			$.ajax({
				url: '<?php echo site_url('admin/rombel/simpan_pembelajaran/'); ?>',
				type: 'post',
				data: {keahlian_id:result.keahlian_id, rombel_id:result.rombel_id, query:result.query, guru_id:result.guru,mapel_id:result.mapel, matpel_kur_id:result.matpel_kur_id, kelompok_id:result.kelompok_id},
				success: function(response){
					var view = $.parseJSON(response);
					noty({
						text        : view.text,
						type        : view.type,
						timeout		: 1500,
						dismissQueue: true,
						layout      : 'topLeft',
						animation: {
							open: {height: 'toggle'},
							close: {height: 'toggle'}, 
							easing: 'swing', 
							speed: 500 
						}
					});
				}
			});
		} else {
			$.each(result.guru, function (i, item) {
				$.ajax({
					url: '<?php echo site_url('admin/rombel/simpan_pembelajaran/'); ?>',
					type: 'post',
					data: {keahlian_id:result.keahlian_id, rombel_id:result.rombel_id, query:result.query, guru_id:item,mapel_id:result.mapel[i], matpel_kur_id:result.matpel_kur_id[i], kelompok_id:result.kelompok_id[i]},
					success: function(response){
						var view = $.parseJSON(response);
						noty({
							text        : view.text,
							type        : view.type,
							timeout		: 1500,
							dismissQueue: true,
							layout      : 'topLeft',
							animation: {
								open: {height: 'toggle'},
								close: {height: 'toggle'}, 
								easing: 'swing', 
								speed: 500 
							}
						});
					}
				});
			});
		}
		/*window.setTimeout(function() { 
			$('#datatable').dataTable().fnReloadAjax();
			$('#modal_content').modal('hide');
		}, 10000);*/
	});
});
</script>