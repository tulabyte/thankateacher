'use strict';

/* Controllers */
  // signin controller
app.controller('SigninFormController', ['$scope', '$http', '$state', 'Data', '$rootScope', function($scope, $http, $state, Data, $rootScope) {
    $scope.admin = {};
    $scope.authError = null;
    $scope.successMsg = null;
    
    $scope.login = function(admin) {
      
      $scope.authError = null;
      $scope.successMsg = null;
      console.log(admin);
      // Try to login
      Data.post('adminLogin', {
        admin: admin
      }).then(function(response) {
        console.log(response);
        if ( response.status != 'success' || !response.tat_id ) {
          $scope.authError = response.message;
          $rootScope.toasterPop('error','Oops!',response.message);
        }else{
          $rootScope.toasterPop('success','Success!','Logged in successful!');
          $scope.successMsg = response.message;
          $state.go('app.message-pending-list');
        }
      }, function(x) {
        console.log(x);
        $scope.authError = 'Server Error';
        $rootScope.toasterPop('error','Oops!','Server Error');
      });
    };
  }])
;