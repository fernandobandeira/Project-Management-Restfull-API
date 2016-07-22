angular.module('app.controllers')
    .controller('ProjectNotesRemoveController',
        ['$scope', 'ProjectNote', '$routeParams', '$location',
            function ($scope, ProjectNote, $routeParams, $location) {
                $scope.note = new ProjectNote.get({id: $routeParams.id, idNote: $routeParams.idNote});

                $scope.remove = function () {
                    $scope.note.$delete({id: $routeParams.id,idNote: $scope.note.id}).then(function () {
                        $location.path('/projects/' + $routeParams.id + '/notes');
                    });
                }
            }]);