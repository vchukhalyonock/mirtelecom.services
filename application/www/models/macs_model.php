<?php
class Macs_model extends CI_Model{
	
	private $_dopvalues_table = 'dopvalues';
	private $_delete_table = 'delete_arp';
	private $_dopfield_id = 4;
	
	public function __construct(){
		parent::__construct();
	}
	
	public function deleteMac($userId, $ip){
		$userId = intval($userId);
		$ip = strval($ip);
		
		$this->db->delete($this->_dopvalues_table, 
				array(
					'parent_id' => $userId,
					'dopfield_id' => 4));
		
		$res = $this->db->where('ip', $ip)->count_all_results($this->_delete_table);
		if($res == 0)
			$this->db->insert($this->_delete_table, array('ip' => $ip));
	}
}
?>