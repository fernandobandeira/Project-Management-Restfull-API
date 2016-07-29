<?php

namespace CodeProject\Transformers;

use CodeProject\Entities\ProjectFile;
use League\Fractal\TransformerAbstract;

class ProjectFileTransformer extends TransformerAbstract
{
    public function transform(ProjectFile $projectFile)
    {
        return [
            'id'          => $projectFile->id,
            'name'        => $projectFile->title,
            'description' => $projectFile->description,
            'extension'   => $projectFile->extension,
        ];
    }
}
