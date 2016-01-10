<?php namespace pineapple;

class VPNClient extends Module
{

	public function route()
	{
		switch ($this->request) {
			case "checkDependencies":
				break;
		}
	}

	private function checkDependencies()
	{
		$openVPN = ($this->checkDependency("openvpn-openssl") && $this->checkDependency("openvpn-easy-rsa"));
		$pptpVPN = false;
	}

}