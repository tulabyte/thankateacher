'use strict';

/* Controllers */

angular.module('app')
  .controller('AppCtrl', ['$scope', '$window', 'Data', '$rootScope', 
    function(              $scope,  $window, Data, $rootScope) {
      

      // config
      $scope.app = {
        name: 'Thank A Teacher',
        version: '0.1.1'
      }

  }])
;