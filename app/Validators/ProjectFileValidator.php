<?php

namespace CodeProject\Validators;

use Prettus\Validator\LaravelValidator;

class ProjectFileValidator extends LaravelValidator
{
    protected $rules = [
        'project_id'  => 'required',
        'name'        => 'required',
        'file'        => 'required|file|mimes:jpeg,jpg,png,gif,pdf,zip',
        'description' => 'required',
    ];
}
