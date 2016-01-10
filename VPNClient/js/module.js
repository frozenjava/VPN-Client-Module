registerController("VPNClientController", ['$api', '$scope', function($api, $scope) {

	getControls();

	$scope.messages = [];
	$scope.throbber = false;
	$scope.openVPNInstalled = false;
	$scope.pptpVPNInstalled = false;
	$scope.uploadKeys = {};

	$scope.sendMessage = function(t, m) {
		// Add a new message to the top of the list
		$scope.messages.unshift({title: t, msg: m});

		// if there are 4 items in the list remove the 4th item
		if ($scope.messages.length == 4) {
			$scope.dismissMessage(3);
		}
	}

	$scope.dismissMessage = function($index) {
		//var index = $scope.messages.indexOf(message);
		$scope.messages.splice($index, 1);
	}

	$scope.handleControl = function(control) {
		control.throbber = true;
		switch (control.title) {
			case "OpenVPN Dependencies":
				$api.request({
					module: "VPNClient",
					action: "handleOpenVPNDepends"
				}, function(response) {
					getControls();
					control.throbber = false;
					$scope.sendMessage(control.title, response.control_message);
				});
				break;

			case "PPTP Dependencies":
				$api.request({
					module: "VPNClient",
					action: "handlePPTPDepends"
				}, function(response) {
					getControls();
					control.throbber = false;
					$scope.sendMessage(control.title, response.control_message);
				});
				break;
		}
	}

	function getControls() {
		$scope.throbber = true;
		$api.request({
			module: "VPNClient",
			action: "getControls"
		}, function(response) {
			console.log(response);
			updateControls(response);
		});
	}

	function updateControls(response) {
		var openVPNStatus = "Install";
		var pptpStatus = "Install";
		$scope.openVPNInstalled = false;
		$scope.pptpVPNInstalled = false;

		if (response.openvpn) {
			openVPNStatus = "Uninstall";
			$scope.openVPNInstalled = true;
		}

		if (response.pptp) {
			pptpStatus = "Uninstall";
			$scope.pptpVPNInstalled = true;
		}

		$scope.controls = [
			{
				title: "OpenVPN Dependencies",
				status: openVPNStatus,
				visible: true,
				throbber: false
			},
			{
				title: "PPTP Dependencies",
				status: pptpStatus,
				visible: true,
				throbber: false
			}
		];
		$scope.throbber = false;
	}

}]);