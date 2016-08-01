<?php

namespace CodeProject\Validators;

use Prettus\Validator\LaravelValidator;

class ProjectTaskValidator extends LaravelValidator
{
    protected $rules = [
        'title'      => 'required',
        'note'       => 'required',
    ];
}
