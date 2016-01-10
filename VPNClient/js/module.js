registerController("VPNClientController", ['$api', '$scope', function($api, $scope) {

	$scope.messages = [];

	$scope.controls = [
		{
			title: "OpenVPN",
			status: "Install",
			visible: true,
			throbber: false
		},
		{
			title: "PPTP VPN",
			status: "Install",
			visible: true,
			throbber: false
		}
	];

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

}]);