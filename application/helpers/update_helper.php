<?php 
// function to check if the system has been installed
$CI =& get_instance();
function check_update(){
	global $CI;
	$settings = $CI->settings->get(1);
	return $settings->version;
}
function download($versi){
	$file = 'update_'.$versi.'.zip';
	$downloadFrom = 'http://updater.erapor-smk.net/'.$file;
	$curl = curl_init(); 
	$fp = fopen($file, 'w'); 
	curl_setopt($curl, CURLOPT_URL, $downloadFrom); 
	curl_setopt($curl, CURLOPT_FILE, $fp); 
	curl_setopt($curl, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']); 
	curl_exec ($curl);
	$details = curl_getinfo($curl); 
	curl_close ($curl); 
	fclose($fp);
	$respon = $details['http_code'];
	if($respon == '200'){
		$data['text'] = '<p class="text-green"><strong>[BERHASIL]</strong></p>';
		$data['response'] = 1;
	} else {
		$data['text'] = '<p class="text-red"><strong>[GAGAL]</strong></p>';
		$data['response'] = 0;
	}
	return $data;
}
function extract_to($versi){
	$CI = & get_instance();
	$file = 'update_'.$versi.'.zip';
	if(is_file($file)){
		$extracts = $CI->unzip->extract($file);
		if($extracts){
			$data['text'] = '<p class="text-green"><strong>[BERHASIL]</strong></p>';
			$data['response'] = 1;
		} else {
			$data['text'] = '<p class="text-red"><strong>[GAGAL]</strong></p>';
			$data['response'] = 0;
		}
		$CI->unzip->close();
	} else {
		$data['text'] = '<p class="text-red"><strong>[GAGAL]</strong></p>';
		$data['response'] = 0;
	}
	return $data;
}
function update_versi($versi){
	$CI = & get_instance();
	$respon = extract_to($versi);
	$response = $respon['response'];
	if($response){
		$settings 	= $CI->settings->get(1);
		$setting = array('version' => $versi);
		if($CI->settings->update(1,$setting)){
			$data['text'] = '<p class="text-green"><strong>[BERHASIL]</strong></p>';
			$data['response'] = 1;
		} else {
			$data['text'] = '<p class="text-red"><strong>[GAGAL]</strong></p>';
			$data['response'] = 0;
		}
	} else {
		$data['text'] = '<p class="text-red"><strong>[GAGAL]</strong></p>';
		$data['response'] = 0;
	}
	$file = 'update_'.$versi.'.zip'; 
	@unlink($file);
	return $data;
}