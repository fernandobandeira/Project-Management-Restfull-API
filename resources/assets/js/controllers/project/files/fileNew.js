angular.module('app.controllers')
    .controller('ProjectNotesNewController',
        ['$scope', 'ProjectNote', '$location', '$routeParams',
            function ($scope, ProjectNote, $location, $routeParams) {
                $scope.note = new ProjectNote();
                $scope.note.project_id = $routeParams.id;

                $scope.save = function () {
                    if ($scope.form.$valid) {
                        $scope.note.$save({id: $routeParams.id}).then(function () {
                            $location.path('/projects/' + $routeParams.id + '/notes');
                        });
                    }
                }
            }]);