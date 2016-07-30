angular.module('app.controllers')
.controller('ProjectFilesNewController',
    ['$scope', '$location', '$routeParams', 'Url', 'Upload', 'appConfig',
    function ($scope, $location, $routeParams, Url, Upload, appConfig) {
        $scope.save = function () {
            if ($scope.form.$valid) {
                var url = appConfig.baseUrl + 
                Url.getUrlFromUrlSymbol(appConfig.urls.projectFile, {
                    id: $routeParams.id,
                    idFile:''
                });

                Upload.upload({
                    url: url,
                    data: {
                        file: $scope.file.file, 
                        name: $scope.file.name,
                        description: $scope.file.description,
                        project_id: $routeParams.id
                    }
                }).then(function (resp) {
                    $location.path('/projects/' + $routeParams.id + '/files');
                });
            }
        }
    }]);