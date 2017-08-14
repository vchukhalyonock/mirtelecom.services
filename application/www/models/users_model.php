<?php
class Users_model extends CI_Model{
	
	private $_users_table = 'users';
	
	public function __construct(){
		parent::__construct();
	}
	
	public function getUserIp($userId){
		$userId = intval($userId);
		$restul = false;
		$res = $this->db->select('ip')->from($this->_users_table)->where('id', $userId)->limit(1)->get();
		if($res->num_rows() > 0)
			$result = $res->row()->ip;
		return $result;
	}
}
?>