<?php

namespace App\Http\Controllers;

use App\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    private $columnsMap;

    public function __construct(){
        $this->columnsMap = ['articulo', 'texto', 'autor', 'fecha'];
    }

    public function index(){
        $comments = Comment::orderBy('fecha', 'DESC')->take(10)->with('articleData')->get();
        $count = Comment::count();
        return view('comments.index', ['comments'=>$comments, 'count'=>$count]);
    }


    public function data(Request $request)
    {
        $draw = $request->input('draw');
        $start = $request->input('start');
        $length = $request->input('length');
        $search = $request->input('search');
        $order = $request->input('order');
        $recordsTotal = Comment::query()->count();
        $data = Comment::with('articleData')->orderBy($this->columnsMap[$order[0]['column']], $order[0]['dir']);
        if($search['value']){
            $data->whereHas('articleData', function($query) use($search){
                $query->where('nombre', 'like', '%'.$search['value'].'%');
            });
            $data->orWhere('autor', 'like', '%'.$search['value'].'%');
            $data->orWhere('fecha', 'like', '%'.$search['value'].'%');
        }
        $recordsFiltered = $data->count();
        $data->skip($start)->take($length);
        return [
            'draw'=>$draw,
            'data'=>$this->toDataRow($data->get()),
            'recordsFiltered'=>$recordsFiltered,
            'recordsTotal'=>$recordsTotal,
        ];
    }

    protected function toDataRow($comments){
        $data = [];
        foreach ($comments as $comment){
            $data[] = [
                $comment->articleData->nombre,
                $comment->texto,
                $comment->autor,
                $comment->fecha,
                view('comments.row', ['comment'=>$comment])->render()
            ];
        }
        return $data;
    }

    public function toggle($id){
        $comment = Comment::findOrFail($id);
        $comment->publicado = abs($comment->publicado - 1);
        $comment->save();
        return redirect(route('comments-index'));
    }

    public function delete($id){
        $comment = Comment::findOrFail($id);
        $comment->delete();
        return redirect(route('comments-index'));
    }
}
