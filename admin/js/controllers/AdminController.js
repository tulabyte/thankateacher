'use strict';

app.controller('AdminController', ['$scope', '$rootScope', 'FTAFunctions', '$state', '$stateParams', 'Data', function($scope, $rootScope, FTAFunctions, $state, $stateParams, Data) {
    
    //initialize stuff
    $scope.admin = {};
    $scope.admins = [];
    $scope.logs = [];

    //console.log('ID: ' + $stateParams.id);

    //admin-list
    if($state.current.name == 'app.admin-list') {
      //get the selected admin
      FTAFunctions.getAdminList().then(function(results) {
        // console.log(results);
        if(results.status == "success") {
          $scope.admins = results.admins;
          // $rootScope.toasterPop('success','Action Successful!',results.message);
        } else {
          $rootScope.toasterPop('error','Oops!',results.message);
        }
      });
    }

    //admin-logs
    if($state.current.name == 'app.admin-logs') {
      //get the admin logs
      FTAFunctions.getAdminLogs().then(function(results) {
        // console.log(results);
        if(results.status == "success") {
          $scope.logs = results.logs;
          // $rootScope.toasterPop('success','Action Successful!',results.message);
        } else {
          $rootScope.toasterPop('error','Oops!',results.message);
        }
      });
    }

    //admin-edit
    if($state.current.name == 'app.admin-edit' && $stateParams.id != '') {
      $state.current.data.pageTitle = "Edit Admin"
      //get the selected admin
      FTAFunctions.getAdmin($stateParams.id).then(function(results) {
        // console.log(results);
        if(results.status == "success") {
          $scope.admin = results.admin;
          // $rootScope.toasterPop('success','Action Successful!',results.message);
        } else {
          $rootScope.toasterPop('error','Oops!',results.message);
        }
      });
    }

    // Edit Admin
    $scope.editAdmin = function(admin) {
      //check if we are on the edit page
      if($stateParams.id) {
        //edit the user
        console.log('editing user...');
        Data.post('editAdmin', {
            admin: admin
        }).then(function (results) {
            console.log(results);
            if(results.status == "success") {
              //admin edited. Show message
              $rootScope.toasterPop('success','Action Successful!',results.message);
            } else {
              //problemo. show error
              $rootScope.toasterPop('error','Oops!',results.message);
            }
        });
      } else {
        //create the user
        // console.log('creating new user...');
        Data.post('createAdmin', {
            admin: admin
        }).then(function (results) {
            console.log(results);
            if(results.status == "success") {
              //admin created. Show message and go to admin list
              $state.go('app.admin-list');
              $rootScope.toasterPop('success','Action Successful!',results.message);
            } else {
              //problemo. show error
              $rootScope.toasterPop('error','Oops!',results.message);
            }
        });
      }
    };

    // delete admin
    $scope.deleteAdmin = function(id) {
      if (confirm("Are you sure you want to delete this admin?")) {
        FTAFunctions.deleteAdmin(id).then(function(results) {
          if(results.status == 'success') {
            $state.go('app.admin-list', {reload: true});
            FTAFunctions.getAdminList().then(function(results) {
              if(results.status == "success") {
                $scope.admins = results.admins;
              }
            });
            $rootScope.toasterPop('success','Action Successful!',results.message);
          } else {
            $rootScope.toasterPop('error','Oops!',results.message);
          }
        });
      } else {
        return false;
      }
    };

    // disable admin
    $scope.disableAdmin = function(id) {
      if (confirm("Are you sure you want to disable this admin?")) {
        FTAFunctions.disableAdmin(id).then(function(results) {
          if(results.status == 'success') {
            $state.go('app.admin-list', {reload: true});
            FTAFunctions.getAdminList().then(function(results) {
              if(results.status == "success") {
                $scope.admins = results.admins;
              }
            });
            $rootScope.toasterPop('success','Action Successful!',results.message);
          } else {
            $rootScope.toasterPop('error','Oops!',results.message);
          }
        });
      } else {
        return false;
      }
    };

    // enable admin
    $scope.enableAdmin = function(id) {
      if (confirm("Are you sure you want to enable this admin?")) {
        FTAFunctions.enableAdmin(id).then(function(results) {
          if(results.status == 'success') {
            $state.go('app.admin-list', {reload: true});
            FTAFunctions.getAdminList().then(function(results) {
              if(results.status == "success") {
                $scope.admins = results.admins;
              }
            });
            $rootScope.toasterPop('success','Action Successful!',results.message);
          } else {
            $rootScope.toasterPop('error','Oops!',results.message);
          }
        });
      } else {
        return false;
      }
    };

    //$rootScope.toasterPop('success','Testing','Toaster.js Works in FORM.JS !!!');

  }])
 ;