angular.module('app.controllers')
    .controller('ProjectNotesShowController',
        ['$scope', 'ProjectNote', '$routeParams',
            function ($scope, ProjectNote, $routeParams) {
                $scope.note = new ProjectNote.get({id: $routeParams.id, idNote: $routeParams.idNote});
                $scope.project_id = $routeParams.id;
            }]);