<?php namespace pineapple;

class VPNClient extends Module
{

	public function route()
	{
		switch ($this->request->action) {
			case "getControls":
				$this->getControls();
				break;

			case "handleOpenVPNDepends":
				$this->handleOpenVPNDepends();
				break;

			case "handlePPTPDepends":
				$this->handlePPTPDepends();
				break;
		}
	}

	private function getControls()
	{
		$openVPN = ($this->checkDependency("openvpn-openssl") && $this->checkDependency("openvpn-easy-rsa"));
		$pptpVPN = $this->checkDependency("ppp-mod-pptp");

		$this->response = array("openvpn" => $openVPN, "pptp" => $pptpVPN);
	}

	private function handleOpenVPNDepends()
	{
		$response_array = array();
		$installed = ($this->checkDependency("openvpn-openssl") && $this->checkDependency("openvpn-easy-rsa"));

		if (!$installed) {
			$successOpenSSL = $this->installDependency("openvpn-openssl");
			$successRSA = $this->installDependency("openvpn-easy-rsa");
			$success = ($successRSA && $successOpenSSL);

			$message = "Successfully installed dependencies.";

			if (!$successOpenSSL && $successRSA) {
				$message = "Error occured while installing package 'openvpn-openssl'.";
			} else if ($successOpenSSL && !$successRSA) {
				$message = "Error occured while installing package 'openvpn-easy-rsa'.";
			} else if (!$successOpenSSL && !$successRSA) {
				$message = "Error occured while install packages 'openvpn-openssl' and 'openvpn-easy-rsa'.";
			}

			$response_array = array("control_succcess" => $success, "control_message" => $message);

		} else {
			exec("opkg remove openvpn-openssl openvpn-easy-rsa");
			$removed = !($this->checkDependency("openvpn-openssl") && $this->checkDependency("openvpn-easy-rsa"));
			$message = "Successfully removed dependencies.";
			if (!$removed) {
				$message = "Error removing dependencies.";
			}
			$response_array = array("control_succcess" => $removed, "control_message" => $message);
		}

		$this->response = $response_array;

	}

	private function handlePPTPDepends()
	{
		$response_array = array();
		$installed = $this->checkDependency("ppp-mod-pptp");

		if (!$installed) {
			$success = $this->installDependency("ppp-mod-pptp");

			$message = "Successfully installed dependencies.";

			if (!$success) {
				$message = "Error occured while installing 'ppp-mod-pptp'";
			}

			$response_array = array("control_succcess" => $success, "control_message" => $message);

		} else {
			exec("opkg remove ppp-mod-pptp");
			$removed = !$this->checkDependency("ppp-mod-pptp");
			$message = "Successfully removed dependencies.";
			if (!$removed) {
				$message = "Error removing dependencies.";
			}
			$response_array = array("control_succcess" => $removed, "control_message" => $message);
		}

		$this->response = $response_array;

	}

}