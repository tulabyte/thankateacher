'use strict';

app
  .controller('ArchiveController', ['$scope', '$window', 'Data', '$rootScope', '$http', 
    function(              $scope,  $window, Data, $rootScope, $http) {
      
      // $scope.bgshow = "inherit";
      
      // Start New Message
      $scope.newMessage = function () {
      	$scope.message = {};
      	$scope.show_success = false;
      	$scope.message_error = '';
      	$scope.currentpage = 1;
      	$scope.show_form = true;
      };

      $scope.featured_messages = [];

      // get list of states
      $http.get('http://localhost/thankateacher/api/states.json').then(function(results) {
      	console.log(results);
      	$scope.states = results.data;
      })

      // initialize
      $scope.newMessage();

      // get 3 of d featured messages
      Data.get('getFeaturedMessages').then(function(results) {
      	if(results.status == "success") {
      		$scope.featured_messages = results.featured_messages;
      	}
      });
      
      // Next
      $scope.next = function() {
      	console.log($scope.message);
      	// validate
      	if($scope.currentpage == 1 && (!$scope.message.teacher_name || !$scope.message.sender_name)) {
      		alert("Your name and your teacher's names are required!")
      		return false;
      	}
      	if($scope.currentpage == 2 && (!$scope.message.state || !$scope.message.city || !$scope.message.school)) {
      		alert("State, City and School are required!")
      		return false;
      	}
      	if($scope.currentpage < 3) {
      		$scope.currentpage += 1;
      	}
      };

      // Previous
      $scope.previous = function() {
      	if($scope.currentpage > 1) {
      		$scope.currentpage -= 1;
      	}
      };

      // Submit Message
      $scope.submitMessage = function(message) {
      	Data.post('createMessage', { message: message }).then(function(results) {
      		console.log(results);
      		if(results.status == "success") {
      			// clear message object 
      			$scope.message = {};
      			// show success message and hide form
      			$scope.show_success = true;
      			$scope.show_form = false;
      		} else {
      			// show error message
      			$scope.message_error = results.message;
      		}
      	});
      }

  }])
;