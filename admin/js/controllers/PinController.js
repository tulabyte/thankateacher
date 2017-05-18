'use strict';

app.controller('PinController', ['$scope', 'Upload', '$rootScope', 'FTAFunctions', '$state', '$stateParams', '$http', 'Data', function($scope, Upload,$rootScope, FTAFunctions, $state, $stateParams, $http, Data) {
    
    //initialize stuff
    $scope.pin = {};

    // get admin information
/*    console.log($rootScope.tat_id);
    Data.get("getAdmin?id="+$rootScope.tat_id).then(function(results) {
      console.log(results);
      if(results.status == "success") {
        $scope.admin = results.admin;
      }
    });*/

//latest pin list
if ($state.current.name == 'app.latest-pin-gen') {
        Data.get('getLatestGenPins?id='+$stateParams.id).then(function(results) {
              console.log(results);
              if(results.status == "success") {
                  $scope.pins = results.pins;
                }else {
                  $rootScope.toasterPop('error','Oops!',results.message);
                }
            });
      }


/* create a card*/
$scope.generatePin = function(pin) {
            //if you are on pin page
          if ($state.current.name == 'app.pin-gen') {
              Data.post('generateNewPin', {
                      pin: pin
                  }).then(function (results) {
                    console.log(results);
                    if(results.status == "success") { 
                      $rootScope.toasterPop('success','Action Successful!',results.message);
                      $scope.latest_pins = results.latest_pins;
                 //     $state.go('app.latest-pin-gen', {'id' : $scope.pin.pin_total });
                    } else {
                      //problemo. show error
                      $rootScope.toasterPop('error','Oops!',results.message);
                    }
                });
          }
    };

    // get unused pins
     if ($state.current.name == 'app.pin-unused-list') {
            Data.get("getAllPins").then(function(results) {
              if(results.status == "success") {
                  $scope.pins = results.unused_pin;
                  console.log(results.unused_pin);
                }else {
                  $rootScope.toasterPop('error','Oops!',results.message);
                }
            });
          }

      // get used pins
     if ($state.current.name == 'app.pin-used-list') {
            Data.get("getAllPins").then(function(results) {
              console.log(results.used_pin);
              if(results.status == "success") {
                  $scope.pins = results.used_pin;
                }else {
                  $rootScope.toasterPop('error','Oops!',results.message);
                }
            });
          }



        // get pin details
     if ($state.current.name == 'app.pin-details') {
            Data.get('getPinDetails?id='+$stateParams.id).then(function(results) {
              console.log(results);
              if(results.status == "success") {
                  $scope.pin = results.pin;}else {
                  $rootScope.toasterPop('error','Oops!',results.message);
                }
            });
          }

      //enable or disable pin
      $scope.togglePin = function(id, action) {
      var val = (action == 'enable') ? 'on' : 'off';
      var apiURL = 'toggleItem?type='+'pincode'+'&id='+id+'&val='+val;
      console.log(apiURL);
      if (confirm("Are you sure you want to "+action+" this pin?")) {
        Data.get(apiURL).then(function(results) {
          console.log(results);
          if(results.status == 'success') {
            // set value
            for(var i=0; i<$scope.pins.length; i++) {
              if($scope.pins[i].pin_id == id) {
                if(action == 'enable') {
                  delete $scope.pins[i].pin_is_disabled;
                } else {
                  $scope.pins[i].pin_is_disabled = 1;
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



  }])
 ;