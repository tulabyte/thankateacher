'use strict';

app

.controller('MessageModalInstanceCtrl', ['$scope', '$modalInstance', 'message', 'Data', function($scope, $modalInstance, message, Data) {
  if(message) {
    $scope.message = message;
    console.log(message);
  } else {
    $scope.message = {};
  }
  $scope.error = undefined;

  $scope.close = function () {
    $modalInstance.dismiss('cancel');
  };
}])

  .controller('HomeController', ['$scope', '$stateParams', '$window', 'Data', '$rootScope', '$http', 'NgTableParams', '$modal', '$cookieStore',
    function(              $scope, $stateParams, $window, Data, $rootScope, $http, NgTableParams, $modal, $cookieStore) {
      
      console.log('home controller!');
      
      // modal for message
      $scope.openMessageModal = function (message = null) {
            console.log('modal called!')
            var modalInstance = $modal.open({
              templateUrl: 'MessageModalContent.html',
              controller: 'MessageModalInstanceCtrl',
              resolve: {
                message: function (){
                  return message;
                }
              }
            });

            modalInstance.result.then(function (status) {
              if(status == 'OK') {
                console.log('Modal OK');
                // $scope.loadModules($scope.course.course_id);
              }
            }, function () {
              console.log('Modal dismissed at: ' + new Date());
            });
      };

      // $scope.bgshow = "inherit";

      // Start New Message
      $scope.newMessage = function () {
      	$cookieStore.remove('message');
        $scope.message = {};
      	$scope.show_success = false;
      	$scope.message_error = '';
      	$scope.currentpage = 1;
      	$scope.show_form = true;
        $scope.submit_clicked = false;
      };

      if($stateParams.success) {
        $scope.show_success = true;
        $scope.show_form = false;
      }

      $scope.featured_messages = [];

      $scope.messages = [];

      // get list of states
      $http.get('http://thankateacher.nigerianteachingawards.org/api/states.json').then(function(results) {
      	console.log(results);
      	$scope.states = results.data;

            if($cookieStore.get('message')) {
              $scope.message = $cookieStore.get('message');
              $scope.show_success = false;
              $scope.message_error = '';
              $scope.currentpage = 1;
              $scope.show_form = true;
              $scope.submit_clicked = false;
            } else {
              // initialize
              $scope.newMessage();
            }

            // get list of messages for archive
            Data.get('getApprovedMessageList').then(function(results) {
                  console.log(results);
                  if(results.status == "success") {
                        $scope.messages = results.approved_messages;
                        $scope.tableParams = new NgTableParams({}, { dataset: $scope.messages});
                  }
            })
      })

      

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
      	// validate
        if(!message || !message.message) {
          alert("Please enter your message!")
          return false;
        }

        $scope.submit_clicked = true;
        // console.log('submit clicked!');
        // return false;

        if(confirm("Would you like to Send a Card to your Teacher?")) {
          if(message.teacher_email !== null && message.teacher_email !== undefined) {
            // store the message in cookies
            delete message.pin;
            $cookieStore.remove('message');
            $cookieStore.put('message', message);
            // move to the card selection view
            console.log('Going to Card page...');
            $window.location.href = "http://thankateacher.nigerianteachingawards.org/card.html#/card";
          } else {
            //let user know teacher email is required and switch to that page
            alert("To send a card, you need to provide your teacher's email!");
            $scope.submit_clicked = false;
            $scope.currentpage = 1;
            return false;
          }
        } else {
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
                $scope.submit_clicked = false;
            }
          });
        }
        
      }

  }])
;