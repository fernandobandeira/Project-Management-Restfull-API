<?php

namespace CodeProject\Services;

use CodeProject\Repositories\ProjectRepository;
use CodeProject\Validators\ProjectValidator;
use Illuminate\Contracts\Filesystem\Factory as Storage;
use Illuminate\Filesystem\Filesystem as File;
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
    /**
     * @var Storage
     */
    private $storage;
    /**
     * @var File
     */
    private $file;

    public function __construct(ProjectRepository $repository, ProjectValidator $validator, Storage $storage, File $file)
    {
        $this->repository = $repository;
        $this->validator = $validator;
        $this->storage = $storage;
        $this->file = $file;
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

    public function userProjects($user_id)
    {
        return $this->repository->scopeQuery(function ($query) use ($user_id) {
            return $query->select('projects.*')
                ->leftJoin('project_members', 'project_members.project_id', '=', 'projects.id')
                ->where('project_members.user_id', '=', $user_id)
                ->orWhere('owner_id', '=', $user_id);
        })->all();
    }

    public function createFile(array $data)
    {
        $project = $this->repository->skipPresenter()->find($data['project_id']);
        $projectFile = $project->files()->create($data);

        $this->storage->put($projectFile->id.'.'.$data['extension'], $this->file->get($data['file']));
    }

    public function deleteFile($project_id, $file_id)
    {
        $project = $this->repository->skipPresenter()->find($project_id);
        $file = $project->files()->where('id', $file_id)->firstOrFail();

        $this->storage->delete($file->id.'.'.$file->extension);
        $file->delete();
    }
}
