angular.module('app.controllers')
    .controller('ProjectNotesListController', ['$scope', 'ProjectNote', '$routeParams',
        function ($scope, ProjectNote, $routeParams) {
        $scope.notes = ProjectNote.query({id: $routeParams.id});
        $scope.project_id = $routeParams.id;
    }]);