angular.module('app.controllers')
    .controller('ProjectFilesEditController',
        ['$scope', 'ProjectFile', '$routeParams', '$location',
            function ($scope, ProjectFile, $routeParams, $location) {
                $scope.file = new ProjectFile.get({id: $routeParams.id, idFile: $routeParams.idFile});
                
                $scope.save = function () {
                    if ($scope.form.$valid) {
                        ProjectFile.update(
                            {id: $routeParams.id,idFile: $scope.file.id},
                            $scope.file, function () {
                            $location.path('/projects/' + $routeParams.id + '/files');
                        });
                    }
                }
            }]);