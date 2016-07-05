<?php

namespace CodeProject\Http\Middleware;

use Authorizer;
use Closure;
use CodeProject\Repositories\ProjectRepository;

class CheckProjectPermissions
{
    /**
     * @var ProjectRepository
     */
    private $repository;

    public function __construct(ProjectRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure                 $next
     *
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $user_id = Authorizer::getResourceOwnerId();
        $project_id = $request->project;

        if ($this->repository->isOwner($project_id, $user_id) == false && $this->repository->isMember($project_id, $user_id) == false) {
            return ['error' => true, 'message' => 'Você não possui permissão para acessar este recurso.'];
        }

        return $next($request);
    }
}
