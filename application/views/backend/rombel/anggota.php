<?php
/*
<style>
#sortable1, #sortable2 {list-style-type: none;margin: 0;padding: 5px 0 0 0;border: 1px solid #eee;
overflow: scroll;
height: 450px;}
#sortable1 li, #sortable2 li {border: 1px solid #eee;margin: 0 5px 5px 5px;padding: 5px;}
#sortable1 li:hover, #sortable2 li:hover{cursor:move;}
.ui-state-highlight{background:#CCCCCC;height: 2.5em;}
.ui-sortable-helper {
    display: table;
}
</style>
<input type="hidden" id="rombel_id" value="<?php echo $id_rombel; ?>" />
<script>
$(function(){
	var rombel_id = $('#rombel_id').val();
	$( "#sortable1, #sortable2" ).sortable({
		placeholder: "ui-state-highlight",
		connectWith: ".connectedSortable"
	}).disableSelection();
	$( "#sortable2" ).on( "sortreceive", function( event, ui ) {
		var siswa_id = ui.item.find('input').val();
		console.log('receive');
		$.ajax({
			url: '<?php echo site_url('admin/rombel/simpan_anggota');?>',
			type: 'post',
			data: {rombel_id:rombel_id,siswa_id:siswa_id},
			success: function(response){
				var view = $.parseJSON(response);
				noty({
					text        : view.text,
					type        : view.type,
					timeout		: 1500,
					dismissQueue: true,
					layout      : 'top',
					animation: {
						open: {height: 'toggle'},
						close: {height: 'toggle'}, 
						easing: 'swing', 
						speed: 500 
					}
				});
			}
		});
	} );
	$( "#sortable2" ).on( "sortremove", function( event, ui ) {
		var siswa_id = ui.item.find('input').val();
		console.log('remove');
		$.ajax({
			url: '<?php echo site_url('admin/rombel/hapus_anggota');?>',
			type: 'post',
			data: {rombel_id:rombel_id,siswa_id:siswa_id},
			success: function(response){
				var view = $.parseJSON(response);
				noty({
					text        : view.text,
					type        : view.type,
					timeout		: 1500,
					dismissQueue: true,
					layout      : 'top',
					animation: {
						open: {height: 'toggle'},
						close: {height: 'toggle'}, 
						easing: 'swing', 
						speed: 500 
					}
				});
			}
		});
	} );
});
</script>
<div class="row">
	<div class="row">
		<div class="col-xs-12">
			<ul id="sortable1" class="connectedSortable col-xs-6">
			<?php foreach($free as $f){ ?>
				<li class="ui-state-default">
				<input type="hidden" name="siswa" value="<?php echo $f->id; ?>" />
				<?php echo $f->nama; ?>
				</li>
			<?php } ?>
			</ul>
			<form id="anggota">
			<ul id="sortable2" class="connectedSortable col-xs-6">
				<?php foreach($anggota as $a){
				$siswa = Datasiswa::find_by_id($a->siswa_id);
				?>
				<li class="ui-state-highlight">
					<input type="hidden" name="siswa" value="<?php echo isset($siswa->id) ? $siswa->id : ''; ?>" />
					<?php echo isset($siswa->nama) ? $siswa->nama : ''; ?>
				</li>
				<?php } ?>
			</ul>
			</form>
		</div>
     </div>
</div>
<script src="<?php echo base_url(); ?>assets/js/jquery.noty.packaged.js"></script>
<script type="text/javascript">
$.fn.serializeObject = function()
{
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
$('.simpan_anggota').click(function(){
	var data = $("form#anggota").serializeObject();
	var result = $.parseJSON(JSON.stringify(data));
	console.log(result);
	$.each(result.siswa, function (i, item) {
		console.log(item);
		$.ajax({
			url: '<?php echo site_url('admin/rombel/simpan_anggota');?>',
			type: 'post',
			data: {rombel_id:result.rombel_id,siswa_id:item},
			success: function(response){
				var view = $.parseJSON(response);
				noty({
					text        : view.text,
					type        : view.type,
					timeout		: 1500,
					dismissQueue: true,
					layout      : 'top',
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
	$('#modal_content').modal('hide');
});
</script>
*/
?>
<style>
ul.simple_with_animation li {cursor: move;display: block;margin: 5px;padding: 5px;border: 1px solid #cccccc;color: #0088cc;background: #eeeeee;}

/* line 51, /Users/jonasvonandrian/jquery-sortable/source/css/application.css.sass */
ul.simple_with_animation {list-style-type: none;}
.simple_with_animation {border: 1px solid #999999; min-height:450px; max-height:450px; overflow: auto; margin-right:5px;}
</style>
	<div class="row">
		<div class="col-xs-12">
			<ul id="free" class="simple_with_animation col-xs-5">
				<label>Data siswa belum memiliki rombel</label>
			<?php if($free){
				foreach($free as $f){ ?>
				<li class="ui-state-default" data-siswa_id="<?php echo $f->id; ?>" data-rombel_id="0">
				<?php echo $f->nama; ?>
				</li>
			<?php } 
			}
			?>
			</ul>
			<ul id="anggota" class="simple_with_animation col-xs-6">
				<label>Data siswa didalam rombel <?php echo get_nama_rombel($id_rombel); ?></label>
				<?php if($anggota){
				 foreach($anggota as $a){
				//$siswa = Datasiswa::find_by_id($a->siswa_id);
				?>
				<li class="ui-state-default" data-siswa_id="<?php echo isset($a->siswa->id) ? $a->siswa->id : ''; ?>" data-rombel_id="<?php echo isset($id_rombel) ? $id_rombel : ''; ?>">
					<input type="hidden" name="siswa" value="<?php echo isset($a->siswa->id) ? $a->siswa->id : ''; ?>" />
					<?php echo isset($a->siswa->nama) ? $a->siswa->nama : ''; ?>
				</li>
				<?php } 
				}?>
			</ul>
		</div>
     </div>
<input type="hidden" id="rombel_id" value="<?php echo $id_rombel; ?>" />
<script src="<?php echo base_url(); ?>assets/js/plugins/jquery-noty/packaged/jquery.noty.packaged.js"></script>
<script src="<?php echo base_url(); ?>assets/js/jquery-sortable.js"></script>
<script>
var rombel_id = $('#rombel_id').val();
var adjustment;
var url;
$("ul.simple_with_animation").sortable({
  group: 'simple_with_animation',
  pullPlaceholder: false,
  // animation on drop
  onDrop: function  ($item, container, _super, event) {
    var $clonedItem = $('<li/>').css({height: 0});
    $item.before($clonedItem);
    $clonedItem.animate({'height': $item.height()});
	var $get_id = $item.closest("ul");
	var div_id = $get_id.attr('id');
	var siswa_id = $item.data("siswa_id");
	if(div_id == 'anggota'){
		url = '<?php echo site_url('admin/rombel/simpan_anggota');?>';
	}else if(div_id == 'free'){
		url = '<?php echo site_url('admin/rombel/hapus_anggota');?>';
	} else {
		url = '<?php echo site_url('admin/rombel/set_');?>unknow';
	}
	$.ajax({
		url: url,
		type: 'post',
		data: {siswa_id:siswa_id,rombel_id:rombel_id},
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
					speed: 100 
				}
			});
		}
	});
	console.log(url);
    //var jsonString = JSON.stringify(data, null, ' ');
    $item.animate($clonedItem.position(), function  () {
      $clonedItem.detach();
      _super($item, container);
    });
  },

  // set $item relative to cursor position
  onDragStart: function ($item, container, _super) {
    var offset = $item.offset(),
        pointer = container.rootGroup.pointer;

    adjustment = {
      left: pointer.left - offset.left,
      top: pointer.top - offset.top
    };
    _super($item, container);
  },
  onDrag: function ($item, position) {
    $item.css({
      left: position.left - adjustment.left,
      top: position.top - adjustment.top
    });
  }
});
</script>