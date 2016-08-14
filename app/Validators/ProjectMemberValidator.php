<?php

namespace CodeProject\Validators;

use Prettus\Validator\LaravelValidator;

class ProjectMemberValidator extends LaravelValidator
{
    protected $rules = [
        'project_id' => 'required|exists:projects,id',
        'member_id'  => 'required|exists:users,id',
    ];
}
