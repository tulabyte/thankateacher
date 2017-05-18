'use strict';

app

  .controller('CardViewController', ['$scope', '$stateParams', '$window', 'Data', '$rootScope', '$http', 'NgTableParams', '$modal', '$cookieStore',
    function(              $scope, $stateParams, $window, Data, $rootScope, $http, NgTableParams, $modal, $cookieStore) {

      $scope.loading = true;
      $scope.message = {};
      $scope.message_error = undefined;
      $scope.cards_url = "admin/img/card-images/";

      // check is stateParam is set
      if($stateParams.id) {
        // get the message and card details from API
        Data.get('getMessageWithCard?id='+$stateParams.id).then(function(results) {
          console.log("getMessageWithCard results", results);
          $scope.loading = false;
          if(results.status == "success") {
            $scope.message = results.msg;
          } else {
            $scope.message_error = results.message;
          }
        });
      } else {
        // redirect to home page
        window.href = "index.html";
      }

  }])
;