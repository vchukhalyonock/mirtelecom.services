<?php
class smtp_model extends CI_Model{
	
	private $_dopvalues_table = 'dopvalues';
	private $_delete_table = 'delete_arp';
	private $_dopfield_id = 3;
	
	public function __construct(){
		parent::__construct();
		$this->load->model('macs_model', 'macs');
	}
	
	public function closeSMTPPort($userId, $ip, $mask){
		$userId = intval($userId);
		$ip = strval($ip);
		$mask = strval($mask);
		
		if(!preg_match($mask, $ip)){
			$resMaxRevision = $this->db->select_max('revision')->from($this->_dopvalues_table)->get();
			if($resMaxRevision->num_rows() > 0){
				$revision = $resMaxRevision->row()->revision;
				$mac = $this->macs->getCurrentMacByUserId($userId);
				if($mac !== false){
					$this->db->insert($this->_dopvalues_table,
							array(
									'parent_id' => $userId,
									'dopfield_id' => 4,
									'field_value' => $mac,
									'admin_id' => 1,
									'time' => time(),
									'revision' => $revision));
				}
				
				$this->db->insert($this->_dopvalues_table,
						array(
								'parent_id' => $userId,
								'dopfield_id' => 3,
								'field_value' => 0,
								'admin_id' => 1,
								'time' => time(),
								'revision' => $revision));
				
				$this->db->insert($this->_dopvalues_table,
						array(
								'parent_id' => $userId,
								'dopfield_id' => 14,
								'field_value' => 1,
								'admin_id' => 1,
								'time' => time(),
								'revision' => $revision));
			}
		}
	}
}
?>