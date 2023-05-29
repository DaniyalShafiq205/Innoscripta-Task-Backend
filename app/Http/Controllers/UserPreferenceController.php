<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repository\Repositories\UserPreferenceRepository;

class UserPreferenceController extends BaseController
{
    public function __construct(UserPreferenceRepository $repository)
    {
        $this->repository=$repository;
    }

}
