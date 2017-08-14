<?php
class Macs_model extends CI_Model{
	
	private $_dopvalues_table = 'dopvalues';
	private $_delete_table = 'delete_arp';
	private $_dopfield_id = 4;
	
	public function __construct(){
		parent::__construct();
	}
	
	public function getCurrentMacByUserId($userId){
		$userId = intval($userId);
		$result = false;
		$res = $this->db->select('field_value')
			->from($this->_dopvalues_table)
			->where(array(
					'parent_id' => $userId,
					'dopfield_id' => $this->_dopfield_id))
			->order_by('revision', 'desc')->limit(1)->get();
		if($res->num_rows() > 0)
			$result = $res->row()->field_value;
		return $this->isMAC($result) ? $result : false;
	}
	
	private function isMAC($mac){
		return preg_match("/^([0-9a-fA-F]{2}([:-]|$)){6}$/i", $mac);
	}
	
	public function addNewMAC($userId, $mac){
		$userId = intval($userId);
		$mac = strval($mac);
		
		$resMaxRevision = $this->db->select_max('revision')->from($this->_dopvalues_table)->get();
		if($resMaxRevision->num_rows() > 0){
			$revision = $resMaxRevision->row()->revision + 1;
			$this->db->insert($this->_dopvalues_table,
					array(
							'parent_id' => $userId,
							'dopfield_id' => $this->_dopfield_id,
							'field_value' => $mac,
							'admin_id' => 1,
							'time' => time(),
							'revision' => $revision));
			
			$this->db->insert($this->_dopvalues_table,
					array(
							'parent_id' => $userId,
							'dopfield_id' => 3,
							'field_value' => 1,
							'admin_id' => 1,
							'time' => time(),
							'revision' => $revision));
		}
	}
	
	
	public function getDeletedIps(){
		$result = array();
		$res = $this->db->select('ip')->from($this->_delete_table)->get();
		if($res->num_rows() > 0){
			foreach ($res->result() as $row)
				$result[] = $row->ip;
		}
		return $result;
	}
	
	
	public function deleteIPRecord($userId, $ip){
		$this->db->delete($this->_delete_table, array('ip' => $ip));
		$this->addNewMAC($userId, '');
	}
}
?>