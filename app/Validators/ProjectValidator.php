<?php

namespace CodeProject\Validators;

use Prettus\Validator\LaravelValidator;

class ProjectValidator extends LaravelValidator
{
    protected $rules = [
        'name'      => 'required|max:255',
        'status'    => 'required',
        'due_date'  => 'required|date',
        'owner_id'  => 'required|exists:users,id',
        'client_id' => 'required|exists:clients,id',
    ];
}
