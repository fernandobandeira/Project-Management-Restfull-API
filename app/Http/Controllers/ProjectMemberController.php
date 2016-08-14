<?php
namespace CodeProject\Http\Controllers;
use CodeProject\Repositories\ProjectMemberRepository;
use CodeProject\Services\ProjectMemberService;
use Illuminate\Http\Request;
use CodeProject\Http\Requests;
use CodeProject\Http\Controllers\Controller;
class ProjectMemberController extends Controller
{
    /**
     * @var ProjectMemberRepository
     */
    private $repository;
    /**
     * @var ProjectMemberService
     */
    private $service;
    public function __construct(ProjectMemberRepository $repository, ProjectMemberService $service)
    {
        $this->repository = $repository;
        $this->service = $service;
        $this->middleware('CheckProjectOwner', ['except' => ['index','show']]);
        $this->middleware('CheckProjectPermissions', ['only' => ['index','show']]);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($id)
    {
        return $this->repository->findWhere(['project_id'=>$id]);
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $id)
    {
        try {
                    $data = $request->all();
        $data['project_id'] = $id;
        return $this->service->create($data);
        } catch (\Exception $e) {
            return ['error' => true, 'message' => 'Ocorreu algum erro ao associar o membro.'];
        }
    }
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id, $idProjectMember)
    {
        try {
            return $this->repository->find($idProjectMember);
        } catch (ModelNotFoundException $e) {
            return ['error' => true, 'message' => 'Membro não encontrado.'];
        } catch (\Exception $e) {
            return ['error' => true, 'message' => 'Ocorreu algum erro ao exibir o membro.'];
        }
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id, $idProjectMember)
    {
        try {
             $this->repository->delete($idProjectMember);

            return ['error' => false, 'message' => 'Membro retirado com sucesso.'];
        } catch (ModelNotFoundException $e) {
            return ['error' => true, 'message' => 'Membro não encontrado.'];
        } catch (\Exception $e) {
            return ['error' => true, 'message' => 'Ocorreu algum erro ao retirar o membro.'];
        }
    }
}