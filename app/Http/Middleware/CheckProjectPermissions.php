<?php

namespace CodeProject\Http\Middleware;

use Closure;
use CodeProject\Services\ProjectService;
use Authorizer;

class CheckProjectPermissions
{
    /**
     * @var ProjectService
     */
    private $service;

    public function __construct(ProjectService $service)
    {

        $this->service = $service;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $user_id = Authorizer::getResourceOwnerId();
        $project_id = $request->project;

        if ($this->service->isOwner($project_id, $user_id) == false && $this->service->isMember($project_id, $user_id) == false) {
            return [ 'error' => true, 'message' => 'Você não possui permissão para acessar este recurso.' ];
        }

        return $next($request);
    }
}
