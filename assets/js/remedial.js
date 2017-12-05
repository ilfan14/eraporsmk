$(function() {
	var formData = '';
	var get_all_kd = $('#get_all_kd').val();
	var get_kkm = $('#get_kkm').val();
	$("input[type=text]").on("keyup", function() {
		var textVal = 0;
		var $tr = $(this).closest("tr");
		var $input = $tr.find("input:text").each(function() {
			if(parseInt(this.value) >= parseInt(get_kkm)){
				$(this).removeClass('bg-red').addClass('bg-green');
			}
			if(parseInt(this.value) < parseInt(get_kkm)){
				$(this).removeClass('bg-green').addClass('bg-red');
			}
			textVal += parseInt(this.value);
		});
		var $set_rerata_akhir = textVal / parseInt(get_all_kd);
		var $rerata_akhir = $tr.find('#rerata_remedial').html('<input type="hidden" id="rerata_remedial_input" name="rerata_remedial[]" value="'+$set_rerata_akhir.toFixed(0)+'" /><strong>'+$set_rerata_akhir.toFixed(0)+'</strong>');
		if($set_rerata_akhir.toFixed(0) >= parseInt(get_kkm)){
			$tr.find('#rerata_remedial').removeClass('text-red').addClass('text-green');
		}
		if($set_rerata_akhir.toFixed(0) < parseInt(get_kkm)){
			$tr.find('#rerata_remedial').removeClass('text-green').addClass('text-red');
		}
		console.log(textVal);
		//console.log($(this).val());
		console.log(parseInt(get_all_kd));
		console.log($set_rerata_akhir.toFixed(0));
	});
});