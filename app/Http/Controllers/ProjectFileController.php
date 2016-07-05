<?php

namespace CodeProject\Http\Controllers;

use CodeProject\Repositories\ProjectRepository;
use CodeProject\Services\ProjectService;
use CodeProject\Validators\ProjectFileValidator;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Prettus\Validator\Exceptions\ValidatorException;

class ProjectFileController extends Controller
{
    /**
     * @var ProjectRepository
     */
    private $repository;

    /**
     * @var ProjectService
     */
    private $service;
    /**
     * @var ProjectFileValidator
     */
    private $validator;

    public function __construct(ProjectRepository $repository, ProjectService $service, ProjectFileValidator $validator)
    {
        $this->repository = $repository;
        $this->service = $service;
        $this->validator = $validator;

        $this->middleware('CheckProjectPermissions', ['except' => [
            'destroy',
        ]]);

        $this->middleware('CheckProjectOwner', ['only' => [
            'destroy',
        ]]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {
            $this->validator->with($request->all())->passesOrFail();

            $file = $request->file('file');
            $extension = $file->getClientOriginalExtension();

            $data['name'] = $request->name;
            $data['extension'] = $extension;
            $data['description'] = $request->description;
            $data['project_id'] = $request->project;
            $data['file'] = $file;

            $this->service->createFile($data);

            return ['error' => false, 'message' => 'O arquivo foi adicionado ao projeto'];
        } catch (ValidatorException $e) {
            return [
                'error'   => true,
                'message' => $e->getMessageBag(),
            ];
        } catch (\Exception $e) {
            return ['error' => true, 'message' => 'Ocorreu algum erro adicionar o arquivo ao projeto.'];
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
            $this->service->deleteFile($project_id, $id);

            return ['error' => false, 'message' => 'Arquivo deletado com sucesso.'];
        } catch (ModelNotFoundException $e) {
            return ['error' => true, 'message' => 'Arquivo não encontrado.'];
        } catch (\Exception $e) {
            return ['error' => true, 'message' => 'Ocorreu algum erro ao deletar o arquivo.'];
        }
    }
}
