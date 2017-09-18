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

  .controller('CardController', ['$scope', '$window', 'Data', '$rootScope', '$http', 'NgTableParams', '$modal', '$cookieStore',
    function(              $scope,  $window, Data, $rootScope, $http, NgTableParams, $modal, $cookieStore) {
      
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

      $scope.loading = true;
      $scope.card_pin_error = false;

      // put the selected card in cookie
      $scope.storeSelectedCard = function(card) {
        $cookieStore.remove('card');
        $cookieStore.put('card', card);
        $scope.card_pin_error = false;
        delete $scope.message.pin;
      }

      $scope.selectCard = function(card) {
        $scope.selected_card = card;
        $scope.storeSelectedCard(card);
      }

      // store message in cookie
      /*$scope.storeMessage = function(message) {
        $cookieStore.put('message', message);
      }*/

      // format the card title and value for display
      $scope.getCardDisplay = function(card) {
        // console.log('getCardDisplay:',card);
        var price = (card.card_value > 0.00) ? ('₦' + card.card_value) : 'FREE';
        return card.card_title + ' (' + price + ')';
      }

      // check the pin
      $scope.checkPin = function(card_pin) {
        if(!card_pin || card_pin.length < 12) {
          $scope.card_pin_error = true;
        } else {
          Data.get("checkPin?pin="+card_pin).then(function(results) {
            console.log('checkPin results',results);
            if(results !== undefined && results !== null && results.status == "success") {
              // is the pin of correct value?
              if(parseFloat(results.pincode.pin_value) == parseFloat($scope.selected_card.card_value)) {
                $scope.card_pin_error = false;
                $scope.message.pin = card_pin; 
              } else {
                alert("The value of the pin you  supplied ("+results.pincode.pin_value+") is incorrect!");
              }
              
            } else {
              if(results !== undefined && results !== null) alert(results.message);
              $scope.card_pin_error = true;
            }
          });
        }
      }

      // remove the pin
      $scope.removePin = function() {
        if(confirm("Are you sure you want to remove this pin?")) {
          delete $scope.card_pin;
          delete $scope.message.pin;
        }
      }

      // check if message is in cookie
      if(!$cookieStore.get('message')) {
        alert('No message in cookie!');
        // go back to home page
        window.href = "index.html";
      } else {
        // get message from cookie
        $scope.message = $cookieStore.get('message');
        console.log('Message in Cookie:', $scope.message);
        $scope.cards = []; //initialize cards
        $scope.cards_url = "admin/img/card-images/";

        // get card designs
        Data.get("getActiveCardDesigns").then(function(results) {
          if(results.status == "success") {
            console.log('get cards results:',results);
            $scope.cards = results.cards; //put result in cards
            if(!$cookieStore.get('card')) {
              $scope.selected_card = $scope.cards[0]; //select first card as default  
            } else {
              $scope.selected_card = $cookieStore.get('card'); //pick the stored choice from cookie
            }
            $scope.loading = false;
          }
        })
      }

      // Submit Message
      $scope.submitMessage = function(message, card) {
      	
        $scope.submit_clicked = true;
        // console.log('submit clicked!');
        // return false;

        if(confirm("Proceed to send this card (you will NOT be able to edit afterwards)?")) {
          message.msg_card_id = card.card_id;
          Data.post('createMessage', { message: message }).then(function(results) {
          console.log(results);
            if(results.status == "success") {
              // clear cookies
              $cookieStore.remove('message');
              $cookieStore.remove('card');
              // alert success
              alert("Thank you for thanking your teacher. Your message has been received and will be approved for display within the next 24 hours.");
              // go home with success report
              $window.location.href = "http://thankateacher.nigerianteachingawards.org/index.html#/home/success";
            } else {
              // show error message
                $scope.message_error = results.message;
                $scope.submit_clicked = false;
            }
          });
        } else {
          $scope.submit_clicked = false;
        }
      }

  }])
;