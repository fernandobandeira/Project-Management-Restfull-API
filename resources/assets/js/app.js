var app = angular.module('app', ['ngRoute', 'angular-oauth2', 'app.controllers',
    'app.services', 'app.filters', 'app.directives', 'ui.bootstrap.typeahead', 
    'ui.bootstrap.datepickerPopup', 'ui.bootstrap.tpls', 'ngFileUpload'
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
                    if (dataJson.hasOwnProperty('data')) {
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

        $routeProvider
            .when('/login', {
                templateUrl: 'build/views/login.html',
                controller: 'LoginController'
            })
            .when('/home', {
                templateUrl: 'build/views/home.html',
                controller: 'HomeController'
            })
            .when('/clients', {
                templateUrl: 'build/views/client/list.html',
                controller: 'ClientListController'
            })
            .when('/clients/new', {
                templateUrl: 'build/views/client/new.html',
                controller: 'ClientNewController'
            })
            .when('/clients/:id', {
                templateUrl: 'build/views/client/show.html',
                controller: 'ClientShowController'
            })
            .when('/clients/:id/edit', {
                templateUrl: 'build/views/client/edit.html',
                controller: 'ClientEditController'
            })
            .when('/clients/:id/remove', {
                templateUrl: 'build/views/client/remove.html',
                controller: 'ClientRemoveController'
            })
            .when('/projects', {
                templateUrl: 'build/views/project/list.html',
                controller: 'ProjectListController'
            })
            .when('/projects/new', {
                templateUrl: 'build/views/project/new.html',
                controller: 'ProjectNewController'
            })
            .when('/projects/:id', {
                templateUrl: 'build/views/project/show.html',
                controller: 'ProjectShowController'
            })
            .when('/projects/:id/edit', {
                templateUrl: 'build/views/project/edit.html',
                controller: 'ProjectEditController'
            })
            .when('/projects/:id/remove', {
                templateUrl: 'build/views/project/remove.html',
                controller: 'ProjectRemoveController'
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

app.run(['$rootScope', '$window', 'OAuth', function($rootScope, $window, OAuth) {
    $rootScope.$on('oauth:error', function(event, rejection) {
        // Ignore `invalid_grant` error - should be catched on `LoginController`.
        if ('invalid_grant' === rejection.data.error) {
            return;
        }

        // Refresh token when a `invalid_token` error occurs.
        if ('invalid_token' === rejection.data.error) {
            return OAuth.getRefreshToken();
        }

        // Redirect to `/login` with the `error_reason`.
        return $window.location.href = '/login?error_reason=' + rejection.data.error;
    });
}]);
