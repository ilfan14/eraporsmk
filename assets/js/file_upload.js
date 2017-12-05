$('#fileupload').fileupload({
	url: SET_URL,
	dataType: 'json',
}).on('fileuploadprogress', function (e, data) {
	var progress = parseInt(data.loaded / data.total * 100, 10);
	$('#progress .progress-bar').css('width',progress + '%');
}).on('fileuploadsubmit', function (e, data) {
	$('#progress').show();
}).on('fileuploaddone', function (e, data) {
	window.setTimeout(function() { 
		$('#progress').hide();
		$('#progress .progress-bar').css('width','0%');
	}, 1000);
	var cari_form = $('body').find('.set_nilai');
	$(cari_form).each(function(i,v) {
		var cari_input = $(this).next().find('input[type!=hidden]:not([readonly])');
		$(cari_input).each(function(a) {
			console.log(a);
			$(this).val(data.result[i].nilai[a]);
		});
		var cari_readonly = $(this).next().find("input[name='rerata[]']");
		$(cari_readonly).each(function(a) {
			console.log(data.result[i].rerata);
			$(this).val(data.result[i].rerata);
		});
	});
}).prop('disabled', !$.support.fileInput).parent().addClass($.support.fileInput ? undefined : 'disabled');
$(function() {
	var formData = '';
	var get_all_kd = $('#jumlah_kd').val();
	$("input[type=text]").on("keyup", function() {
		var textVal = 0;
		var $tr = $(this).closest("tr");
		var $input = $tr.find("input[type!=hidden]:not([readonly])").each(function() {
			textVal += parseInt(this.value);
		});
		var $set_rerata_akhir = textVal / parseInt(get_all_kd);
		var $rerata_akhir = $tr.find("input[name='rerata[]']").val($set_rerata_akhir.toFixed(0));
		console.log('1:'+textVal);
		console.log('2:'+parseInt(get_all_kd));
		console.log('3:'+$set_rerata_akhir.toFixed(0));
		console.log($(this).val());
	});
});