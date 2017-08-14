<?php
class Processor_model extends CI_Model{
	
	private $_arp = '';
	private $_ipfw = '';
	private $_exclude_interfaces = '';
	
	public function __construct(){
		parent::__construct();
		$this->_arp = $this->config->item('arp_path');
		$this->_ipfw = $this->config->item('ipfw_path');
		$this->_exclude_interfaces = $this->config->item('exclude_interfaces');
	}
	
	
	public function getExpiresFromAll($arpOut){
		$arpOut = strval($arpOut);
		$strings = explode("\n", $arpOut);
		$result = array();
		foreach ($strings as $arp){
			if(stristr($arp, 'expires'))
				$result[] = $arp;
		}
		return $result;
	}
	
	public function getPermanentFromAll($arpOut){
		$arpOut = strval($arpOut);
		$strings = explode("\n", $arpOut);
		$result = array();
		foreach ($strings as $arp){
			if(stristr($arp, 'permanent'))
				$result[] = $arp;
		}
		return $result;
	}
	
	public function getMacAndIp($arp){
		$arp = strval($arp);
		preg_match("/^(\? \()([0-9.]*)(\) at )([0-9a-z:]*)( .*)$/i", $arp, $parts);
		$result = array(
				'ip' => $parts[2],
				'mac' => $parts[4]);
		return $result;
	}
	
	
	public function setArpRule($ip, $mac){
		$ip = strval($ip);
		$mac = strval($mac);
		shell_exec("{$this->_arp} -s {$ip} {$mac}");
		//echo "{$this->_arp} -s {$ip} {$mac}\n";
	}
	
	
	public function getAllArpTable(){
		$result = shell_exec("{$this->_arp} -an | grep -v '{$this->_exclude_interfaces}'");
		return $result;
	}
	
	
	public function removeFromArp($ip){
		$ip = strval($ip);
		shell_exec("{$this->_arp} -d {$ip}");
		//echo "{$this->_arp} -d {$id}\n";
	}


	public function setAgreementTable($ips) {
		shell_exec("{$this->_ipfw} table 45 flush");
		foreach ($ips as $ip) {
			shell_exec("{$this->_ipfw} table 45 add {$ip}/32");
		}
	}
}
?>