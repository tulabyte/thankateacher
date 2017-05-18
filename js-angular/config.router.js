'use strict';

/**
 * Config for the router
 */
angular.module('app')
  .run(
    [          '$rootScope', '$state', '$stateParams', 'Data', 
      function ($rootScope,   $state,   $stateParams, Data) {
                    
          console.log('router!');

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

              .state('card', {
                  url: '/card',
                  data: {pageTitle: 'Send a Card'},
                  templateUrl: 'tpl/card.html',
                  cache: false,
                  controller: 'CardController'
              })

              .state('view', {
                  url: '/view/:id',
                  data: {pageTitle: 'My Card'},
                  templateUrl: 'tpl/view.html',
                  cache: false,
                  controller: 'CardViewController'
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