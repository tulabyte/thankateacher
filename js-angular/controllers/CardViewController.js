'use strict';

app

  .controller('CardViewController', ['$scope', '$stateParams', '$window', 'Data', '$rootScope', '$http', 'NgTableParams', '$modal', '$cookieStore',
    function(              $scope, $stateParams, $window, Data, $rootScope, $http, NgTableParams, $modal, $cookieStore) {

      console.log("CardViewController!");

      $scope.loading = true;
      $scope.message = {};
      $scope.message_error = undefined;
      $scope.cards_url = "admin/img/card-images/";

      $scope.printCard = function() {
        // window.print();
        console.log("creating pdf...");
        html2canvas(document.getElementById('card-view'), {
            onrendered: function (canvas) {
                var data = canvas.toDataURL();
                var docDefinition = {
                    // pageSize: 'A5',
                    pageOrientation: 'landscape',
                    info: {
                      title: 'Thank a Teacher Card',
                      author: 'ThankaTeacher',
                      subject: 'Appreciation',
                      keywords: 'thank a teacher, nigeria teaching awards, card',
                    },
                    content: [{
                        image: data,
                        width: 500,
                    }]
                };
                // pdfMake.createPdf(docDefinition).download("ThankaTeacher Card.pdf");
                pdfMake.createPdf(docDefinition).open();
            }
        });
      };

      

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