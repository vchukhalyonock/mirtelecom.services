<?php
class Cron extends CI_Controller{
	
	public function __construct(){
		parent::__construct();
		$this->load->model('users_model', 'users');
		$this->load->model('macs_model', 'macs');
		$this->load->model('processor_model', 'processor');
		$this->load->model('smtp_model', 'smtp');
	}
	
	
	public function index(){
		echo 'Hi';
	}
	
	
	public function processingArpTable(){
		//Тянем то, что надо удалить
		$deletedIps = $this->macs->getDeletedIps();
		
		
		//тянем все маки и пользователей из базы
		$arpTable = $this->processor->getAllArpTable();
		//$unknownARPS = $this->processor->getExpiresFromAll($arpTable);
		$knownARPS = $this->processor->getPermanentFromAll($arpTable);
		if(count($knownARPS) > 0){
			foreach ($knownARPS as $arp){
				$data = $this->processor->getMacAndIp($arp);
				if(in_array($data['ip'], $deletedIps)){
					$userId = $this->users->getUserIdByIP($data['ip']);
					if($userId){
						$this->macs->deleteIPRecord($userId, $data['ip']);
						$this->processor->removeFromArp($data['ip']);
					}
				}
			}
		}
		$allUsers = $this->users->getAllUsers();
		foreach ($allUsers as $user){
			$mac = $this->macs->getCurrentMacByUserId($user['id']);
			if($mac)
				$this->processor->setArpRule($user['ip'], $mac);
		}
		
		//проверяем тех, которых нет в базе
		//$arpTable = $this->processor->getAllArpTable();
		$unknownARPS = $this->processor->getExpiresFromAll($arpTable);
		if(count($unknownARPS) > 0){
			foreach ($unknownARPS as $arp){
				$data = $this->processor->getMacAndIp($arp);
				$userId = $this->users->getUserIdByIP($data['ip']);
				if($userId){
					$this->macs->addNewMAC($userId, $data['mac']);
					$this->processor->setArpRule($data['ip'], $data['mac']);
				}
			}
		}
	}
	
	
	public function closeSMTPPorts(){
		$allUsers = $this->users->getAllUsers();
		foreach ($allUsers as $user){
			$this->smtp->closeSMTPPort($user['id'], $user['ip'], "/^91\.225\.[0-9]{1,3}\.[0-9]{1,3}$/");
		}
	}
	
	
	public function lockUsersWithoutMoney(){
		$this->users->lockUsersWithoutMoney();
	}


	public function setAllAgreement() {
		$this->users->setAgreement();
	}


	public function setAgreementTable() {
		$ips = $this->users->getAllUsersIpsForAgreement();
		$this->processor->setAgreementTable($ips);
	}
}
?>