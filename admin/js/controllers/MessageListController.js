'use strict';

app.controller('MessageModalInstanceCtrl', ['$scope', '$modalInstance', 'message', 'Data', function($scope, $modalInstance, message, Data) {
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
;

app.controller('MessageListController', ['$scope', '$rootScope', '$modal', 'FTAFunctions', '$state', '$stateParams', '$http', 'Data', '$moment', function($scope, $rootScope, $modal, FTAFunctions, $state, $stateParams, $http, Data, $moment) {
    
    //initialize stuff
    $scope.pending_messages = [];
    $scope.featured_messages = [];
    $scope.approved_messages = [];

    $scope.openMessageModal = function (message = null) {
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

    // approve message
    $scope.approveMessage = function(message) {
      if(!confirm("Are you sure you want to APPROVE this message? THIS WILL AUTOMATICALLY MAKE THIS MESSAGE VISIBLE ON THE FRONTEND!!!")) {
        return false;
      }
      Data.get('approveMessage?id='+message.msg_id).then(function(results) {
        console.log(results);
        if(results.status == "success") {
          // remove item from the list
          for(var i=0; i<$scope.pending_messages.length; i++) {
            if($scope.pending_messages[i].msg_id == message.msg_id) {
              $scope.pending_messages.splice(i,1);
              break;
            }
          }
          $rootScope.toasterPop('success','Action Successful',results.message);
        } else {
          $rootScope.toasterPop('error','Oops!',results.message);
        }
      });
    };

//update message
    $scope.updateMessage = function(message) {
      var apiURL = 'updateMessage';
      console.log(apiURL);
        Data.post(apiURL, {
            message: message
        }).then(function(results) {
          console.log(results);
          if(results.status == 'success') {
            //load message list
           $rootScope.toasterPop('success','Action Successful!',results.message);
          } else {
            $rootScope.toasterPop('error','Oops!',results.message);
          }
        });
    };

//delete message
    $scope.deleteMessage = function(id, action) {
      var apiURL = 'deleteMessage?id='+id;
      console.log(apiURL);
      if (confirm("Are you sure you want to DELETE this message?")) {
        Data.get(apiURL).then(function(results) {
          console.log(results);
          if(results.status == 'success') {
            //load message list

      switch($state.current.name){
         case'app.message-pending-list' :
        Data.get('getPendingMessageList').then(function(results) {
        console.log(results);
        if(results.status == "success") {
          $scope.pending_messages = results.pending_messages;
        } else {
          $rootScope.toasterPop('error','Oops!',results.message);
        }
      });
        break;
        case'app.message-approved-list' :
      Data.get('getApprovedMessageList').then(function(results) {
        console.log(results);
        if(results.status == "success") {
          $scope.approved_messages = results.approved_messages;
        } else {
          $rootScope.toasterPop('error','Oops!',results.message);
        }
      });
      break;
            }

           $rootScope.toasterPop('success','Action Successful!',results.message);
          } else {
            $rootScope.toasterPop('error','Oops!',results.message);
          }
        });
      } else {
        return false;
      }
    };

    // disable message
    $scope.toggleMessage = function(id, action) {
      var val = (action == 'enable') ? 'on' : 'off';
      var apiURL = 'toggleItem?type='+'message'+'&id='+id+'&val='+val;
      console.log(apiURL);
      if (confirm("Are you sure you want to "+action+" this message?")) {
        Data.get(apiURL).then(function(results) {
          console.log(results);
          if(results.status == 'success') {
            // set value
            for(var i=0; i<$scope.approved_messages.length; i++) {
              if($scope.approved_messages[i].msg_id == id) {
                if(action == 'enable') {
                  delete $scope.approved_messages[i].msg_is_disabled;
                } else {
                  $scope.approved_messages[i].msg_is_disabled = 1;
                }
                break;
              }
            }
            $rootScope.toasterPop('success','Action Successful!',results.message);
          } else {
            $rootScope.toasterPop('error','Oops!',results.message);
          }
        });
      } else {
        return false;
      }
    };

    // mark message as featured
    $scope.markFeatured = function(message) {
      if(confirm("Are you sure you want to mark this message as FEATURED?")) {
        Data.get('markFeaturedMessage?id='+ message.msg_id).then(function(results){
          console.log(results);
          if(results.status == "success") {
            $rootScope.toasterPop('success','Action Successful!',results.message);
            $scope.featured_messages.push(message);
            Data.get('getApprovedMessageList').then(function(results) {
              if(results.status == "success") {
                $scope.approved_messages = results.approved_messages;
              }
            });
          }
        })
      }
    };

    // remove message from featured
    $scope.removeFeatured = function(message) {
      if(confirm("Are you sure you want to REMOVE this message from the FEATURED list?")) {
        Data.get('removeFeaturedMessage?id='+message.msg_id).then(function(results){
          console.log(results);
          if(results.status == "success") {
            $rootScope.toasterPop('success','Action Successful!',results.message);
            Data.get('getFeaturedMessages').then(function(results) {
              if(results.status == "success") {
                $scope.featured_messages = results.featured_messages;
              }
            });
          }
        })
      }
    };

    // edit message
    if($state.current.name == 'app.message-edit') {
      //message list 
      FTAFunctions.getMessageDetails($stateParams.id).then(function(results) {
        console.log(results);
        if(results.status == "success") {
          $scope.message = results.messages;
        } else {
          $rootScope.toasterPop('error','Oops!',results.message);
        }
      });
    }


    // retrieve message list
    if($state.current.name == 'app.message-pending-list') {
      //pending message=-list
      Data.get('getPendingMessageList').then(function(results) {
        console.log(results);
        if(results.status == "success") {
          $scope.pending_messages = results.pending_messages;
        } else {
          $rootScope.toasterPop('error','Oops!',results.message);
        }
      });
    }

    if($state.current.name == 'app.message-approved-list') {
      //approved message=-list
      Data.get('getApprovedMessageList').then(function(results) {
        console.log(results);
        if(results.status == "success") {
          $scope.approved_messages = results.approved_messages;
        } else {
          $rootScope.toasterPop('error','Oops!',results.message);
        }
      });
    }

    //approved message list
    Data.get('getFeaturedMessages').then(function(results) {
      console.log(results);
      if(results.status == "success") {
        $scope.featured_messages = results.featured_messages;
      }
    });

  }])
 ;