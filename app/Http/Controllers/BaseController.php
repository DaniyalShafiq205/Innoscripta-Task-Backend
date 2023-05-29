<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;



class BaseController extends Controller
{
    protected $repository;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return $this->repository->getAll();
    }


    public function addToUser(Request $request)
    {


        $maxValue = 5; 


        $validator = Validator::make($request->all(), [
            'sources' => ['nullable', 'array'],
            'sources.*' => ['gt:0', 'lte:' . $maxValue, 'integer'],
            'categories' => ['nullable', 'array'],
            'categories.*' => ['gt:0', 'lte:' . $maxValue, 'integer'],
            'authors' => ['nullable', 'array'],
            'authors.*' => ['gt:0', 'lte:' . $maxValue, 'integer'],
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }
        return $this->repository->addToUser($request);
    }


}
