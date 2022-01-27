<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\Api\TodoIndexRequest;
use App\UseCases\Api\Todo\TodoIndexAction;
use App\Http\Requests\Api\TodoStoreRequest;
use App\UseCases\Api\Todo\TodoStoreAction;
use App\Http\Requests\Api\TodoUpdateRequest;
use App\UseCases\Api\Todo\TodoUpdateAction;
use App\Http\Requests\Api\TodoDeleteRequest;
use App\UseCases\Api\Todo\TodoDeleteAction;

class ToDoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param TodoIndexRequest $request
     * @param TodoIndexAction $action
     * @return \Illuminate\Http\Response $tasks
     */
    public function index(TodoIndexRequest $request, TodoIndexAction $action)
    {
        $tasks = $action->invoke($request);
        return $tasks;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  TodoStoreRequest  $request
     * @param  TodoStoreAction  $action
     * @return \Illuminate\Http\Response $tasks
     */
    public function store(TodoStoreRequest $request, TodoStoreAction $action)
    {
        $tasks = $action->invoke($request);
        return $tasks;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  TodoUpdateRequest  $request
     * @param  TodoUpdateAction  $action
     * @param  int  $taskId
     * @return \Illuminate\Http\Response $tasks
     */
    public function update(TodoUpdateRequest $request, $taskId, TodoUpdateAction $action)
    {
        $tasks = $action->invoke($request, $taskId);
        return $tasks;
    }

    /**
     * Remove the specified resource from storage.
     * 
     * @param  TodoDeleteRequest  $request
     * @param  TodoDeleteAction  $action
     * @param  int  $taskId
     * @return \Illuminate\Http\Response
     */
    public function destroy(TodoDeleteRequest $request, $taskId, TodoDeleteAction $action)
    {
        $tasks = $action->invoke($request, $taskId);
        return $tasks;
    }
}
