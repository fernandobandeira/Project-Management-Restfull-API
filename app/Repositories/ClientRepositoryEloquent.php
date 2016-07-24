<?php

namespace CodeProject\Repositories;

use CodeProject\Entities\Client;
use CodeProject\Presenters\ClientPresenter;
use Prettus\Repository\Criteria\RequestCriteria;
use Prettus\Repository\Eloquent\BaseRepository;

/**
 * Class ClientRepositoryEloquent.
 */
class ClientRepositoryEloquent extends BaseRepository implements ClientRepository
{
    protected $fieldSearchable = [
      'name',
    ];

    /**
     * Specify Model class name.
     *
     * @return string
     */
    public function model()
    {
        return Client::class;
    }

    /**
     * Boot up the repository, pushing criteria.
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }

    public function presenter()
    {
        return ClientPresenter::class;
    }
}
