<?php defined('BASEPATH') OR exit('No direct script access allowed');
class Get_excel extends Backend_Controller {
	public function __construct(){
		parent::__construct();
		ini_set('max_execution_time', 0); 
		ini_set('memory_limit', '-1');
		$this->load->library('excel');
	}
	public function perencanaan($kompetensi_id, $rombel_id, $id_mapel){
		$data_rombel = $this->rombongan_belajar->get($rombel_id);
		if($kompetensi_id == 1){
			$aspek = 'P';
			$placeholder = 'UH/UTS/UAS dll...';
		} else {
			$aspek = 'K';
			$placeholder = 'Kinerja/Proyek/Portofolio';
		}
		$objPHPExcel = new PHPExcel();
		$objPHPExcel->setActiveSheetIndex(0);
		$bentuk_penilaian = $this->teknik_penilaian->find_all_by_kompetensi_id($kompetensi_id);
		$configs = '';
		if($bentuk_penilaian){
			foreach($bentuk_penilaian as $value){
				$teknik[] = $value->nama;
			}
			$configs = implode(',',$teknik);
		}
		$all_aktifitas_1 = array('Penugasan 1','Ulangan Harian 1','Penugasan 2', 'Ulangan Harian 2', 'UTS', 'Penugasan 3', 'Ulangan Harian 3', 'Penugasan 4', 'Ulangan Harian 4', 'UAS');
		$kolom_akhir = count($all_aktifitas_1) + 20;
		$objPHPExcel->getActiveSheet()->setCellValue('A1', $id_mapel);
		$objPHPExcel->getActiveSheet()->setCellValue('B1', $rombel_id);
		$objPHPExcel->getActiveSheet()->setCellValue('C1', $kompetensi_id);
		$objPHPExcel->getActiveSheet()->setCellValue('A2', 'Aktifitas Penilaian');
		$objPHPExcel->getActiveSheet()->setCellValue('B2', 'Teknik');
		$objPHPExcel->getActiveSheet()->setCellValue('C2', 'Bobot');
		$all_kd = $this->kompetensi_dasar->find_all("aspek = '$aspek' AND mata_pelajaran_id = $id_mapel AND kelas = $data_rombel->tingkat");
		if(!$all_kd){
			$all_kd = $this->kompetensi_dasar->find_all("aspek = 'PK' AND mata_pelajaran_id = $id_mapel AND kelas = $data_rombel->tingkat");
		}
		$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
		$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
		$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
		if($all_kd){
			$start_kd = 'D';
			foreach($all_kd as $kd){
				$objPHPExcel->getActiveSheet()->setCellValue($start_kd.'1', $kd->id);
				$objPHPExcel->getActiveSheet()->setCellValue($start_kd.'2', 'kd_'.$kd->id_kompetensi);
				$objPHPExcel->getActiveSheet()->getColumnDimension($start_kd)->setAutoSize(true);
				$start_kd++;
			}
			$objPHPExcel->getActiveSheet()->setCellValue($start_kd.'2', 'Keterangan');
			$objPHPExcel->getActiveSheet()->getColumnDimension($start_kd)->setAutoSize(true);
			$objPHPExcel->getActiveSheet()->getProtection()->setSheet(true);
			$objPHPExcel->getActiveSheet()->getStyle('A2:'.$start_kd.$kolom_akhir)->getProtection()->setLocked(PHPExcel_Style_Protection::PROTECTION_UNPROTECTED);
			$objPHPExcel->getActiveSheet()->getStyle('A1:'.$start_kd.'1')->applyFromArray(
				array(
					'fill' => array(
						'type' => PHPExcel_Style_Fill::FILL_SOLID,
						'color' => array('rgb' => '000000')
					)
				)
			);
		}
		$start_aktifitas = 3;
		foreach($all_aktifitas_1 as $aktifitas){
			$objPHPExcel->getActiveSheet()->setCellValue('A'.$start_aktifitas, $aktifitas);
			$objPHPExcel->getActiveSheet()->setCellValue('B'.$start_aktifitas, "Pilih Teknik");
			$objValidation = $objPHPExcel->getActiveSheet()->getCell('B'.$start_aktifitas)->getDataValidation();
			$objValidation->setType( PHPExcel_Cell_DataValidation::TYPE_LIST );
			$objValidation->setErrorStyle( PHPExcel_Cell_DataValidation::STYLE_INFORMATION );
			$objValidation->setAllowBlank(false);
			$objValidation->setShowInputMessage(true);
			$objValidation->setShowErrorMessage(true);
			$objValidation->setShowDropDown(true);
			$objValidation->setErrorTitle('Input error');
			$objValidation->setError('Pilihan salah.');
			$objValidation->setPromptTitle('Pilih teknik');
			$objValidation->setPrompt('Silahkan pilih teknik sesuai referensi yang tersedia.');
			$objValidation->setFormula1('"'.$configs.'"');
			$start_aktifitas++;
		}
		$get_nama_rombel = get_nama_rombel($rombel_id);
		$nama_mapel = get_nama_mapel($id_mapel);
		//$filename='template_perencanaan.xlsx'; //save our workbook as this file name
		$filename=strtolower(str_replace(' ','_','template_perencanaan_'.$get_nama_rombel.'_'.$nama_mapel)).'.xlsx'; //save our workbook as this file name

		header('Content-Type: application/vnd.ms-excel'); //mime type
        header('Content-Disposition: attachment;filename="'.$filename.'"'); //tell browser what's the file name
        header('Cache-Control: max-age=0'); //no cache
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007'); 
        $objWriter->save('php://output');
	}
	public function nilai($rencana_penilaian_id){
		$rencana_penilaian = $this->rencana_penilaian->with('mata_pelajaran')->get($rencana_penilaian_id);
		$get_nama_rombel = get_nama_rombel($rencana_penilaian->rombongan_belajar_id);
		$guru_mapel = get_guru_mapel($rencana_penilaian->semester_id,$rencana_penilaian->rombongan_belajar_id,$rencana_penilaian->mata_pelajaran_id,'nama');
		$nama_mapel = get_nama_mapel($rencana_penilaian->mata_pelajaran_id);
		$kkm = get_kkm($rencana_penilaian->semester_id,$rencana_penilaian->rombongan_belajar_id,$rencana_penilaian->mata_pelajaran_id);
		$data_siswa = filter_agama_siswa($nama_mapel,$rencana_penilaian->rombongan_belajar_id);
		$all_kd_nilai = $this->kd_nilai->find_all_by_rencana_penilaian_id($rencana_penilaian_id);
		$objPHPExcel = new PHPExcel();
		$objPHPExcel->setActiveSheetIndex(0);
        //name the worksheet
		$nama_kompetensi = 'Pengetahuan';
		if($rencana_penilaian->kompetensi_id == 2){
			$nama_kompetensi = 'Keterampilan';
		}
		$objPHPExcel->getActiveSheet()->getStyle('C')->getNumberFormat()->setFormatCode('0000000000');
		$objPHPExcel->getActiveSheet()->setCellValue('A1', 'Format Excel Import Nilai eRaporSMK');
		$objPHPExcel->getActiveSheet()->setCellValue('A2', 'Aspek Penilaian');
		$objPHPExcel->getActiveSheet()->setCellValue('A3', 'Aktifitas Penilaian');
		$objPHPExcel->getActiveSheet()->setCellValue('A4', 'Mata Pelajaran');
		$objPHPExcel->getActiveSheet()->setCellValue('A5', 'Rombongan Belajar');
		$objPHPExcel->getActiveSheet()->setCellValue('A6', 'KKM');
		$objPHPExcel->getActiveSheet()->setCellValue('C2', ': '.$nama_kompetensi);
		$objPHPExcel->getActiveSheet()->setCellValue('C3', ': '.$rencana_penilaian->nama_penilaian);
		$objPHPExcel->getActiveSheet()->setCellValue('C4', ': '.$nama_mapel);
		$objPHPExcel->getActiveSheet()->setCellValue('C5', ': '.$get_nama_rombel);
		$objPHPExcel->getActiveSheet()->setCellValue('C6', ': '.$kkm);
		$objPHPExcel->getActiveSheet()->setCellValue('A7', 'NO.');
		$objPHPExcel->getActiveSheet()->getStyle('A')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$objPHPExcel->getActiveSheet()->getStyle('B6')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$objPHPExcel->getActiveSheet()->getStyle('A7:C7')->getAlignment()->setVertical(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$objPHPExcel->getActiveSheet()->getStyle('C')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		
		$objPHPExcel->getActiveSheet()->setCellValue('B7', 'NAMA SISWA');
		
		$objPHPExcel->getActiveSheet()->setCellValue('C7', 'NISN');
		$objPHPExcel->getActiveSheet()->setCellValue('D7', 'NILAI PER KOMPETENSI DASAR');
		$objPHPExcel->getActiveSheet()->mergeCells('A2:B2');
		$objPHPExcel->getActiveSheet()->mergeCells('A3:B3');
		$objPHPExcel->getActiveSheet()->mergeCells('A4:B4');
		$objPHPExcel->getActiveSheet()->mergeCells('A5:B5');
		$objPHPExcel->getActiveSheet()->mergeCells('A6:B6');
		$objPHPExcel->getActiveSheet()->mergeCells('A7:A8');
		$objPHPExcel->getActiveSheet()->mergeCells('B7:B8');
		$objPHPExcel->getActiveSheet()->mergeCells('C7:C8');
        $objPHPExcel->getActiveSheet()->setTitle('Format Excel Import Nilai');
		$huruf_kd_merger = 'C';
		$huruf_kd = 'D';
		foreach($all_kd_nilai as $kd_nilai){
			$objPHPExcel->getActiveSheet()->setCellValue($huruf_kd.'8', "kd_".$kd_nilai->id_kompetensi);
			$huruf_kd_merger++;
			$huruf_kd++;
		}
		$objPHPExcel->getActiveSheet()->mergeCells('C2:'.$huruf_kd_merger.'2');
		$objPHPExcel->getActiveSheet()->mergeCells('C3:'.$huruf_kd_merger.'3');
		$objPHPExcel->getActiveSheet()->mergeCells('C4:'.$huruf_kd_merger.'4');
		$objPHPExcel->getActiveSheet()->mergeCells('C5:'.$huruf_kd_merger.'5');
		$objPHPExcel->getActiveSheet()->mergeCells('C6:'.$huruf_kd_merger.'6');
		$objPHPExcel->getActiveSheet()->mergeCells('A1:'.$huruf_kd_merger.'1');
		$objPHPExcel->getActiveSheet()->getStyle('A1:'.$huruf_kd_merger.'8')->getFont()->setBold(true);
		$objPHPExcel->getActiveSheet()->getStyle('D8:'.$huruf_kd_merger.'8')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		if($huruf_kd_merger != 'D'){
			$objPHPExcel->getActiveSheet()->mergeCells('D7:'.$huruf_kd_merger.'7');
		}
		$row = 9;
		$i=1;
		foreach($data_siswa as $siswa){
			$siswa_id = $siswa->siswa->id;
			$objPHPExcel->getActiveSheet()->setCellValue('A'.$row, $i);
			$objPHPExcel->getActiveSheet()->setCellValue('B'.$row, $siswa->siswa->nama);
			$objPHPExcel->getActiveSheet()->setCellValue('C'.$row, $siswa->siswa->nisn);
			$huruf_kd = 'D';
			foreach($all_kd_nilai as $kd_nilai){
				$nilai = $this->nilai->find("siswa_id = $siswa_id and rencana_penilaian_id = $rencana_penilaian_id AND kompetensi_dasar_id = $kd_nilai->kd_id");
				$nilai_value 	= ($nilai) ? $nilai->nilai : '';
				$objPHPExcel->getActiveSheet()->setCellValue($huruf_kd.$row, $nilai_value);
				$huruf_kd++;
			}
			$i++;
			$row++;
		}
		$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
		$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
		$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
		$styleArray = array(
						'borders' => array(
							'allborders' => array(
								'style' => PHPExcel_Style_Border::BORDER_THIN,
								'color' => array('argb' => '00000000'),
							),
						),
					);
		$objPHPExcel->getActiveSheet()->getStyle('A7:'.$huruf_kd_merger.($row - 1))->applyFromArray($styleArray);
        $filename=strtolower(str_replace(' ','-','format-nilai-eRaporSMK-'.$get_nama_rombel.'-'.$nama_mapel)).'.xlsx'; //save our workbook as this file name
        header('Content-Type: application/vnd.ms-excel'); //mime type
        header('Content-Disposition: attachment;filename="'.$filename.'"'); //tell browser what's the file name
        header('Cache-Control: max-age=0'); //no cache
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007'); 
        $objWriter->save('php://output');
	}
}