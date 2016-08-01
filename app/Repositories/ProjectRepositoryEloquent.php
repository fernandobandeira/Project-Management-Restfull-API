<?php

namespace CodeProject\Repositories;

use CodeProject\Entities\Project;
use CodeProject\Presenters\ProjectPresenter;
use Prettus\Repository\Criteria\RequestCriteria;
use Prettus\Repository\Eloquent\BaseRepository;

/**
 * Class ProjectRepositoryEloquent.
 */
class ProjectRepositoryEloquent extends BaseRepository implements ProjectRepository
{
    /**
     * Specify Model class name.
     *
     * @return string
     */
    public function model()
    {
        return Project::class;
    }

    /**
     * Boot up the repository, pushing criteria.
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }

    public function addMember($project_id, $member_id)
    {
        try {
            $project = $this->find($project_id);
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
            $project = $this->find($project_id);
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
        $project = $this
            ->skipPresenter()
            ->findWhere(['id' => $project_id, 'owner_id' => $owner_id]);

        if (count($project)) {
            return true;
        }

        return false;
    }

    public function isMember($project_id, $member_id)
    {
        $project = $this
            ->whereHas(
                'members',
                function ($query) use ($member_id) {
                    return $query->where('id', $member_id);
                }
            )
            ->skipPresenter()
            ->find($project_id);

        if (count($project)) {
            return true;
        }

        return false;
    }

    public function members($project_id)
    {
        return $this->skipPresenter()->find($project_id)->members;
    }

    public function presenter()
    {
        return ProjectPresenter::class;
    }
}
