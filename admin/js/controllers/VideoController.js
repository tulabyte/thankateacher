'use strict';

app.controller('VideoController', ['$scope', 'Upload', '$rootScope', 'FTAFunctions', '$state', '$stateParams', '$http', 'Data', function($scope, Upload,$rootScope, FTAFunctions, $state, $stateParams, $http, Data) {
    
    //initialize stuff
    $scope.video = {};
        $scope.videos = {};

    // get admin information
/*    console.log($rootScope.tat_id);
    Data.get("getAdmin?id="+$rootScope.tat_id).then(function(results) {
      console.log(results);
      if(results.status == "success") {
        $scope.admin = results.admin;
      }
    });*/

//latest pin list
if ($state.current.name == 'app.edit-video') {
        Data.get('getVideoDetails?id='+$stateParams.id).then(function(results) {
              console.log(results);
              if(results.status == "success") {
                  $scope.video = results.video;
                }else {
                  $rootScope.toasterPop('error','Oops!',results.message);
                }
            });
      }


/* create a video*/
$scope.editVideo = function(video) {
            //if you are on pin page
          if ($state.current.name == 'app.create-video') {
              Data.post('createNewVideo', {
                      video: video
                  }).then(function (results) {
                    console.log(results);
                    if(results.status == "success") { 
                      $rootScope.toasterPop('success','Action Successful!',results.message);
                      $state.go('app.video-list');
                    } else {
                      //problemo. show error
                      $rootScope.toasterPop('error','Oops!',results.message);
                    }
                });
          } if ($state.current.name == 'app.edit-video') {
                Data.post('editVideo', {
                      video: video
                  }).then(function (results) {
                    console.log(results);
                    if(results.status == "success") { 
                      $rootScope.toasterPop('success','Action Successful!',results.message);
                      $state.go('app.video-list');
                    } else {
                      //problemo. show error
                      $rootScope.toasterPop('error','Oops!',results.message);
                    }
                });
          }
    };


      // get all Videos
     if ($state.current.name == 'app.video-list') {
            Data.get("getAllVideos").then(function(results) {
              console.log(results.videos);
              if(results.status == "success") {
                  $scope.videos = results.videos;
                }else {
                  $rootScope.toasterPop('error','Oops!',results.message);
                }
            });
          }



  }])
 ;