<?php

namespace App\Http\Controllers;

use App\Tag;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Response;

class TagController extends Controller
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

        $lesson = Tag::all();

        return $this->respond([
            'data' => $this->transformCollection($lesson)
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
//        $tag = new Tag();
//
//        $this->saveTag($request, $tag);

        if (! Input::get('name') or ! Input::get('done'))
        {
            return $this->setStatusCode(422)->respondWithError('Parameters failed validation for a tag.');
        }

        Tag::create(Input::all());

        return $this->respondCreated('Tag successfully created.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $lesson = Tag::find($id);
        if(! $lesson)
        {
            return $this->respondNotFound('Task does not exist.');
//            return Response::json([
//                'error' => [
//                    'message' => 'Tag does not exist'
//                ]
//            ], 404);
        }
        return $this->respond([
            'data' =>$this->transform($lesson->toArray())
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
        $tag = Tag::findOrFail($id);

        $this->saveTag($request, $tag);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Tag::destroy($id);
    }

    public function transformCollection($lesson){
        return array_map([$this, 'transform'], $lesson->toArray());
    }

    public function transform($tag)
    {
        return[

            'name' => $tag['name'],
            'done' => (boolean) $tag['done'],
        ];


    }

    /**
     * @param Request $request
     * @param $tag
     */
    public function saveTag(Request $request, $tag)
    {
        $tag->name = $request->name;
        $tag->done = $request->done;

        $tag->save();
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
