<?php

namespace App\Http\Controllers;

use App\ToDoList;
use Illuminate\Http\Request;

class TodolistCtrl extends Controller
{
    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $list = ToDoList::all();
        return response()->json([
            'list' => $list
        ]);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function create(Request $request)
    {
        $rules = [
            'todo' => 'required'
        ];

        $this->validate($request, $rules);
        $todo = new ToDoList;
        $todo->todo = $request->get('todo');
        $todo->save();

        return response()->json([
            'message' => 'oke',
            'data' => [
                'id' => $todo->id,
                'todo' => $todo->todo
            ],
        ]);
    }

    /**
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function delete($id)
    {
        $data = ToDoList::find($id);
        $data->delete();
        return response()->json([
            'message' => 'sukses'
        ]);
    }
}
