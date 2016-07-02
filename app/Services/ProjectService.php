<?php

namespace CodeProject\Services;


use CodeProject\Repositories\ProjectRepository;
use CodeProject\Validators\ProjectValidator;

use Illuminate\Contracts\Validation\ValidationException;
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
                'error' => true,
                'message' => $e->getMessageBag()
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
                'error' => true,
                'message' => $e->getMessageBag()
            ];
        }
    }

    public function addMember($project_id, $member_id)
    {
        try {
            $project = $this->repository->find($project_id);
            $project->members()->attach($member_id);
            return ['error' => false, 'message' => 'Membro adicionado ao projeto.'];
        } catch (ModelNotFoundException $e) {
            return ['error' => true, 'message' => 'Projeto não encontrado.'];
        } catch (\Exception $e) {
            return ['error' => true, 'message' => 'Ocorreu algum erro ao adicionar o membro ao projeto.'];
        }
    }

    public function removeMember($project_id, $member_id)
    {
        try {
            $project = $this->repository->find($project_id);
            $project->members()->detach($member_id);
            return ['error' => false, 'message' => 'Membro removido do projeto.'];
        } catch (ModelNotFoundException $e) {
            return ['error' => true, 'message' => 'Projeto não encontrado.'];
        } catch (\Exception $e) {
            return ['error' => true, 'message' => 'Ocorreu algum erro ao remover o membro do projeto.'];
        }
    }

    public function isOwner($project_id, $owner_id)
    {
        if (count($this->repository->findWhere(['id' => $project_id, 'owner_id' => $owner_id])))
            return true;
        return false;
    }

    public function isMember($project_id, $member_id)
    {
        $project = $this->repository->find($project_id);
        $member = $project->members()->where('id', $member_id)->first();
        if ($member != null)
            return true;
        return false;
    }
}