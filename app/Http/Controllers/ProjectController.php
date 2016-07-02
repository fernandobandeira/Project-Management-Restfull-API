<?php

namespace CodeProject\Http\Controllers;

use CodeProject\Repositories\ProjectRepository;
use CodeProject\Services\ProjectService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Authorizer;

class ProjectController extends Controller
{
    /**
     * @var ProjectRepository
     */
    private $repository;

    /**
     * @var ProjectService
     */
    private $service;

    public function __construct(ProjectRepository $repository, ProjectService $service)
    {
        $this->repository = $repository;
        $this->service = $service;

        $this->middleware('CheckProjectPermissions', ['except' => [
            'index',
            'store',
            'destroy'
        ]]);

        $this->middleware('CheckProjectOwner', ['only' => [
            'destroy',
        ]]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $ownedProjects = $this->repository
            ->with('client')
            ->with('owner')
            ->findWhere(['owner_id' => Authorizer::getResourceOwnerId()]);

        $memberOfProjects = $this->repository
            ->with('client')
            ->with('owner')
            ->whereHas('members', function ($query) {
                return $query->where('id', Authorizer::getResourceOwnerId());
            })->all();

        return $ownedProjects->merge($memberOfProjects);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {
            return $this->service->create($request->all());
        } catch (\Exception $e) {
            return ['error' => true, 'message' => 'Ocorreu algum erro ao salvar o projeto.'];
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try {            
            return $this->repository
                ->with('owner')
                ->with('client')
                ->with('members')
                ->with('notes')
                ->with('tasks')
                ->find($id);
        } catch (ModelNotFoundException $e) {
            return ['error' => true, 'message' => 'Projeto não encontrado.'];
        } catch (\Exception $e) {
            return ['error' => true, 'message' => 'Ocorreu algum erro ao exibir o projeto.'];
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        try {
            return $this->service->update($request->all(), $id);
        } catch (ModelNotFoundException $e) {
            return ['error' => true, 'message' => 'Projeto não encontrado.'];
        } catch (\Exception $e) {
            return ['error' => true, 'message' => 'Ocorreu algum erro ao atualizar o projeto.'];
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $this->repository->delete($id);
            return ['error' => false, 'message' => 'Projeto deletado com sucesso.'];
        } catch (QueryException $e) {
            return ['error' => true, 'message' => 'Projeto não pode ser apagado pois o mesmo possui vínculos.'];
        } catch (ModelNotFoundException $e) {
            return ['error' => true, 'message' => 'Projeto não encontrado.'];
        } catch (\Exception $e) {
            return ['error' => true, 'message' => 'Ocorreu algum erro ao deletar o projeto.'];
        }
    }

    public function members($id)
    {
        try {
            $project = $this->repository->find($id);
            return $project->members;
        } catch (ModelNotFoundException $e) {
            return ['error' => true, 'message' => 'Projeto não encontrado.'];
        } catch (\Exception $e) {
            return ['error' => true, 'message' => 'Ocorreu algum erro ao listar os membros do projeto.'];
        }
    }
}