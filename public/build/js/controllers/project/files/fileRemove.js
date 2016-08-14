angular.module('app.controllers')
    .controller('ProjectFilesRemoveController',
        ['$scope', 'ProjectFile', '$routeParams', '$location',
            function ($scope, ProjectFile, $routeParams, $location) {
                $scope.file = new ProjectFile.get({id: $routeParams.id, idFile: $routeParams.idFile});

                $scope.remove = function () {
                    $scope.file.$delete({id: $routeParams.id,idFile: $scope.file.id}).then(function () {
                        $location.path('/projects/' + $routeParams.id + '/files');
                    });
                }
            }]);