app.factory("Data", ['$http', '$rootScope', 
    function ($http, $rootScope) { // This service connects to our REST API

        // var serviceBase = 'api/default/index.php/';
         var serviceBase = 'http://localhost/thankateacher/api/default/index.php/';
        // var serviceBase = 'http://thankateacher.nigerianteachingawards.org/api/default/index.php/';

        var obj = {};

        obj.get = function (q) {
            //$rootScope.showPreloader();
            return $http.get(serviceBase + q).then(function (results) {
                return results.data;
            },
            function(error) {
                console.log(error);
            });
        };
        obj.post = function (q, object) {
            return $http.post(serviceBase + q, object).then(function (results) {
                return results.data;
            },
            function(error) {
                console.log(error);
            });
        };
        obj.put = function (q, object) {
            return $http.put(serviceBase + q, object).then(function (results) {
                return results.data;
            },
            function(error) {
                console.log(error);
            });
        };
        obj.delete = function (q) {
            return $http.delete(serviceBase + q).then(function (results) {
                return results.data;
            },
            function(error) {
                console.log(error);
            });
        };

        return obj;
}]);