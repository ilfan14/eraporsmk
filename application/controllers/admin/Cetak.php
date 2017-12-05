<?php defined('BASEPATH') OR exit('No direct script access allowed');
class Cetak extends Backend_Controller {
	var $B;
	var $I;
	var $U;
	var $HREF; 
	public function __construct(){
		parent::__construct();
		ini_set('max_execution_time', 0); 
		ini_set('memory_limit', '-1'); 
	}
	public function rapor_debug_top_all($kur,$ajaran_id,$rombel_id){
		$data['ajaran_id'] = $ajaran_id;
		$data['rombel_id'] = $rombel_id;
		$data['kurikulum_id'] = $kur;
		$data_siswa = get_siswa_by_rombel($rombel_id);
		$this->load->view('backend/cetak/rapor_header', $data);
		foreach($data_siswa as $siswa){
			$data['siswa_id'] = $siswa->id;
        	$this->load->view('backend/cetak/rapor_cover', $data);
			$this->load->view('backend/cetak/rapor_identitas_sekolah', $data);
			$this->load->view('backend/cetak/rapor_identitas_siswa', $data);
		}
		$this->load->view('backend/cetak/rapor_footer', $data);
	}
	public function rapor_debug_nilai_all($kur,$ajaran_id,$rombel_id){
		$data['ajaran_id'] = $ajaran_id;
		$data['rombel_id'] = $rombel_id;
		$data['kurikulum_id'] = $kur;
		$data_siswa = get_siswa_by_rombel($rombel_id);
		$this->load->view('backend/cetak/rapor_header', $data);
		foreach($data_siswa as $siswa){
			$data['siswa_id'] = $siswa->id;
	       	$this->load->view('backend/cetak/rapor_sikap', $data);
			$this->load->view('backend/cetak/rapor_nilai', $data);
		}
		$this->load->view('backend/cetak/rapor_footer', $data);
	}
	public function rapor_debug_bottom_all($kur,$ajaran_id,$rombel_id){
		$data['ajaran_id'] = $ajaran_id;
		$data['rombel_id'] = $rombel_id;
		$data['kurikulum_id'] = $kur;
		$data_siswa = get_siswa_by_rombel($rombel_id);
		$this->load->view('backend/cetak/rapor_header', $data);
		foreach($data_siswa as $siswa){
			$data['siswa_id'] = $siswa->id;
	    	$this->load->view('backend/cetak/rapor_prakerin', $data);
			$this->load->view('backend/cetak/rapor_ekskul', $data);
			$this->load->view('backend/cetak/rapor_prestasi', $data);
			$this->load->view('backend/cetak/rapor_absen', $data);
			$this->load->view('backend/cetak/rapor_catatan_wali_kelas', $data);
		}
		$this->load->view('backend/cetak/rapor_footer', $data);
	}
	public function rapor_top_all($kur,$ajaran_id,$rombel_id){
        $this->load->library('m_pdf');
		$data['ajaran_id'] = $ajaran_id;
		$data['rombel_id'] = $rombel_id;
		$data['kurikulum_id'] = $kur;
		$rombel = Datarombel::find($rombel_id);
        $pdfFilePath = strtolower(str_replace(' ','_',$rombel->nama)).".pdf";
		$wm = base_url() . 'assets/img/logo.png';
		$this->m_pdf->pdf->SetWatermarkImage($wm);
		$this->m_pdf->pdf->showWatermarkImage = false;
		$rapor_header=$this->load->view('backend/cetak/rapor_header', $data, true);
        $this->m_pdf->pdf->WriteHTML($rapor_header);
		$data_siswa = get_siswa_by_rombel($rombel_id);
		foreach($data_siswa as $siswa){
			$data['siswa_id'] = $siswa->id;
			$this->m_pdf->pdf->SetHTMLFooter('<b style="font-size:8px;"><i>'.$siswa->nama.' - '.$rombel->nama.'<i></b>');
			$rapor_cover=$this->load->view('backend/cetak/rapor_cover', $data, true);
			$this->m_pdf->pdf->WriteHTML($rapor_cover);
			$this->m_pdf->pdf->AddPage('P');		
			$rapor_identitas_sekolah=$this->load->view('backend/cetak/rapor_identitas_sekolah', $data, true);
			$this->m_pdf->pdf->WriteHTML($rapor_identitas_sekolah);
			$this->m_pdf->pdf->AddPage('P');
			$rapor_identitas_siswa=$this->load->view('backend/cetak/rapor_identitas_siswa', $data, true);
			$this->m_pdf->pdf->WriteHTML($rapor_identitas_siswa);
			$this->m_pdf->pdf->AddPage('P');
		}
		$rapor_footer=$this->load->view('backend/cetak/rapor_footer', $data, true);
		$this->m_pdf->pdf->WriteHTML($rapor_footer);
		//download it.
		//Output($file,'I') browser
		//Output($file,'F') simpan di server
		//Output($file,'S') Kirim ke email
		//Output($file,'D') Download
		$this->m_pdf->pdf->Output($pdfFilePath,'I');   
	}
	public function rapor_nilai_all($kur,$ajaran_id,$rombel_id){
        $this->load->library('m_pdf');
		$data['ajaran_id'] = $ajaran_id;
		$data['rombel_id'] = $rombel_id;
		$data['kurikulum_id'] = $kur;
		$rombel = Datarombel::find($rombel_id);
        $pdfFilePath = strtolower(str_replace(' ','_',$rombel->nama)).".pdf";
		$wm = base_url() . 'assets/img/logo.png';
		$this->m_pdf->pdf->SetWatermarkImage($wm);
		$this->m_pdf->pdf->showWatermarkImage = false;
		$css_files = array(
			'assets/bootstrap/css/bootstrap.min.css',
			'assets/css/cetak.css',
		);
		for($i = 0; $i < count($css_files); $i++){
			$stylesheet = file_get_contents($css_files[$i]);
			$this->m_pdf->pdf->WriteHTML($stylesheet,1); // 1 para indicar que es CSS
		}
		$data_siswa = get_siswa_by_rombel($rombel_id);
		foreach($data_siswa as $siswa){
			$data['siswa_id'] = $siswa->id;
			$this->m_pdf->pdf->AddPage('L'); // Adds a new page in Landscape orientation
			$this->m_pdf->pdf->SetHTMLFooter('<b style="font-size:8px;"><i>'.$siswa->nama.' - '.$rombel->nama.'<i></b>');
			$rapor_sikap=$this->load->view('backend/cetak/rapor_sikap', $data, true);
			$this->m_pdf->pdf->WriteHTML($rapor_sikap);
			$this->m_pdf->pdf->AddPage('L'); // Adds a new page in Landscape orientation
			$rapor_nilai=$this->load->view('backend/cetak/'.$kur.'/rapor_nilai', $data, true);
			$this->m_pdf->pdf->WriteHTML($rapor_nilai);
		}
		$rapor_footer=$this->load->view('backend/cetak/rapor_footer', $data, true);
		$this->m_pdf->pdf->WriteHTML($rapor_footer);
        //download it.
		//Output($file,'I') browser
		//Output($file,'F') simpan di server
		//Output($file,'S') Kirim ke email
		//Output($file,'D') Download
		$this->m_pdf->pdf->Output($pdfFilePath,'I');   
	}
	public function rapor_bottom_all($kur,$ajaran_id,$rombel_id){
		$this->load->library('m_pdf');
		$data['ajaran_id'] = $ajaran_id;
		$data['rombel_id'] = $rombel_id;
		$data['kurikulum_id'] = $kur;
		$rombel = Datarombel::find($rombel_id);
        $pdfFilePath = strtolower(str_replace(' ','_',$rombel->nama)).".pdf";
		$wm = base_url() . 'assets/img/logo.png';
		$this->m_pdf->pdf->SetWatermarkImage($wm);
		$this->m_pdf->pdf->showWatermarkImage = false;
		//$this->m_pdf->pdf->AddPage('L'); // Adds a new page in Landscape orientation
		//$rapor_header=$this->load->view('backend/cetak/rapor_header', $data, true);
        //$this->m_pdf->pdf->WriteHTML($rapor_header);
		$css_files = array(
			'assets/bootstrap/css/bootstrap.min.css',
			'assets/css/cetak.css',
		);
		for($i = 0; $i < count($css_files); $i++){
			$stylesheet = file_get_contents($css_files[$i]);
			$this->m_pdf->pdf->WriteHTML($stylesheet,1); // 1 para indicar que es CSS
		}
		$data_siswa = get_siswa_by_rombel($rombel_id);
		foreach($data_siswa as $siswa){
			$data['siswa_id'] = $siswa->id;
			$this->m_pdf->pdf->AddPage('L'); // Adds a new page in Landscape orientation
			$this->m_pdf->pdf->SetHTMLFooter('<b style="font-size:8px;"><i>'.$siswa->nama.' - '.$rombel->nama.'<i></b>');
			$rapor_prakerin=$this->load->view('backend/cetak/rapor_prakerin', $data, true);
			$this->m_pdf->pdf->WriteHTML($rapor_prakerin);
			$rapor_ekskul=$this->load->view('backend/cetak/rapor_ekskul', $data, true);
			$this->m_pdf->pdf->WriteHTML($rapor_ekskul);
			$rapor_prestasi=$this->load->view('backend/cetak/rapor_prestasi', $data, true);
			$this->m_pdf->pdf->WriteHTML($rapor_prestasi);
			$rapor_absen=$this->load->view('backend/cetak/rapor_absen', $data, true);
			$this->m_pdf->pdf->WriteHTML($rapor_absen);
			$rapor_catatan_wali_kelas=$this->load->view('backend/cetak/rapor_catatan_wali_kelas', $data, true);
			$this->m_pdf->pdf->WriteHTML($rapor_catatan_wali_kelas);
		}
		$rapor_footer=$this->load->view('backend/cetak/rapor_footer', $data, true);
		$this->m_pdf->pdf->WriteHTML($rapor_footer);
        //download it.
		//Output($file,'I') browser
		//Output($file,'F') simpan di server
		//Output($file,'S') Kirim ke email
		//Output($file,'D') Download
		$this->m_pdf->pdf->Output($pdfFilePath,'I'); 
	}
	public function rapor_top($kur,$ajaran_id,$rombel_id,$siswa_id){
		$data_rombel = $this->rombongan_belajar->get($rombel_id);
		$loggeduser = $this->ion_auth->user()->row();
		//$this->qr_code($kur,$ajaran_id,$rombel_id,$siswa_id);
        $this->load->library('m_pdf');
		$data['ajaran_id'] = $ajaran_id;
		$data['rombel_id'] = $rombel_id;
		$data['siswa_id'] = $siswa_id;
		$data['kurikulum_id'] = $kur;
		$data['sekolah_id'] = $loggeduser->sekolah_id;
        $pdfFilePath = strtolower(str_replace(' ','_',get_nama_siswa($siswa_id))).".pdf";
		$wm = base_url() . 'assets/img/logo.png';
		$this->m_pdf->pdf->SetWatermarkImage($wm);
		$this->m_pdf->pdf->showWatermarkImage = false;
		//$this->m_pdf->pdf->SetHTMLFooter('<div style="font-size:10px; font-weight:bold; float:left; text-align:left; width:45%;"><i>'.get_nama_siswa($siswa_id).' - '.get_nama_rombel($rombel_id).'</i></div><div style="font-size:12px; float:left;text-align:center; width:10%;"><i>{PAGENO}</i></div><div style="font-size:10px;float:right; text-align:right;"><i>Dicetak dari eRaporSMK Bisa</i></div>');
		$this->m_pdf->pdf->defaultfooterfontsize=7;
		$this->m_pdf->pdf->defaultfooterline=0;
		$this->m_pdf->pdf->SetFooter(get_nama_siswa($siswa_id).' - '.get_nama_rombel($rombel_id).'|{PAGENO}|Dicetak dari eRaporSMK Bisa');
		$rapor_header=$this->load->view('backend/cetak/rapor_header', $data, true);
        $this->m_pdf->pdf->WriteHTML($rapor_header);
		$rapor_cover=$this->load->view('backend/cetak/rapor_cover', $data, true);
        $this->m_pdf->pdf->WriteHTML($rapor_cover);
		$this->m_pdf->pdf->AddPage('P');
		$rapor_identitas_sekolah=$this->load->view('backend/cetak/rapor_identitas_sekolah', $data, true);
        $this->m_pdf->pdf->WriteHTML($rapor_identitas_sekolah);
		$this->m_pdf->pdf->AddPage('P');
		$rapor_identitas_siswa=$this->load->view('backend/cetak/rapor_identitas_siswa', $data, true);
        $this->m_pdf->pdf->WriteHTML($rapor_identitas_siswa);
		$rapor_footer=$this->load->view('backend/cetak/rapor_footer', $data, true);
		$this->m_pdf->pdf->WriteHTML($rapor_footer);
        //download it.
		//Output($file,'I') browser
		//Output($file,'F') simpan di server
		//Output($file,'S') Kirim ke email
		//Output($file,'D') Download
		$this->m_pdf->pdf->Output($pdfFilePath,'I');   
	}
	public function rapor_pdf($kur,$ajaran_id,$rombel_id,$siswa_id){
		$data_rombel = $this->rombongan_belajar->get($rombel_id);
		$loggeduser = $this->ion_auth->user()->row();
		//$this->qr_code($kur,$ajaran_id,$rombel_id,$siswa_id);
        $this->load->library('m_pdf');
		$data['ajaran_id'] = $ajaran_id;
		$data['rombel_id'] = $rombel_id;
		$data['siswa_id'] = $siswa_id;
		$data['kurikulum_id'] = $kur;
		$data['sekolah_id'] = $loggeduser->sekolah_id;
        $pdfFilePath = strtolower(str_replace(' ','_',get_nama_siswa($siswa_id))).".pdf";
		$wm = base_url() . 'assets/img/logo.png';
		$this->m_pdf->pdf->SetWatermarkImage($wm);
		$this->m_pdf->pdf->showWatermarkImage = false;
		$this->m_pdf->pdf->defaultfooterfontsize=7;
		$this->m_pdf->pdf->defaultfooterline=0;
		$this->m_pdf->pdf->SetFooter(get_nama_siswa($siswa_id).' - '.get_nama_rombel($rombel_id).'|{PAGENO}|Dicetak dari eRaporSMK Bisa');
		$this->m_pdf->pdf->AddPage('P', '', 4);
		//$this->m_pdf->pdf->WriteHTML('<pagebreak type="NEXT-ODD" pagenumstyle="10" />');
		$rapor_header=$this->load->view('backend/cetak/rapor_header', $data, true);
        $this->m_pdf->pdf->WriteHTML($rapor_header);
 		$rapor_sikap=$this->load->view('backend/cetak/rapor_sikap', $data, true);
		$this->m_pdf->pdf->WriteHTML($rapor_sikap);
		$this->m_pdf->pdf->AddPage('P'); // Adds a new page in Landscape orientation
 		$rapor_nilai=$this->load->view('backend/cetak/'.$kur.'/rapor_nilai', $data, true);
		$this->m_pdf->pdf->WriteHTML($rapor_nilai);
		if($data_rombel->tingkat != 10){
			$rapor_prakerin=$this->load->view('backend/cetak/rapor_prakerin', $data, true);
			$this->m_pdf->pdf->WriteHTML($rapor_prakerin);
		}
		$rapor_ekskul=$this->load->view('backend/cetak/rapor_ekskul', $data, true);
		$this->m_pdf->pdf->WriteHTML($rapor_ekskul);
		$rapor_prestasi=$this->load->view('backend/cetak/rapor_prestasi', $data, true);
		$this->m_pdf->pdf->WriteHTML($rapor_prestasi);
		$rapor_absen=$this->load->view('backend/cetak/rapor_absen', $data, true);
		$this->m_pdf->pdf->WriteHTML($rapor_absen);
		$rapor_catatan_wali_kelas=$this->load->view('backend/cetak/rapor_catatan_wali_kelas', $data, true);
		$this->m_pdf->pdf->WriteHTML($rapor_catatan_wali_kelas);
		/*$this->m_pdf->pdf->AddPage('P');
		$rapor_ppk=$this->load->view('backend/cetak/rapor_ppk', $data, true);
		$this->m_pdf->pdf->WriteHTML($rapor_ppk);*/
		$rapor_footer=$this->load->view('backend/cetak/rapor_footer', $data, true);
		$this->m_pdf->pdf->WriteHTML($rapor_footer);
        //download it.
		//Output($file,'I') browser
		//Output($file,'F') simpan di server
		//Output($file,'S') Kirim ke email
		//Output($file,'D') Download
		$this->m_pdf->pdf->Output($pdfFilePath,'I');   
	}
	public function rapor_ppk($kur,$ajaran_id,$rombel_id,$siswa_id){
		$data_rombel = $this->rombongan_belajar->get($rombel_id);
		$loggeduser = $this->ion_auth->user()->row();
		$this->load->library('m_pdf');
		$data['ajaran_id'] = $ajaran_id;
		$data['rombel_id'] = $rombel_id;
		$data['siswa_id'] = $siswa_id;
		$data['kurikulum_id'] = $kur;
		$data['sekolah_id'] = $loggeduser->sekolah_id;
        $pdfFilePath = strtolower(str_replace(' ','_',get_nama_siswa($siswa_id))).".pdf";
		$wm = base_url() . 'assets/img/logo.png';
		$this->m_pdf->pdf->SetWatermarkImage($wm);
		$this->m_pdf->pdf->showWatermarkImage = false;
		$this->m_pdf->pdf->defaultfooterfontsize=7;
		$this->m_pdf->pdf->defaultfooterline=0;
		$this->m_pdf->pdf->SetFooter(get_nama_siswa($siswa_id).' - '.get_nama_rombel($rombel_id).'|{PAGENO}|Dicetak dari eRaporSMK Bisa');
		$this->m_pdf->pdf->AddPage('', '', 5);
		$rapor_header=$this->load->view('backend/cetak/rapor_header', $data, true);
		$this->m_pdf->pdf->WriteHTML($rapor_header);
		$rapor_ppk=$this->load->view('backend/cetak/rapor_ppk', $data, true);
		$this->m_pdf->pdf->WriteHTML($rapor_ppk);
		$rapor_footer=$this->load->view('backend/cetak/rapor_footer', $data, true);
		$this->m_pdf->pdf->WriteHTML($rapor_footer);
        //download it.
		//Output($file,'I') browser
		//Output($file,'F') simpan di server
		//Output($file,'S') Kirim ke email
		//Output($file,'D') Download
		$this->m_pdf->pdf->Output($pdfFilePath,'I');   
	}
	public function cover_ppk($kur,$ajaran_id,$rombel_id,$siswa_id){
		$data_rombel = $this->rombongan_belajar->get($rombel_id);
		$loggeduser = $this->ion_auth->user()->row();
		//$this->qr_code($kur,$ajaran_id,$rombel_id,$siswa_id);
        $this->load->library('m_pdf');
		$data['ajaran_id'] = $ajaran_id;
		$data['rombel_id'] = $rombel_id;
		$data['siswa_id'] = $siswa_id;
		$data['kurikulum_id'] = $kur;
		$data['sekolah_id'] = $loggeduser->sekolah_id;
        $pdfFilePath = strtolower(str_replace(' ','_',get_nama_siswa($siswa_id))).".pdf";
		$wm = base_url() . 'assets/img/logo.png';
		$this->m_pdf->pdf->SetWatermarkImage($wm);
		$this->m_pdf->pdf->showWatermarkImage = false;
		$this->m_pdf->pdf->defaultfooterfontsize=7;
		$this->m_pdf->pdf->defaultfooterline=0;
		$this->m_pdf->pdf->SetFooter(get_nama_siswa($siswa_id).' - '.get_nama_rombel($rombel_id).'|{PAGENO}|Dicetak dari eRaporSMK Bisa');
		$rapor_header=$this->load->view('backend/cetak/rapor_header', $data, true);
		$this->m_pdf->pdf->WriteHTML($rapor_header);
		//new line start
		$rapor_cover=$this->load->view('backend/cetak/rapor_cover', $data, true);
        $this->m_pdf->pdf->WriteHTML($rapor_cover);
		$this->m_pdf->pdf->AddPage('P');
		$rapor_identitas_sekolah=$this->load->view('backend/cetak/rapor_identitas_sekolah', $data, true);
        $this->m_pdf->pdf->WriteHTML($rapor_identitas_sekolah);
		$this->m_pdf->pdf->AddPage('P');
		$rapor_identitas_siswa=$this->load->view('backend/cetak/rapor_identitas_siswa', $data, true);
        $this->m_pdf->pdf->WriteHTML($rapor_identitas_siswa);
		$this->m_pdf->pdf->AddPage();
		$rapor_cover_ppk=$this->load->view('backend/cetak/rapor_cover_ppk', $data, true);
		$this->m_pdf->pdf->WriteHTML($rapor_cover_ppk);
        //download it.
		//Output($file,'I') browser
		//Output($file,'F') simpan di server
		//Output($file,'S') Kirim ke email
		//Output($file,'D') Download
		$this->m_pdf->pdf->Output($pdfFilePath,'I');   
	}
	public function qr_code($kur,$ajaran_id,$rombel_id,$siswa_id){
		if($kur == 2013){
			$kelompok_a 		= 1;
			$kelompok_b 		= 2;
			$kelompok_c1 		= 3;
			$kelompok_c2 		= 4;
			$kelompok_c3 		= 5;
			$kelompok_tambahan 	= 99;
		} elseif($kur == 2017){
			$kelompok_a 		= 6;
			$kelompok_b 		= 7;
			$kelompok_c1 		= 8;
			$kelompok_c2 		= 9;
			$kelompok_c3 		= 10;
			$kelompok_tambahan 	= 99;
		} else {
			$kelompok_a 		= 11;
			$kelompok_b 		= 12;
			$kelompok_c1 		= 16;
			$kelompok_c2 		= 17;
			$kelompok_c3 		= 18;
			$kelompok_tambahan 	= 99;
		}
		$s = $this->siswa->get($siswa_id);
		$sekolah = $this->sekolah->get(1);
		$setting = $this->settings->get(1);
		$rombel = $this->rombongan_belajar->get($rombel_id);
		$ajaran = $this->semester->get($ajaran_id);
		$mapel_a = $this->pembelajaran->with('mata_pelajaran')->find_all("semester_id =  $ajaran_id AND  rombongan_belajar_id = $rombel_id AND guru_id !=0 AND mata_pelajaran_id IN(SELECT mata_pelajaran_id FROM mata_pelajaran_kurikulum WHERE kelompok_id = $kelompok_a)");
		$mapel_b = $this->pembelajaran->with('mata_pelajaran')->find_all("semester_id =  $ajaran_id AND  rombongan_belajar_id = $rombel_id AND guru_id !=0 AND mata_pelajaran_id IN(SELECT mata_pelajaran_id FROM mata_pelajaran_kurikulum WHERE kelompok_id = $kelompok_b)");
		$mapel_c1 = $this->pembelajaran->with('mata_pelajaran')->find_all("semester_id =  $ajaran_id AND  rombongan_belajar_id = $rombel_id AND guru_id !=0 AND mata_pelajaran_id IN(SELECT mata_pelajaran_id FROM mata_pelajaran_kurikulum WHERE kelompok_id = $kelompok_c1)");
		$mapel_c2 = $this->pembelajaran->with('mata_pelajaran')->find_all("semester_id =  $ajaran_id AND  rombongan_belajar_id = $rombel_id AND guru_id !=0 AND mata_pelajaran_id IN(SELECT mata_pelajaran_id FROM mata_pelajaran_kurikulum WHERE kelompok_id = $kelompok_c2)");
		$mapel_c3 = $this->pembelajaran->with('mata_pelajaran')->find_all("semester_id =  $ajaran_id AND  rombongan_belajar_id = $rombel_id AND guru_id !=0 AND mata_pelajaran_id IN(SELECT mata_pelajaran_id FROM mata_pelajaran_kurikulum WHERE kelompok_id = $kelompok_c3)");
		$mapel_tambahan = $this->pembelajaran->with('mata_pelajaran')->find_all("semester_id =  $ajaran_id AND  rombongan_belajar_id = $rombel_id AND guru_id !=0 AND mata_pelajaran_id IN(SELECT mata_pelajaran_id FROM mata_pelajaran_kurikulum WHERE kelompok_id = $kelompok_tambahan)");
		$data = 'Nama Siswa : '.get_nama_siswa($siswa_id)."\n";
		if($mapel_a){
			$i=1;
			foreach($mapel_a as $mapela){
				$data .= 'Nilai Mapel '.get_nama_mapel($mapela->mata_pelajaran_id).' P: '.get_nilai_siswa($ajaran_id, 1, $rombel_id, $mapela->mata_pelajaran_id, $s->id)."\n";
				$data .= 'Nilai Mapel '.get_nama_mapel($mapela->mata_pelajaran_id).' K: '.get_nilai_siswa($ajaran_id, 2, $rombel_id, $mapela->mata_pelajaran_id, $s->id)."\n";
			}
		}
		if($mapel_b){
			$i=1;
			foreach($mapel_b as $mapelb){
				$data .= 'Nilai Mapel '.get_nama_mapel($mapelb->mata_pelajaran_id).' P: '.get_nilai_siswa($ajaran_id, 1, $rombel_id, $mapelb->mata_pelajaran_id, $s->id)."\n";
				$data .= 'Nilai Mapel '.get_nama_mapel($mapelb->mata_pelajaran_id).' K: '.get_nilai_siswa($ajaran_id, 2, $rombel_id, $mapelb->mata_pelajaran_id, $s->id)."\n";
			}
		}
		if($mapel_c1){
			$i=1;
			foreach($mapel_c1 as $mapelc1){
				$data .= 'Nilai Mapel '.get_nama_mapel($mapelc1->mata_pelajaran_id).' P: '.get_nilai_siswa($ajaran_id, 1, $rombel_id, $mapelc1->mata_pelajaran_id, $s->id)."\n";
				$data .= 'Nilai Mapel '.get_nama_mapel($mapelc1->mata_pelajaran_id).' K: '.get_nilai_siswa($ajaran_id, 2, $rombel_id, $mapelc1->mata_pelajaran_id, $s->id)."\n";
			}
		}
		if($mapel_c2){
			$i=1;
			foreach($mapel_c2 as $mapelc2){
				$data .= 'Nilai Mapel '.get_nama_mapel($mapelc2->mata_pelajaran_id).' P: '.get_nilai_siswa($ajaran_id, 1, $rombel_id, $mapelc2->mata_pelajaran_id, $s->id)."\n";
				$data .= 'Nilai Mapel '.get_nama_mapel($mapelc2->mata_pelajaran_id).' K: '.get_nilai_siswa($ajaran_id, 2, $rombel_id, $mapelc2->mata_pelajaran_id, $s->id)."\n";
			}
		}
		if($mapel_c3){
			$i=1;
			foreach($mapel_c3 as $mapelc3){
				$data .= 'Nilai Mapel '.get_nama_mapel($mapelc3->mata_pelajaran_id).' P: '.get_nilai_siswa($ajaran_id, 1, $rombel_id, $mapelc3->mata_pelajaran_id, $s->id)."\n";
				$data .= 'Nilai Mapel '.get_nama_mapel($mapelc3->mata_pelajaran_id).' K: '.get_nilai_siswa($ajaran_id, 2, $rombel_id, $mapelc3->mata_pelajaran_id, $s->id)."\n";
			}
		}
		if($mapel_tambahan){
			$i=1;
			foreach($mapel_tambahan as $tambahan){
				$data .= 'Nilai Mapel '.get_nama_mapel($tambahan->mata_pelajaran_id).' P: '.get_nilai_siswa($ajaran_id, 1, $rombel_id, $tambahan->mata_pelajaran_id, $s->id)."\n";
				$data .= 'Nilai Mapel '.get_nama_mapel($tambahan->mata_pelajaran_id).' K: '.get_nilai_siswa($ajaran_id, 2, $rombel_id, $tambahan->mata_pelajaran_id, $s->id)."\n";
			}
		}
		$nama_siswa = str_replace(' ','_',get_nama_siswa($siswa_id));
		$nama_siswa = strtolower($nama_siswa);
		$nama_siswa = $nama_siswa.'_'.$ajaran_id.'_'.$rombel_id;
		$this->load->library('ciqrcode');
		//header("Content-Type: image/png");
		$params['level'] = 'H';
		$params['size'] = 10;
		$params['savename'] = FCPATH.'files/barcode/'.$nama_siswa.'.png';
		$params['data'] = $data;
		$this->ciqrcode->generate($params);
	}
	public function rapor_debug($kur,$ajaran_id,$rombel_id,$siswa_id){
		$data['ajaran_id'] = $ajaran_id;
		$data['rombel_id'] = $rombel_id;
		$data['siswa_id'] = $siswa_id;
		$data['kurikulum_id'] = $kur;
 		$this->load->view('backend/cetak/rapor_header', $data);
        $this->load->view('backend/cetak/rapor_identitas', $data);
        $this->load->view('backend/cetak/rapor_sikap', $data);
		$this->load->view('backend/cetak/rapor_nilai', $data);
		$this->load->view('backend/cetak/rapor_prakerin', $data);
		$this->load->view('backend/cetak/rapor_ekskul', $data);
		$this->load->view('backend/cetak/rapor_prestasi', $data);
		$this->load->view('backend/cetak/rapor_absen', $data);
		$this->load->view('backend/cetak/rapor_catatan_wali_kelas', $data);
		$this->load->view('backend/cetak/rapor_footer', $data);
	}
	public function legger($ajaran_id,$rombel_id,$kompetensi_id){
		$sekolah = $this->sekolah->get(1);
		$ajaran = get_ta();
		$nama_rombel = $this->rombongan_belajar->get($rombel_id);
		$get_wali = $this->guru->get($nama_rombel->guru_id);
		//$data_siswa = Datasiswa::find_all_by_data_rombel_id($rombel_id);
		$data_siswa = get_siswa_by_rombel($rombel_id);
		$data_mapel = $this->pembelajaran->find_all("semester_id = $ajaran_id and rombongan_belajar_id = $rombel_id");
		//Kurikulum::find_all_by_ajaran_id_and_data_rombel_id($ajaran_id,$rombel_id);
		//echo $nama_rombel->nama;
		//echo $get_wali->nama;
		//die();
		$this->load->library('excel');
		$objPHPExcel = new PHPExcel();
		$objPHPExcel->setActiveSheetIndex(0);
        //name the worksheet
		$nama_kompetensi = 'PENGETAHUAN';
		if($kompetensi_id == 2){
			$nama_kompetensi = 'KETERAMPILAN';
		}
		$objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->setBold(true);
		$objPHPExcel->getActiveSheet()->getStyle('A2')->getFont()->setBold(true);
		$objPHPExcel->getActiveSheet()->getStyle('A3')->getFont()->setBold(true);
		$objPHPExcel->getActiveSheet()->getStyle('A1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$objPHPExcel->getActiveSheet()->getStyle('A2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$objPHPExcel->getActiveSheet()->getStyle('A3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$objPHPExcel->getActiveSheet()->getStyle('A7')->getFont()->setBold(true);
		$objPHPExcel->getActiveSheet()->getStyle('B7')->getFont()->setBold(true);
		$objPHPExcel->getActiveSheet()->getStyle('C7')->getFont()->setBold(true);
		$objPHPExcel->getActiveSheet()->getStyle('A8')->getFont()->setBold(true);
		$objPHPExcel->getActiveSheet()->getStyle('B')->getNumberFormat()->setFormatCode('0000000000');
		$objPHPExcel->getActiveSheet()->mergeCells('A8:C8');
		$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
		$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
		$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
		$objPHPExcel->getActiveSheet()->setCellValue('A1', 'LEGGER '.$nama_kompetensi);
		$objPHPExcel->getActiveSheet()->setCellValue('A2', strtoupper($sekolah->nama));
		$objPHPExcel->getActiveSheet()->setCellValue('A3', 'TAHUN PELAJARAN '.strtoupper($ajaran->tahun));
		$objPHPExcel->getActiveSheet()->setCellValue('C4', 'KELAS');
		$objPHPExcel->getActiveSheet()->setCellValue('C5', 'WALI KELAS');
		$objPHPExcel->getActiveSheet()->setCellValue('D4', $nama_rombel->nama);
		$objPHPExcel->getActiveSheet()->setCellValue('D5', $get_wali->nama);
		$objPHPExcel->getActiveSheet()->getStyle('A8')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$objPHPExcel->getActiveSheet()->setCellValue('A8', 'KKM');
		$objPHPExcel->getActiveSheet()->getStyle('A')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$objPHPExcel->getActiveSheet()->setCellValue('A7', 'NO.');
		$objPHPExcel->getActiveSheet()->getStyle('B')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$objPHPExcel->getActiveSheet()->setCellValue('B7', 'NISN');
		$objPHPExcel->getActiveSheet()->getStyle('B7')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$objPHPExcel->getActiveSheet()->setCellValue('C7', 'NAMA SISWA');
        $objPHPExcel->getActiveSheet()->setTitle('LEGGER');
		$row = 9;
		$row_mapel = 7;
		$row_kkm = 8;
		$merger_mapel = 4;
		$merger_wali = 5;
		$x= 'D';
		$plus1 = 'E';
		$plus2 = 'F';
		for($i=0;$i<count($data_mapel);$i++){
			$huruf[] = $x;
			$last = $x;
			$plus_1 = $plus1;
			$plus_2 = $plus2;
			$x++;
			$plus1++;
			$plus2++;
		}
		//echo $plus_1.'<br />';
		//echo $plus_2.'<br />';
		//die();
		$i=1;
		foreach($data_siswa as $get_siswa){
			$siswa = $get_siswa->siswa;
			$objPHPExcel->getActiveSheet()->setCellValue('A'.$row, $i);
			$objPHPExcel->getActiveSheet()->setCellValue('B'.$row, $siswa->nisn);
			$objPHPExcel->getActiveSheet()->setCellValue('C'.$row, $siswa->nama);
			foreach($data_mapel as $key=>$mapel){
				$nilai_value	= get_nilai_siswa($ajaran_id, $kompetensi_id, $rombel_id, $mapel->mata_pelajaran_id, $siswa->id);
				//$all_nilai_remedial = $this->remedial->find("semester_id = $ajaran_id AND kompetensi_id = $kompetensi_id AND rombongan_belajar_id = $rombel_id  AND mata_pelajaran_id = $mapel->mata_pelajaran_id AND siswa_id = $siswa->id");
				//Remedial::find_by_ajaran_id_and_kompetensi_id_and_rombel_id_and_mapel_id_and_data_siswa_id($ajaran_id,$kompetensi_id,$rombel_id,$mapel->mata_pelajaran_id,$siswa->id);
				//if($all_nilai_remedial){
				//	$all_nilai = unserialize($remedial->nilai);
				//	$jumlah_nilai = array_sum($all_nilai);
				//	//$all_nilai_remedial->rerata_remedial;
				//} else {
				//	$all_nilai = $this->nilai->with('rencana_penilaian')->find_all("semester_id = $ajaran_id AND kompetensi_id = $kompetensi_id AND mata_pelajaran_id = $mapel->mata_pelajaran_id AND siswa_id = $siswa->id");
					/*$all_nilai = Nilaiakhir::find_all_by_ajaran_id_and_kompetensi_id_and_rombel_id_and_mapel_id_and_siswa_id($ajaran_id,$kompetensi_id,$rombel_id,$mapel->mata_pelajaran_id,$siswa->id);
					if($all_nilai){
						$nilai_value = 0;
						foreach($all_nilai as $allnilai){
							$nilai_value += $allnilai->nilai;
						}
						$jumlah_nilai = number_format($nilai_value,0);
					} else {
						$jumlah_nilai = 0;
					}*/
				//	if($kompetensi_id == 1){
				//		$n_s_final = 0;
				//		foreach($all_nilai as $set_nilai){
				//			$nilai_siswa[] = $set_nilai->nilai;
				//			$n_s_final += $set_nilai->nilai / count($all_nilai); 
				//		}
				//		$jumlah_nilai = $n_s_final;
				//	} else {
				//		$nilai_siswa = array();
				//		foreach($all_nilai as $set_nilai){
				//			$nilai_siswa[$set_nilai->kompetensi_dasar_id.'_'.$set_nilai->rencana_penilaian->metode_id][] = $set_nilai->nilai;
				//		}
				//		ksort($nilai_siswa, SORT_NUMERIC);
				//		$n_s_final = 0;
				//		foreach($nilai_siswa as $n_s){
				//			if(count($n_s) > 1){
				//				$n_s_final += max($n_s) / count($nilai_siswa);
				//			} else {
				//				$n_s_final += array_sum($n_s) / count($nilai_siswa); 
				//			}
				//		}
				//		$jumlah_nilai = $n_s_final;
				//	}
				//}
				$objPHPExcel->getActiveSheet()->getStyle($huruf[$key].$row_mapel)->getAlignment()->setTextRotation(90);
				$objPHPExcel->getActiveSheet()->getColumnDimension($huruf[$key])->setAutoSize(true);
				$objPHPExcel->getActiveSheet()->getStyle($huruf[$key].$row_mapel)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
				$objPHPExcel->getActiveSheet()->setCellValue($huruf[$key].$row_mapel, get_nama_mapel($mapel->mata_pelajaran_id));
				$objPHPExcel->getActiveSheet()->setCellValue($huruf[$key].$row_kkm, get_kkm($ajaran_id,$rombel_id,$mapel->mata_pelajaran_id));
				$objPHPExcel->getActiveSheet()->setCellValue($huruf[$key].$row, $nilai_value);
				$objPHPExcel->getActiveSheet()->setCellValue($plus_1.$row, '=SUM(D'.$row.':'.$huruf[$key].$row.')');
				$objPHPExcel->getActiveSheet()->setCellValue($plus_2.$row, '=AVERAGE(D'.$row.':'.$huruf[$key].$row.')');
				$objPHPExcel->getActiveSheet()->getStyle($plus_1.$row)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER);
				$objPHPExcel->getActiveSheet()->getStyle($plus_2.$row)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER);
			}
			$i++;
			$row++;
		}
		$objPHPExcel->getActiveSheet()->mergeCells('A1:'.$plus_2.'1');
		$objPHPExcel->getActiveSheet()->mergeCells('A2:'.$plus_2.'2');
		$objPHPExcel->getActiveSheet()->mergeCells('A3:'.$plus_2.'3');
		$objPHPExcel->getActiveSheet()->mergeCells('D4:'.$plus_2.'4');
		$objPHPExcel->getActiveSheet()->mergeCells('D5:'.$plus_2.'5');
		$objPHPExcel->getActiveSheet()->getColumnDimension($plus_1)->setAutoSize(true);
		$objPHPExcel->getActiveSheet()->getColumnDimension($plus_2)->setAutoSize(true);
		//$objPHPExcel->getActiveSheet()->getRowDimension(7)->setRowHeight(100);
		$objPHPExcel->getActiveSheet()->getStyle($plus_1.$row_mapel)->getAlignment()->setTextRotation(90);
		$objPHPExcel->getActiveSheet()->getStyle($plus_2.$row_mapel)->getAlignment()->setTextRotation(90);
		$objPHPExcel->getActiveSheet()->setCellValue($plus_1.$row_mapel, 'JUMLAH');
		$objPHPExcel->getActiveSheet()->setCellValue($plus_2.$row_mapel, 'RATA-RATA');
		$styleArray = array(
						'borders' => array(
							'allborders' => array(
								'style' => PHPExcel_Style_Border::BORDER_THIN,
								'color' => array('argb' => '00000000'),
							),
						),
					);
		$objPHPExcel->getActiveSheet()->getStyle('A7:'.$plus_2.($row - 1))->applyFromArray($styleArray);
        $filename='LEGGER '.$nama_kompetensi.'_'.str_replace(' ','_',$nama_rombel->nama).'.xlsx'; //save our workbook as this file name
        header('Content-Type: application/vnd.ms-excel'); //mime type
        header('Content-Disposition: attachment;filename="'.$filename.'"'); //tell browser what's the file name
        header('Cache-Control: max-age=0'); //no cache
        //save it to Excel5 format (excel 2003 .XLS file), change this to 'Excel2007' (and adjust the filename extension, also the header mime type)
        //if you want to save it as .XLSX Excel 2007 format
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007'); 
        //force user to download the Excel file without writing it to server's HD
        $objWriter->save('php://output');
	}
}