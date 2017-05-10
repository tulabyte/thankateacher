'use strict';

app.controller('CardController', ['$scope', 'Upload', '$rootScope', 'FTAFunctions', '$state', '$stateParams', '$http', 'Data', function($scope, Upload,$rootScope, FTAFunctions, $state, $stateParams, $http, Data) {
    
var uploadUrl = '../api/default/uploadCard.php';

    //initialize stuff
    $scope.card = {};
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
      if ($scope.form.file.$valid && $scope.file) {
        $scope.upload($scope.file);
        $scope.card.card_image = $scope.file.name;
        console.log(card);
        Data.post('createNewCard', {
              card: card
          }).then(function (results) {
            console.log(results);
            if(results.status == "success") {
              $rootScope.toasterPop('success','Action Successful!','Card Created!');
              $state.go('app.card-list');
            } else {
              //problemo. show error
              $rootScope.toasterPop('error','Oops!',results.message);
            }
        });
      }
    };

/*code to upload the file*/
     $scope.upload = function (file) {
        Upload.upload({
            url: uploadUrl,
            data: {file: file, 'username': $scope.username}
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


        // get all cards
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



  }])
 ;