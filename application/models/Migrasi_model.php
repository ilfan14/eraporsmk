<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Migrasi_model extends CI_Model {
	public function jumlah_data(){
		$this->db->select('*, nilais.id as id_nilai, rencana_penilaians.id AS rencana_penilaian_id');
		$this->db->from('nilais');
		$this->db->join('rencana_penilaians', 'nilais.rencana_penilaian_id = rencana_penilaians.id');
		$this->db->join('rencanas', 'rencanas.id = rencana_penilaians.rencana_id');
		$this->db->join('ref_kompetensi_dasar', 'rencana_penilaians.kd_id = ref_kompetensi_dasar.id');
		$query = $this->db->count_all_results();
		return $query;
	}
	public function get_data($limit, $offset){
		$this->db->select('*, nilais.id as id_nilai, rencana_penilaians.id AS rencana_penilaian_id');
		$this->db->from('nilais');
		$this->db->join('rencana_penilaians', 'nilais.rencana_penilaian_id = rencana_penilaians.id');
		$this->db->join('rencanas', 'rencanas.id = rencana_penilaians.rencana_id');
		$this->db->join('ref_kompetensi_dasar', 'rencana_penilaians.kd_id = ref_kompetensi_dasar.id');
		$this->db->limit($limit, $offset);
		$query = $this->db->get();
		return $query->result();
	}
}