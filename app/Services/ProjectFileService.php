<?php

namespace CodeProject\Services;

use CodeProject\Repositories\ProjectFileRepository;
use CodeProject\Repositories\ProjectRepository;
use CodeProject\Validators\ProjectFileValidator;
use Illuminate\Contracts\Filesystem\Factory as Storage;
use Illuminate\Filesystem\Filesystem as File;
use Prettus\Validator\Exceptions\ValidatorException;

class ProjectFileService
{
    /**
     * @var ProjectFileRepository
     */
    protected $repository;
    /**
     * @var ProjectRepository
     */
    protected $projectRepository;
    /**
     * @var ProjectFileValidator
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

    public function __construct(ProjectFileRepository $repository, ProjectRepository $projectRepository, ProjectFileValidator $validator, Storage $storage, File $file)
    {
        $this->repository = $repository;
        $this->projectRepository = $projectRepository;
        $this->validator = $validator;
        $this->storage = $storage;
        $this->file = $file;
    }

    public function create(array $data)
    {
        try {
            $this->validator->with($data)->passesOrFail();

            $project = $this->projectRepository->skipPresenter()->find($data['project_id']);
            $projectFile = $project->files()->create($data);

            $this->storage->put($projectFile->id.'.'.$data['extension'], $this->file->get($data['file']));

            return $projectFile;
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

    public function delete($file_id)
    {
        $file = $this->repository->skipPresenter()->find($file_id);
        if ($this->storage->exits($file->id.'.'.$file->extension)) {
            $this->storage->delete($file->id.'.'.$file->extension);
        }

        $file->delete();
    }

    public function getFilePath($id)
    {
        $projectFile = $this->projectRepository->skipPresenter()->find($id);

        return $this->getBaseUrl($projectFile);
    }

    public function getBaseUrl($projectFile)
    {
        switch ($this->storage->getDefaultDriver()) {
        case 'local':
          return $this->storage->getDriver()->getAdapter()->getPathPrefix().'/'.$projectFile->id.'/'.$projectFile->extension;
      }
    }
}
