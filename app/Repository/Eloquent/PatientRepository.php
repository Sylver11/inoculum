<?php

namespace App\Repository\Eloquent;

use App\Models\Patient;
use App\Repository\PatientRepositoryInterface;

class PatientRepository extends BaseRepository implements PatientRepositoryInterface
{
    /**
     * @var Model
     */
    protected $model;

    /**
     * BaseRepository constructor.
     *
     * @param Model $model
     */
    public function __construct(Patient $model)
    {
        $this->model = $model;
    }
}