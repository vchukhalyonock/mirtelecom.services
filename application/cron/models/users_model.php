<?php
class Users_model extends CI_Model{
	
	private $_user_table = 'users';
	
	public function __construct(){
		parent::__construct();
	}
	
	
	public function getUserIdByIP($ip){
		$ip = strval($ip);
		$result = false;
		$res = $this->db->select('id')->from($this->_user_table)
			->where('ip', $ip)->limit(1)->get();
		if($res->num_rows() > 0)
			$result = $res->row()->id;
		return $result;
	}
	
	
	public function getAllUsers(){
		$res = $this->db->select('id, ip')->from($this->_user_table)->get();
		$result = array();
		if($res->num_rows() > 0)
			foreach ($res->result_array() as $result[]);
		
		return $result;
	}
	
	
	public function lockUsersWithoutMoney(){
		$this->db->update($this->_user_table,
				array('paket' => 1),
				array(
						'mid' => 0,
						'state' => 'off',
						'balance < limit_balance' => null
						)
				);
	}


	public function setAgreement() {
		$this->db->update($this->_user_table,
				array('agreement' => 1),
				array('agreement' => 0)
			);
	}


	public function getAllUsersIpsForAgreement(){
		$result = array();

		$res = $this->db
			->select('ip')
			->from($this->_user_table)
			->where('agreement', 1)
			->get();
		$result = array();
		if($res->num_rows() > 0)
			foreach ($res->result() as $res){
				$result[] = $res->ip;
			};
		
		return $result;
	}
}
?>