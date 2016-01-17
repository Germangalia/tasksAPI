<?php

namespace App\Http\Controllers;

use App\Task;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use Response;

class TaskController extends Controller
{

    /**
     * TaskController constructor.
     */
    public function __construct()
    {
//        $this->beforeFilters('auth.basic', ['on' => 'post']);
        $this->middleware('auth.basic', ['only' => 'store']);
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //No es retorna tot: paginació

        $task = Task::all();

        return $this->respond([
            'data' => $this->transformCollection($task)
        ], 200);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
//        $task = new Task();
//
//        $this->saveTask($request, $task);

        if (! Input::get('name') or ! Input::get('done') or ! Input::get('priority'))
        {
            return $this->setStatusCode(422)->respondWithError('Parameters failed validation for a task.');
        }

        Task::create(Input::all());

        return $this->respondCreated('Task successfully created.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $task = Task::find($id);
        if(! $task)
        {
            return $this->respondNotFound('Task does not exist.');
//            return $this->respondWithError(404, 'Task does not exist');
//            return Response::json([
//                'error' => [
//                    'message' => 'Task does not exist'
//                ]
//            ], 404);
        }
        return $this->respond([
            'data' =>$this->transform($task->toArray())
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $task = Task::findOrFail($id);

        $this->saveTask($request, $task);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
       Task::destroy($id);
    }

    public function transformCollection($task){
        return array_map([$this, 'transform'], $task->toArray());
    }

    public function transform($task)
    {
        return array_map(function($tasks){

            return [
                'name' => $tasks['name'],
                'done' => $tasks['done'],
                'priority' => $tasks['priority'],
           ];
    }, $task->toArray);

    }

    /**
     * @param Request $request
     * @param $task
     */
    public function saveTask(Request $request, $task)
    {
        $task->name = $request->name;
        $task->done = $request->done;
        $task->priority = $request->priority;

        $task->save();
    }

    /**
     * @param $message
     * @return \Illuminate\Http\JsonResponse
     */
    public function respondCreated($message)
    {
        return $this->setStatusCode(201)->respond([
            'message' => $message
        ]);
    }
}
