'use strict';

/**
 * Config for the router
 */
angular.module('app')
  .run(
    [          '$rootScope', '$state', '$stateParams', 'Data', 
      function ($rootScope,   $state,   $stateParams, Data) {
                    

          // convert date string to JavaScript date
          $rootScope.makeDate = function(string) {
            return new Date(string);
          };          

          $rootScope.$state = $state;
          $rootScope.$stateParams = $stateParams;      
      }
    ]
  )
  .config(
    [          '$stateProvider', '$urlRouterProvider',
      function ($stateProvider,   $urlRouterProvider) {
          
          $urlRouterProvider
              .otherwise('/home');
          $stateProvider
              .state('home', {
                  url: '/home',
                  data: {pageTitle: 'Thank a Teacher'},
                  templateUrl: 'tpl/home.html',
                  cache: false,
                  controller: 'HomeController'
              })

              .state('archive', {
                  url: '/archive',
                  data: {pageTitle: 'Message Archive'},
                  templateUrl: 'tpl/archive.html',
                  cache: false,
                  controller: 'ArchiveController'
              })
              
      }
    ]
  );