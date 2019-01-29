<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Todo;
use App\Category;
use Yajra\Datatables\Datatables;

class TodoListsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $this->userRole = 'todo';
        $data['userRole'] = $this->userRole;

        $data['categoryList'] = Category::select(['id','name'])->pluck('name','id')->toArray();

        if ($request->ajax()) {
            $todo = Todo::select('todo.*','category.name as catname',\DB::raw('DATE_FORMAT(todo.created_at, "%D %M") as date'))->leftjoin('category','todo.category_id','=','category.id')->orderBy('todo.id','desc')->get();                                                                                                                                       
            return Datatables::of($todo)
                    ->editColumn('name', function ($user) {
                        return !empty($user->name) ? $user->name : 'N/A';
                    }) 
                    ->editColumn('catname', function ($user) {
                        return !empty($user->catname) ? $user->catname : 'N/A';
                    }) 
                    ->editColumn('date', function ($user) {
                        return !empty($user->date) ? $user->date : 'N/A';
                    })
                    ->addColumn('action', function($user) {
                        $links = [''];
                        if($this->userRole == 'todo')
                        {
                            $links['delete'] = '<a class="btn btn-danger btn-xs" href="javascript:delete_account('.$user->id.',\'delete\',\'Delete\')">Delete</a>';
                        }
                        return implode('&nbsp;&nbsp;', $links);
                    })
                    ->make(true);
        }

        return view(sprintf('todolists.%s',$this->userRole))->with($data);
        
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $rules = [
            'name' => 'required',
        ];

        $messages = [
            'name.required' => 'Please enter Todo Name.'
        ];
        
        $v = \Validator::make($request->all(),$rules,$messages);

        if($v->fails()) {
            return $v->errors();
        }

        else
        {
            $user = Todo::create(['name'=>$request->name,'category_id'=>$request->category,'created_at' => date('Y-m-d H:i:s'),'updated_at' => date('Y-m-d H:i:s')]);
            return \Response::json(['status' => 'success', 'message' => 'Success']);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function delete(Request $request)
    {
        $todoList = Todo::where('id',$request->id)->delete();

        return 'true';
    }
}
