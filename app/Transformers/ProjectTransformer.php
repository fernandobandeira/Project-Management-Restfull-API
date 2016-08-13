<?php

namespace CodeProject\Transformers;

use CodeProject\Entities\Project;
use League\Fractal\TransformerAbstract;
use Authorizer;

class ProjectTransformer extends TransformerAbstract
{
    protected $defaultIncludes = [
        'client', 'owner', 'members', 'tasks', 'notes', 'files',
    ];

    public function transform(Project $project)
    {
        return [
            'id'          => $project->id,
            'name'        => $project->name,
            'description' => $project->description,
            'progress'    => (int) $project->progress,
            'status'      => $project->status,
            'due_date'    => $project->due_date,
            'is_member'   => $project->owner_id != Authorizer::getResourceOwnerId()
        ];
    }

    public function includeMembers(Project $project)
    {
        return $this->collection($project->members, new ProjectMemberTransformer());
    }

    public function includeTasks(Project $project)
    {
        return $this->collection($project->tasks, new ProjectTaskTransformer());
    }

    public function includeNotes(Project $project)
    {
        return $this->collection($project->notes, new ProjectNoteTransformer());
    }

    public function includeClient(Project $project)
    {
        return $this->item($project->client, new ClientTransformer());
    }

    public function includeOwner(Project $project)
    {
        return $this->item($project->owner, new ProjectOwnerTransformer());
    }

    public function includeFiles(Project $project)
    {
        return $this->collection($project->files, new ProjectFileTransformer());
    }
}
