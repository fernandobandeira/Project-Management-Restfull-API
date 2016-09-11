var app = angular.module('app', ['ngRoute', 'angular-oauth2', 'app.controllers',
    'app.services', 'app.filters', 'app.directives', 'ui.bootstrap.typeahead',
    'ui.bootstrap.datepickerPopup', 'ui.bootstrap.tpls', 'ui.bootstrap.modal',
    'ngFileUpload', 'http-auth-interceptor', 'angularUtils.directives.dirPagination', 'ui.bootstrap.dropdown'
]);

angular.module('app.controllers', ['ngMessages', 'angular-oauth2']);
angular.module('app.filters', []);
angular.module('app.directives', []);
angular.module('app.services', ['ngResource']);

app.provider('appConfig', ['$httpParamSerializerProvider', function($httpParamSerializerProvider) {
    var config = {
        baseUrl: window.location.origin,
        project: {
            status: [{
                value: 1,
                label: 'Não Iniciado'
            }, {
                value: 2,
                label: 'Iniciado'
            }, {
                value: 3,
                label: 'Concluído'
            }]
        },
        projectTask: {
            status: [{
                value: 1,
                label: 'Incompleta'
            }, {
                value: 2,
                label: 'Completa'
            }]
        },
        urls: {
            projectFile: '/project/{{id}}/file/{{idFile}}'
        },
        utils: {
            transformRequest: function(data) {
                if (angular.isObject(data)) {
                    return $httpParamSerializerProvider.$get()(data);
                }
                return data;
            },
            transformResponse: function(data, headers) {
                var headersGetter = headers();
                if (headersGetter['content-type'] == 'application/json' ||
                    headersGetter['content-type'] == 'text/json') {
                    var dataJson = JSON.parse(data);
                    if (dataJson.hasOwnProperty('data') && Object.keys(dataJson).length == 1) {
                        dataJson = dataJson.data;
                    }
                    return dataJson;
                }
                return data;
            }
        }
    }

    return {
        config: config,
        $get: function() {
            return config;
        }
    }
}]);

app.config([
    '$routeProvider', '$httpProvider',
    'OAuthProvider', 'OAuthTokenProvider', 'appConfigProvider',
    function($routeProvider, $httpProvider, OAuthProvider, OAuthTokenProvider, appConfigProvider) {

        $httpProvider.defaults.headers.post['Content-Type'] = 'application/x-www-form-urlencoded;charset=utf-8';
        $httpProvider.defaults.headers.put['Content-Type'] = 'application/x-www-form-urlencoded;charset=utf-8';

        $httpProvider.defaults.transformRequest = appConfigProvider.config.utils.transformRequest;
        $httpProvider.defaults.transformResponse = appConfigProvider.config.utils.transformResponse;
        $httpProvider.interceptors.splice(0, 1);
        $httpProvider.interceptors.splice(0, 1);
        $httpProvider.interceptors.push('oauthFixInterceptor');

        $routeProvider
            .when('/login', {
                templateUrl: 'build/views/login.html',
                controller: 'LoginController'
            })
            .when('/logout', {
                resolve: {
                    logout: ['$location', 'OAuthToken', function($location, OAuthToken) {
                        OAuthToken.removeToken();
                        $location.path('login');
                    }]
                }
            })
            .when('/home', {
                templateUrl: 'build/views/home.html',
                controller: 'HomeController'
            })
            .when('/clients', {
                templateUrl: 'build/views/client/dashboard.html',
                controller: 'ClientDashboardController',
                title: 'Clientes'
            })
            .when('/client/new', {
                templateUrl: 'build/views/client/new.html',
                controller: 'ClientNewController',
                title: 'Clientes'
            })
            .when('/client/:id/edit', {
                templateUrl: 'build/views/client/edit.html',
                controller: 'ClientEditController',
                title: 'Clientes'
            })
            .when('/client/:id/remove', {
                templateUrl: 'build/views/client/remove.html',
                controller: 'ClientRemoveController',
                title: 'Clientes'
            })
            .when('/projects', {
                templateUrl: 'build/views/project/dashboard.html',
                controller: 'ProjectDashboardController',
                title: 'Projetos'
            })
            .when('/projects/new', {
                templateUrl: 'build/views/project/new.html',
                controller: 'ProjectNewController',
                title: 'Projetos'
            })
            .when('/projects/:id', {
                templateUrl: 'build/views/project/show.html',
                controller: 'ProjectShowController',
                title: 'Projetos'
            })
            .when('/projects/:id/edit', {
                templateUrl: 'build/views/project/edit.html',
                controller: 'ProjectEditController',
                title: 'Projetos'
            })
            .when('/projects/:id/remove', {
                templateUrl: 'build/views/project/remove.html',
                controller: 'ProjectRemoveController',
                title: 'Projetos'
            })
            .when('/projects/:id/notes', {
                templateUrl: 'build/views/project/notes/list.html',
                controller: 'ProjectNotesListController'
            })
            .when('/projects/:id/notes/new', {
                templateUrl: 'build/views/project/notes/new.html',
                controller: 'ProjectNotesNewController'
            })
            .when('/projects/:id/notes/:idNote', {
                templateUrl: 'build/views/project/notes/show.html',
                controller: 'ProjectNotesShowController'
            })
            .when('/projects/:id/notes/:idNote/edit', {
                templateUrl: 'build/views/project/notes/edit.html',
                controller: 'ProjectNotesEditController'
            })
            .when('/projects/:id/notes/:idNote/remove', {
                templateUrl: 'build/views/project/notes/remove.html',
                controller: 'ProjectNotesRemoveController'
            })
            .when('/projects/:id/tasks', {
                templateUrl: 'build/views/project/tasks/list.html',
                controller: 'ProjectTasksListController'
            })
            .when('/projects/:id/tasks/new', {
                templateUrl: 'build/views/project/tasks/new.html',
                controller: 'ProjectTasksNewController'
            })
            .when('/projects/:id/tasks/:idTask/edit', {
                templateUrl: 'build/views/project/tasks/edit.html',
                controller: 'ProjectTasksEditController'
            })
            .when('/projects/:id/tasks/:idTask/remove', {
                templateUrl: 'build/views/project/tasks/remove.html',
                controller: 'ProjectTasksRemoveController'
            })
            .when('/projects/:id/files', {
                templateUrl: 'build/views/project/files/list.html',
                controller: 'ProjectFilesListController'
            })
            .when('/projects/:id/files/new', {
                templateUrl: 'build/views/project/files/new.html',
                controller: 'ProjectFilesNewController'
            })
            .when('/projects/:id/files/:idFile', {
                templateUrl: 'build/views/project/files/show.html',
                controller: 'ProjectFilesShowController'
            })
            .when('/projects/:id/files/:idFile/edit', {
                templateUrl: 'build/views/project/files/edit.html',
                controller: 'ProjectFilesEditController'
            })
            .when('/projects/:id/files/:idFile/remove', {
                templateUrl: 'build/views/project/files/remove.html',
                controller: 'ProjectFilesRemoveController'
            })
            .when('/projects/:id/members/', {
                templateUrl: 'build/views/project/members/list.html',
                controller: 'ProjectMembersListController'
            })
            .when('/projects/:id/members/:idProjectMember/remove', {
                templateUrl: 'build/views/project/members/remove.html',
                controller: 'ProjectMembersRemoveController'
            });

        OAuthProvider.configure({
            baseUrl: appConfigProvider.config.baseUrl,
            grantPath: '/oauth/access_token',
            clientId: 'angular_app',
            clientSecret: 'secret' // optional
        });

        OAuthTokenProvider.configure({
            name: 'token',
            options: {
                secure: false //desativa pois nao estamos com https ativo
            }
        });
    }
]);

app.run(['$rootScope', '$location', '$http', '$uibModal', 'httpBuffer', 'OAuth',
    function($rootScope, $location, $http, $uibModal, httpBuffer, OAuth) {
        $rootScope.$on('$routeChangeStart', function(event, next, current) {
            if (next.$$route.originalPath != '/login') {
                if (!OAuth.isAuthenticated()) {
                    $location.path('login');
                }
            }
        });

        $rootScope.$on('$routeChangeSuccess', function(event, current, previous) {
          $rootScope.pageTitle = current.$$route.title;
        });

        $rootScope.$on('oauth:error', function(event, data) {
            // Ignore `invalid_grant` error - should be catched on `LoginController`.
            if ('invalid_grant' === data.rejection.data.error) {
                return;
            }

            // Refresh token when a `access_denied` error occurs.
            if ('access_denied' === data.rejection.data.error) {
                httpBuffer.append(data.rejection.config, data.deferred);
                if (!$rootScope.loginModalOpened) {
                    var modalInstance = $uibModal.open({
                        templateUrl: 'build/views/templates/loginModal.html',
                        controller: 'LoginModalController'
                    });
                    $rootScope.loginModalOpened = true;
                }
                return;
            }

            // Redirect to `/login` with the `error_reason`.
            return $location.path('login');
        });
    }
]);
