<?php

namespace CodeProject\Http\Controllers;

use CodeProject\Repositories\ProjectNoteRepository;
use CodeProject\Services\ProjectNoteService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;

class ProjectNoteController extends Controller
{
    /**
     * @var ProjectNoteRepository
     */
    private $repository;

    /**
     * @var ProjectNoteService
     */
    private $service;

    public function __construct(ProjectNoteRepository $repository, ProjectNoteService $service)
    {
        $this->repository = $repository;
        $this->service = $service;
    }

    /**
     * Display a listing of the resource.
     *
     * @param int $id
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
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $project_id)
    {
        try {
            $data = $request->all();
            $data['project_id'] = $project_id;

            return $this->service->create($data);
        } catch (\Exception $e) {
            return ['error' => true, 'message' => 'Ocorreu algum erro ao salvar a nota do projeto.'];
        }
    }

    /**
     * Display the specified resource.
     *
     * @param int $projectId
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function show($project_id, $id)
    {
        try {
            return $this->repository->find($id);
        } catch (ModelNotFoundException $e) {
            return ['error' => true, 'message' => 'Nota do projeto não encontrada.'];
        } catch (\Exception $e) {
            return ['error' => true, 'message' => 'Ocorreu algum erro ao exibir a nota do projeto.'];
        }
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
            return ['error' => true, 'message' => 'Nota do projeto não encontrada.'];
        } catch (\Exception $e) {
            return ['error' => true, 'message' => 'Ocorreu algum erro ao atualizar a nota do projeto.'];
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $project_id
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy($project_id, $id)
    {
        try {
            $this->repository->delete($id);

            return ['error' => false, 'message' => 'Nota do projeto deletada com sucesso.'];
        } catch (ModelNotFoundException $e) {
            return ['error' => true, 'message' => 'Nota do projeto não encontrada.'];
        } catch (\Exception $e) {
            return ['error' => true, 'message' => 'Ocorreu algum erro ao deletar a nota do projeto.'];
        }
    }
}
