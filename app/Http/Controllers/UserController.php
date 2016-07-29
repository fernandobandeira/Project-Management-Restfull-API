<?php

namespace CodeProject\Http\Controllers;

use CodeProject\Repositories\UserRepository;
use LucaDegasperi\OAuth2Server\Facades\Authorizer;

class UserController extends Controller
{
    /**
   * @var ProjectRepository
   */
  private $repository;

    public function __construct(UserRepository $repository)
    {
        $this->repository = $repository;
    }

    public function authenticated()
    {
        $id = Authorizer::getResourceOwnerId();

        return $this->repository->find($id);
    }
}