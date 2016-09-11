<?php

namespace CodeProject\Services;

use CodeProject\Repositories\ProjectRepository;
use CodeProject\Validators\ProjectValidator;
use Prettus\Validator\Exceptions\ValidatorException;

class ProjectService
{
    /**
     * @var ProjectRepository
     */
    protected $repository;
    /**
     * @var ProjectValidator
     */
    protected $validator;

    public function __construct(ProjectRepository $repository, ProjectValidator $validator)
    {
        $this->repository = $repository;
        $this->validator = $validator;
    }

    public function create(array $data)
    {
        try {
            $this->validator->with($data)->passesOrFail();

            return $this->repository->create($data);
        } catch (ValidatorException $e) {
            return [
                'error'   => true,
                'message' => $e->getMessageBag(),
            ];
        }
    }

    public function update(array $data, $id)
    {
        try {
            $this->validator->with($data)->passesOrFail();

            return $this->repository->update($data, $id);
        } catch (ValidatorException $e) {
            return [
                'error'   => true,
                'message' => $e->getMessageBag(),
            ];
        }
    }

    public function userProjects($user_id, $limit)
    {
        return $this->repository->scopeQuery(
            function ($query) use ($user_id) {
                return $query->select('projects.*')
                    ->leftJoin('project_members', 'project_members.project_id', '=', 'projects.id')
                    ->where('project_members.member_id', '=', $user_id)
                    ->orWhere('owner_id', '=', $user_id);
            }
        )->paginate($limit);
    }
}
