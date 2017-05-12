'use strict';

/**
 * Config for the router
 */
angular.module('app')
  .run(
    [          '$rootScope', '$state', '$stateParams', 'bsLoadingOverlayService', 'Data', 'toaster', 
      function ($rootScope,   $state,   $stateParams, bsLoadingOverlayService, Data, toaster) {
          
          $rootScope.authenticated = false;

          //session access control
          $rootScope.$on("$stateChangeStart", function (event, next, current) {
              
              if (!$rootScope.authenticated && $state.current.name != 'access.signin' &&  $state.current.name != 'access.forgotpwd') {
                Data.get('session').then(function (results) {
                    if (results.tat_id) {
                        $rootScope.authenticated = true;
                        $rootScope.tat_id = results.tat_id;
                        $rootScope.tat_name = results.tat_name;
                        $rootScope.tat_email = results.tat_email;
                        $rootScope.tat_type = results.tat_type;
                        
                    } else {
                        //var nextUrl = next.$$route.originalPath;
                        //$location.path("/login");
                        $state.go('access.signin');
                    }
                });
              }
          });

          // pending payments
          

          //logout
          $rootScope.logout = function() {
            Data.get('logout').then(function (results) {
              if(results.status='success') {
                $state.go('access.signin');
                $rootScope.toasterPop('success','Success','Logout Successful!');
              };
            });
          };

          //toaster notifications/alerts
          $rootScope.toasterPop = function(type, title, text) {
              toaster.pop(type, title, text);    
          };

          // convert date string to JavaScript date
          $rootScope.makeDate = function(string) {
            return new Date(string);
          };          

          $rootScope.$state = $state;
          $rootScope.$stateParams = $stateParams;

          bsLoadingOverlayService.setGlobalConfig({
            templateUrl: 'tpl/loading-overlay-template.html'
          });        
      }
    ]
  )
  .config(
    [          '$stateProvider', '$urlRouterProvider',
      function ($stateProvider,   $urlRouterProvider) {
          
          $urlRouterProvider
              // .otherwise('/app/dashboard');
              .otherwise('/app/message-pending-list');
          $stateProvider
              .state('app', {
                  abstract: true,
                  url: '/app',
                  data: {pageTitle: 'Welcome'},
                  templateUrl: 'tpl/app.html'
              })
              .state('app.dashboard', {
                  url: '/dashboard',
                  data: {pageTitle: 'Dashboard'},
                  templateUrl: 'tpl/app_dashboard.html',
                  resolve: {
                    deps: ['$ocLazyLoad',
                      function( $ocLazyLoad ){
                        return $ocLazyLoad.load(['js/controllers/DashboardController.js']);
                    }]
                  }
              })

              // admin-edit
              .state('app.admin-edit', {
                  url: '/admin-edit/:id',
                  cache: false,
                  templateUrl: 'tpl/admin-edit.html',
                  data: {pageTitle: 'New Admin'},
                  resolve: {
                      deps: ['uiLoad',
                        function( uiLoad){
                          return uiLoad.load('js/controllers/AdminController.js');
                      }]
                  }
              })

              // profile-edit
              .state('app.profile-edit', {
                  url: '/profile-edit',
                  templateUrl: 'tpl/profile-edit.html',
                  data: {pageTitle: 'Edit Profile'},
                  resolve: {
                      deps: ['uiLoad',
                        function( uiLoad){
                          return uiLoad.load('js/controllers/ProfileController.js');
                      }]
                  }
              })

              // password-change
              .state('app.password-change', {
                  url: '/password-change',
                  templateUrl: 'tpl/password-change.html',
                  data: {pageTitle: 'Change Password'},
                  resolve: {
                      deps: ['uiLoad',
                        function( uiLoad){
                          return uiLoad.load('js/controllers/ProfileController.js');
                      }]
                  }
              })

              // user-edit
              .state('app.user-edit', {
                  url: '/user-edit/:id',
                  templateUrl: 'tpl/user-edit.html',
                  data: {pageTitle: 'New User'},
                  resolve: {
                      deps: ['uiLoad',
                        function( uiLoad){
                          return uiLoad.load('js/controllers/UserController.js');
                      }]
                  }
              })


              // create-card
              .state('app.create-card', {
                  url: '/create-card',
                  templateUrl: 'tpl/create-card.html',
                  data: {pageTitle: 'Create Card'},
                  resolve: {
                      deps: ['uiLoad',
                        function( uiLoad){
                          return uiLoad.load('js/controllers/CardController.js');
                      }]
                  }
              })

              // edit-card
              .state('app.edit-card', {
                  url: '/create-card/:id',
                  templateUrl: 'tpl/create-card.html',
                  data: {pageTitle: 'Edit Card'},
                  resolve: {
                      deps: ['uiLoad',
                        function( uiLoad){
                          return uiLoad.load('js/controllers/CardController.js');
                      }]
                  }
              })

              // create-list
              .state('app.card-list', {
                  url: '/card-list',
                  templateUrl: 'tpl/card-list.html',
                  data: {pageTitle: 'Card List'},
                  resolve: {
                      deps: ['uiLoad',
                        function( uiLoad){
                          return uiLoad.load('js/controllers/CardController.js');
                      }]
                  }
              })

                // card-details
              .state('app.card-details', {
                  url: '/card-details/:id',
                  templateUrl: 'tpl/card-details.html',
                  data: {pageTitle: 'Card Details'},
                  resolve: {
                      deps: ['uiLoad',
                        function( uiLoad){
                          return uiLoad.load('js/controllers/CardController.js');
                      }]
                  }
              })

              .state('app.latest-pin-gen', {
                  url: '/latest-pin/:id',
                  templateUrl: 'tpl/latest-pin-gen.html',
                  data: {pageTitle: 'Latest Generated Pins'},
                  resolve: {
                      deps: ['uiLoad',
                        function( uiLoad){
                          return uiLoad.load('js/controllers/PinController.js');
                      }]
                  }
              })

              .state('app.pin-used-list', {
                  url: '/used-pins',
                  templateUrl: 'tpl/pin-used-list.html',
                  data: {pageTitle: 'Used Pins'},
                  resolve: {
                      deps: ['uiLoad',
                        function( uiLoad){
                          return uiLoad.load('js/controllers/PinController.js');
                      }]
                  }
              })

              .state('app.pin-unused-list', {
                  url: '/unused-pins',
                  templateUrl: 'tpl/pin-unused-list.html',
                  data: {pageTitle: 'Unused Pins'},
                  resolve: {
                      deps: ['uiLoad',
                        function( uiLoad){
                          return uiLoad.load('js/controllers/PinController.js');
                      }]
                  }
              })

              .state('app.pin-gen', {
                  url: '/gen-pin',
                  templateUrl: 'tpl/pin-gen.html',
                  data: {pageTitle: 'Generate Pin'},
                  resolve: {
                      deps: ['uiLoad',
                        function( uiLoad){
                          return uiLoad.load('js/controllers/PinController.js');
                      }]
                  }
              })

            // pin-details
              .state('app.pin-details', {
                  url: '/pin-details/:id',
                  templateUrl: 'tpl/pin-details.html',
                  data: {pageTitle: 'Pin Details'},
                  resolve: {
                      deps: ['uiLoad',
                        function( uiLoad){
                          return uiLoad.load('js/controllers/PinController.js');
                      }]
                  }
              })

              // message-edit
              .state('app.message-edit', {
                  url: '/message-edit/:id',
                  templateUrl: 'tpl/message-edit.html',
                  data: {pageTitle: 'Edit Message'},
                  resolve: {
                      deps: ['uiLoad',
                        function( uiLoad){
                          return uiLoad.load('js/controllers/MessageListController.js');
                      }]
                  }
              })

              // edit category
              .state('app.cat-edit', {
                  url: '/cat-edit/:id',
                  templateUrl: 'tpl/cat-edit.html',
                  data: {pageTitle: 'New Category'},
                  resolve: {
                      deps: ['uiLoad',
                        function( uiLoad){
                          return uiLoad.load('js/controllers/CategoryController.js');
                      }]
                  }
              })

              // edit course
              .state('app.course-edit', {
                  url: '/course-edit/:id',
                  templateUrl: 'tpl/course-edit.html',
                  data: {pageTitle: 'New Course'},
                  resolve: {
                      deps: ['$ocLazyLoad',
                        function( $ocLazyLoad){
                          return $ocLazyLoad.load([
                              'angularFileUpload',
                              'textAngular'
                            ]).then(
                              function(){
                                 return $ocLazyLoad.load('js/controllers/CourseController.js');
                              }
                          );
                      }]
                  }
              })

              // course details
              .state('app.course-details', {
                  url: '/course-details/:id',
                  templateUrl: 'tpl/course-details.html',
                  data: {pageTitle: 'Course Details'},
                  resolve: {
                      deps: ['uiLoad',
                        function( uiLoad){
                          return uiLoad.load('js/controllers/CourseController.js');
                      }]
                  }
              })

              // admin list
              .state('app.admin-list', {
                  url: '/admin-list',
                  templateUrl: 'tpl/admin-list.html',
                  data: {pageTitle: 'Admin List'},
                  resolve: {
                      deps: ['uiLoad',
                        function( uiLoad){
                          return uiLoad.load('js/controllers/AdminController.js');
                      }]
                  }
              })

              // user list
              .state('app.user-list', {
                  url: '/user-list',
                  templateUrl: 'tpl/user-list.html',
                  data: {pageTitle: 'User List'},
                  resolve: {
                      deps: ['uiLoad',
                        function( uiLoad){
                          return uiLoad.load('js/controllers/UserController.js');
                      }]
                  }
              })

              // admin logs
              .state('app.admin-logs', {
                  url: '/admin-logs',
                  templateUrl: 'tpl/admin-logs.html',
                  data: {pageTitle: 'Admin Logs'},
                  resolve: {
                      deps: ['uiLoad',
                        function( uiLoad){
                          return uiLoad.load('js/controllers/AdminController.js');
                      }]
                  }
              })

              // category list
              .state('app.cat-list', {
                  url: '/cat-list',
                  templateUrl: 'tpl/cat-list.html',
                  data: {pageTitle: 'Course Categories'},
                  resolve: {
                      deps: ['uiLoad',
                        function( uiLoad){
                          return uiLoad.load('js/controllers/CategoryController.js');
                      }]
                  }
              })

              // course list
              .state('app.course-list', {
                  url: '/course-list',
                  templateUrl: 'tpl/course-list.html',
                  data: {pageTitle: 'Course List'},
                  resolve: {
                      deps: ['uiLoad',
                        function( uiLoad){
                          return uiLoad.load('js/controllers/CourseController.js');
                      }]
                  }
              })

              // subscription list
              .state('app.sub-list', {
                  url: '/sub-list/:type',
                  templateUrl: 'tpl/sub-list.html',
                  data: {pageTitle: 'Subscription List'},
                  resolve: {
                      deps: ['uiLoad',
                        function( uiLoad){
                          return uiLoad.load('js/controllers/SubscriptionController.js');
                      }]
                  }
              })

              // payment list
              .state('app.payment-list', {
                  url: '/payment-list/:type',
                  templateUrl: 'tpl/payment-list.html',
                  data: {pageTitle: 'Payment List'},
                  resolve: {
                      deps: ['uiLoad',
                        function( uiLoad){
                          return uiLoad.load('js/controllers/PaymentController.js');
                      }]
                  }
              })

              // bank-waiting list
              .state('app.bank-waiting-list', {
                  url: '/bank-waiting-list',
                  templateUrl: 'tpl/bank-waiting-list.html',
                  data: {pageTitle: 'Bank Waiting List'},
                  resolve: {
                      deps: ['uiLoad',
                        function( uiLoad){
                          return uiLoad.load('js/controllers/PaymentController.js');
                      }]
                  }
              })

              // pending messages list
              .state('app.message-pending-list', {
                  url: '/message-pending-list',
                  templateUrl: 'tpl/message-pending-list.html',
                  data: {pageTitle: 'Pending Message List'},
                  resolve: {
                      deps: ['uiLoad',
                        function( uiLoad){
                          return uiLoad.load('js/controllers/MessageListController.js');
                      }]
                  }
              })

              // approved messages list
              .state('app.message-approved-list', {
                  url: '/message-approved-list',
                  templateUrl: 'tpl/message-approved-list.html',
                  data: {pageTitle: 'Approved Message List'},
                  resolve: {
                      deps: ['uiLoad',
                        function( uiLoad){
                          return uiLoad.load('js/controllers/MessageListController.js');
                      }]
                  }
              })

              // featured messages list
              .state('app.message-featured-list', {
                  url: '/message-featured-list',
                  templateUrl: 'tpl/message-featured-list.html',
                  data: {pageTitle: 'Featured Messages'},
                  resolve: {
                      deps: ['uiLoad',
                        function( uiLoad){
                          return uiLoad.load('js/controllers/MessageListController.js');
                      }]
                  }
              })
              
              
              // user auth
              
              .state('access', {
                  url: '/access',
                  template: '<div ui-view class="fade-in-right-big smooth"></div>'
              })
              .state('access.signin', {
                  url: '/signin',
                  templateUrl: 'tpl/page_signin.html',
                  data: {pageTitle: 'Sign In'},
                  resolve: {
                      deps: ['uiLoad',
                        function( uiLoad ){
                          return uiLoad.load( ['js/controllers/signin.js'] );
                      }]
                  }
              })
              .state('access.signup', {
                  url: '/signup',
                  templateUrl: 'tpl/page_signup.html',
                  resolve: {
                      deps: ['uiLoad',
                        function( uiLoad ){
                          return uiLoad.load( ['js/controllers/signup.js'] );
                      }]
                  }
              })
              .state('access.forgotpwd', {
                  url: '/forgotpwd',
                  templateUrl: 'tpl/page_forgotpwd.html',
                  data: {pageTitle: 'Recover Password'},
                  resolve: {
                      deps: ['uiLoad',
                        function( uiLoad ){
                          return uiLoad.load( ['js/controllers/forgotpwd.js'] );
                      }]
                  }
              })
              
      }
    ]
  );