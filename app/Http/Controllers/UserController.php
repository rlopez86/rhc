<?php

namespace App\Http\Controllers;

use App\Language;
use App\Permission;
use App\Redactor;
use App\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index(){
        $users = User::all();
        return view('users.index', ['users'=>$users]);
    }

    public function enable($id){
        $user = User::findOrFail($id);
        $user->habilitado ? $user->habilitado = 0 : $user->habilitado = 1;
        $user->save();
        return redirect(route('users-index'));
    }

    public function permissions($id){
        $user = User::findOrFail($id);
        $permissions = Permission::all();
        return view('users.permission', ['user'=>$user, 'permissions'=>$permissions]);
    }

    public function changePermissions($id, $id_perm){
        $user = User::findOrFail($id);
        $user->permissions()->toggle($id_perm);
        return redirect(route('users-permissions', $id));
    }

    public function edit($id){
        $user = User::findOrFail($id);
        $languages = Language::where('habilitado', 1)->get();
        return view('users.edit', ['user'=>$user, 'languages'=>$languages]);
    }

    public function add(){
        $user = new User;
        $languages = Language::where('habilitado', 1)->get();
        return view('users.edit', ['user'=>$user, 'languages'=>$languages]);
    }

    public function save(Request $request){
        $request->validate([
            'name'=>'required',
            'nick'=>'required',
            'email'=>'nullable|email',
            'r_password'=>'same:password'
        ]);
        if($request->input('id'))
            $user = User::findOrFail($request->input('id'));
        else
            $user = new User;
        $user->name = $request->input('name');
        $user->nick = $request->input('nick');
        $user->email = $request->input('email');
        if($request->input('password'))
            $user->password = bcrypt($request->input('password'));
        $user->save();
        if($user->redactor)
            $redactor = $user->redactor;
        else
            $redactor = new Redactor;
        if(!$redactor->users_iduser && $request->input('languages')){
            $redactor->idiomas = json_encode($request->input('languages'));
            $redactor->users_iduser = $user->id;
            $redactor->save();
        }
        else if($redactor->users_iduser){
            $redactor->idiomas = $request->input('languages') ? json_encode($request->input('languages')) : '[]';
            $redactor->save();
        }
        return redirect(route('users-index'));
    }
}
