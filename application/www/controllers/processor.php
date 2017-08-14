<?php
class Processor extends CI_Controller{
	
	private $_passphrase = 'ghfdjcnjhjyytldb;tybt';
	
	public function __construct(){
		parent::__construct();
		$this->load->model('macs_model', 'macs');
		$this->load->model('users_model', 'users');
	}
	
	public function index(){
		$this->output->set_output('Hi');
	}
	
	public function deleteMac(){
		if($this->_passphrase == $this->input->get('passphrase', true)){
			$userId = $this->input->get('id', true);
			if($userId > 0){
				$ip = $this->users->getUserIp($userId);
				if($ip !== false){
					$this->macs->deleteMac($userId, $ip);
				}
			}
			$this->output->set_output(json_encode(array('result' => true)));
		}
		else
			$this->output->set_output(json_encode(array('result' => false)));
	}
}
?>