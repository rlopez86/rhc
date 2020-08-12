<?php

namespace App\Http\Controllers;

use App\Registro;
use Illuminate\Http\Request;

class BulletinController extends Controller
{
    private $columnsMap;

    public function __construct(){
        $this->columnsMap = ['email', 'idioma', 'fecha'];
    }

    public function index(){
        $registers = Registro::limit(10)->get();
        $count = Registro::count();
        return view('bulletin.index', ['registers'=>$registers, 'count'=>$count]);
    }

    public function toggle(Request $request, $id){
        $register = Registro::findOrFail($id);
        $register->activo = abs($register->activo - 1);
        $register->save();
        return $register->activo;
    }

    public function delete(Request $request, $id){
        $register = Registro::findOrFail($id);
        $register->delete();
        return 1;
    }

    public function unsubscribe(Request $request, $code){
        $result = base64_decode($code);
        $parts = explode('//', $result);
        $register = Registro::findOrFail($parts[0]);
        $register->delete();
        return 1;
    }

    public function data(Request $request)
    {
        $draw = $request->input('draw');
        $start = $request->input('start');
        $length = $request->input('length');
        $search = $request->input('search');
        $order = $request->input('order');
        $from = $request->input('from', '');
        $to = $request->input('to', '');
        $pub = $request->input('pub', -1);
        $recordsTotal = Registro::query()->count();

        $data = Registro::orderBy($this->columnsMap[$order[0]['column']], $order[0]['dir']);
        if($search['value']){
            $data->where(function($query) use ($search) {
                $query->where('email', 'like', '%'.$search['value'].'%');
                $query->orWhere('idioma', 'like', '%'.$search['value'].'%');
                $query->orWhere('fecha', 'like', '%'.$search['value'].'%');
            });
        }

        $recordsFiltered = $data->count();
        $data->skip($start)->take($length);
        //return $data->toSql();
        return [
            'draw'=>$draw,
            'data'=>$this->toDataRow($data->get()),
            'recordsFiltered'=>$recordsFiltered,
            'recordsTotal'=>$recordsTotal,
        ];
    }

    protected function toDataRow($registers){
        $data = [];
        foreach ($registers as $register){
            $data[] = [
                $register->email,
                $register->idioma,
                $register->fecha,
                '<a href="'.route('bulletin-toggle', $register->id).'" class="toggle"><i class="mdi '.($register->activo ? "mdi-flag" : "mdi-flag-outline").
                '"></i></a> <a href="'.route('bulletin-delete', $register->id).'" class="delete"><i class="mdi mdi-delete"></i></a>'
            ];
        }
        return $data;
    }
}
