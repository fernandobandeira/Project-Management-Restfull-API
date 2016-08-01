<?php

namespace CodeProject\Http\Controllers;

use CodeProject\Repositories\ProjectFileRepository;
use CodeProject\Services\ProjectFileService;
use CodeProject\Validators\ProjectFileValidator;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;

class ProjectFileController extends Controller
{
    /**
     * @var ProjectFileRepository
     */
    private $repository;

    /**
     * @var ProjectFileService
     */
    private $service;
    /**
     * @var ProjectFileValidator
     */
    private $validator;

    public function __construct(
        ProjectFileRepository $repository,
        ProjectFileService $service,
        ProjectFileValidator $validator
    ) {
        $this->repository = $repository;
        $this->service = $service;
        $this->validator = $validator;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($id)
    {
        return $this->repository->findWhere(['project_id' => $id]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int                      $projectId
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $project_id)
    {
        try {
            $file = $request->file('file');
            $extension = $file->getClientOriginalExtension();

            $data['name'] = $request->name;
            $data['extension'] = $extension;
            $data['description'] = $request->description;
            $data['project_id'] = $project_id;
            $data['file'] = $file;

            return $this->service->create($data);
        } catch (\Exception $e) {
            return ['error' => true, 'message' => 'Ocorreu algum erro adicionar o arquivo ao projeto.'];
        }
    }

    /**
     * Display the specified resource.
     *
     * @param int $project_id
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function show($project_id, $id)
    {
        try {
            return $this->repository->find($id);
        } catch (ModelNotFoundException $e) {
            return ['error' => true, 'message' => 'Arquivo do projeto não encontrado.'];
        } catch (\Exception $e) {
            return ['error' => true, 'message' => 'Ocorreu algum erro ao exibir o arquivo do projeto.'];
        }
    }

    public function showFile($project_id, $id)
    {
        $filePath = $this->service->getFilePath($id);
        $fileContent = file_get_contents($filePath);
        $file64 = base64_encode($fileContent);

        return [
            'file' => $file64,
            'size' => filesize($filePath),
            'name' => $this->service->getFileName($id),
        ];
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int                      $project_id
     * @param int                      $id
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $project_id, $id)
    {
        try {
            $data = $request->all();
            $data['project_id'] = $project_id;

            return $this->service->update($data, $id);
        } catch (ModelNotFoundException $e) {
            return ['error' => true, 'message' => 'Arquivo do projeto não encontrado.'];
        } catch (\Exception $e) {
            return ['error' => true, 'message' => 'Ocorreu algum erro ao atualizar o arquivo do projeto.'];
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy($project_id, $id)
    {
        try {
            $this->service->delete($id);

            return ['error' => false, 'message' => 'Arquivo deletado com sucesso.'];
        } catch (ModelNotFoundException $e) {
            return ['error' => true, 'message' => 'Arquivo não encontrado.'];
        } catch (\Exception $e) {
            return ['error' => true, 'message' => 'Ocorreu algum erro ao deletar o arquivo.'];
        }
    }
}
