'use strict';

app.controller('CardController', ['$scope', 'Upload', '$rootScope', 'FTAFunctions', '$state', '$stateParams', '$http', 'Data', function($scope, Upload,$rootScope, FTAFunctions, $state, $stateParams, $http, Data) {
    
var uploadUrl = '../api/default/uploadCard.php';

    //initialize stuff
    $scope.card = {};
    $scope.card.card_themeColor = 'black';
    $scope.demo_teacher_name = 'Mr John Doe';
    $scope.demo_message_body = 'Interactively syndicate cross functional paradigms rather than seamless infomediaries. Appropriately foster error-free e-markets vis-a-vis multimedia based vortals. Efficiently underwhelm end-to-end.';
    $scope.demo_sender_name = 'Jane Doe';
    $scope.demo_date = new Date();

    // get admin information
/*    console.log($rootScope.tat_id);
    Data.get("getAdmin?id="+$rootScope.tat_id).then(function(results) {
      console.log(results);
      if(results.status == "success") {
        $scope.card = results.admin;
      }
    });*/

/* create a card*/
$scope.createCard = function(card) {
            //if you are on edit page
          if ($state.current.name == 'app.edit-card') {
                // updating card without image
                if (card.card_edit) {
                  $scope.card.card_image = $scope.file.name;}
              Data.post('updateCard', {
                      card: card
                  }).then(function (results) {
                    console.log(results);
                    if(results.status == "success") {
                      if (card.card_edit) {
                        $scope.upload($scope.file, results.card_name);
                      }
                      $rootScope.toasterPop('success','Action Successful!','Card Created!');
                     // $state.go('app.card-list');
                    } else {
                      //problemo. show error
                      $rootScope.toasterPop('error','Oops!',results.message);
                    }
                });
          }
    if ($scope.form.file.$valid && $scope.file) {
      if ($state.current.name == 'app.create-card') {
              $scope.card.card_image = $scope.file.name;
              console.log(card);
              Data.post('createNewCard', {
                    card: card
                }).then(function (results) {
                  console.log(results);
                  if(results.status == "success") {
                    $scope.upload($scope.file, results.card_name);
                    $rootScope.toasterPop('success','Action Successful!','Card Created!');
                    $state.go('app.card-list');
                  } else {
                    //problemo. show error
                    $rootScope.toasterPop('error','Oops!',results.message);
                  }
              });
            }
      }
    };

/*code to upload the file*/
     $scope.upload = function (file, image_name) {
        Upload.upload({
            url: uploadUrl,
            data: {file: file, 'image': image_name}
        }).then(function (resp) {
//            console.log('Success ' + resp.config.data.file.name + 'uploaded. Response: ' + resp.data);
        }, function (resp) {
  //          console.log('Error status: ' + resp.status);
        }, function (evt) {
            var progressPercentage = parseInt(100.0 * evt.loaded / evt.total);
    //        console.log('progress: ' + progressPercentage + '% ' + evt.config.data.file.name);
        });
    };

    // get all cards
     if ($state.current.name == 'app.card-list') {
            Data.get("getAllCards").then(function(results) {
              console.log(results);
              if(results.status == "success") {
                  $scope.cards = results.cards;
                }else {
                  $rootScope.toasterPop('error','Oops!',results.message);
                }
            });
          }


        // get card details
     if ($state.current.name == 'app.card-details') {
            Data.get('getCardDetails?id='+$stateParams.id).then(function(results) {
              console.log(results);
              if(results.status == "success") {
                  $scope.card = results.card;
                  $scope.messages = results.messages;
                }else {
                  $rootScope.toasterPop('error','Oops!',results.message);
                }
            });
          }

      //enable or disable card
      $scope.toggleCard = function(id, action) {
      var val = (action == 'enable') ? 'on' : 'off';
      var apiURL = 'toggleItem?type='+'card_design'+'&id='+id+'&val='+val;
      console.log(apiURL);
      if (confirm("Are you sure you want to "+action+" this card?")) {
        Data.get(apiURL).then(function(results) {
          console.log(results);
          if(results.status == 'success') {
            // set value
            for(var i=0; i<$scope.cards.length; i++) {
              if($scope.cards[i].card_id == id) {
                if(action == 'enable') {
                  delete $scope.cards[i].card_is_disabled;
                } else {
                  $scope.cards[i].card_is_disabled = 1;
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


    // get card for edit
     if ($state.current.name == 'app.edit-card') {
            Data.get('getCardDetails?id='+$stateParams.id).then(function(results) {
              console.log(results);
              if(results.status == "success") {
                  $scope.card = results.card;
                  $scope.card.card_value = parseInt(results.card.card_value);
                }else {
                  $rootScope.toasterPop('error','Oops!',results.message);
                }
            });
          }




  }])
 ;