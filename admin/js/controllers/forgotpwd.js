'use strict';

/* Controllers */
  // signin controller
app.controller('PasswordResetFormController', ['$scope', '$http', '$state', 'Data', '$rootScope', function($scope, $http, $state, Data, $rootScope) {
    $scope.email = null;
    $scope.authError = null;
    $scope.successMsg = null;
    
    $scope.doReset = function(email) {
      
      $scope.authError = null;
      $scope.successMsg = null;
      
      // Try to login
      Data.get('adminResetPassword?email='+email).then(function (results) {
        console.log(results);
        if ( results.status != 'success' ) {
          $scope.authError = results.message;

        }else{
          $scope.successMsg = results.message;
          // $state.go('access.signin');
          $rootScope.toasterPop('success','Success!',results.message);
        }
      }, function(x) {
        $scope.authError = 'Server Error';
      });
    };
  }])
;