'use strict';

app.controller('ProfileController', ['$scope', '$rootScope', 'FTAFunctions', '$state', '$stateParams', 'Data', function($scope, $rootScope, FTAFunctions, $state, $stateParams, Data) {
    
    //initialize stuff
    $scope.profile = {};

    // get profile information
    console.log($rootScope.tat_id);
    Data.get("getAdmin?id="+$rootScope.tat_id).then(function(results) {
      console.log(results);
      if(results.status == "success") {
        $scope.profile = results.admin;
      }
    });

    $scope.updateProfile = function(admin) {
      Data.post('editAdmin', {
            admin: admin
        }).then(function (results) {
            console.log(results);
            if(results.status == "success") {
              //admin edited. Show message
              $rootScope.tat_name = admin.admin_name;

              $rootScope.toasterPop('success','Action Successful!','Profile Updated!');


            } else {
              //problemo. show error
              $rootScope.toasterPop('error','Oops!','Error updating profile');
            }
        });
    }

    // Edit Category
    $scope.changePassword = function(password) {
      if(password.new != password.new2) {
        $rootScope.toasterPop('error','Oops!',"The new passwords don't match. Please check and correct");
        return false;
      }

      Data.post('changeAdminPassword', {
          password: password
      }).then(function (results) {
          console.log(results);
          if(results.status == "success") {
            //category edited. Show message
            $rootScope.toasterPop('success','Successful!',results.message);
          } else {
            //problemo. show error
            $rootScope.toasterPop('error','Oops!',results.message);
          }
      });
    };

  }])
 ;