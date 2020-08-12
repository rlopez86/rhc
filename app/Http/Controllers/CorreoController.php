<?php

namespace App\Http\Controllers;

use App\Correo;
use App\Settings;
use Illuminate\Http\Request;

class CorreoController extends Controller
{
    //

    public function index(){
        $mails = Correo::orderBy('fecha', 'desc')->get();
        $filters = json_decode(Settings::where('key',config('app.correo_filters_key'))->first()->value);
        return view('correos.index', ['mails'=>$mails, 'filters'=>$filters]);
    }

    public function toggle($id){
        $mail = Correo::findOrFail($id);
        $mail->publicado = abs($mail->publicado - 1);
        $mail->save();
        return redirect(route('mails-index'));
    }

    public function delete($id){
        $mail = Correo::findOrFail($id);
        $mail->delete();
        return redirect(route('mails-index'));
    }

    public function addFilter(Request $request){
        $filters = json_decode(Settings::where('key',config('app.correo_filters_key'))->first()->value);
        $filters[] = $request->input('filter');
        Settings::where('key', config('app.correo_filters_key'))->update([
            'value'=>json_encode($filters)
        ]);
        return $request->input('filter');
    }

    public function deleteFilter(Request $request){
        $filters = json_decode(Settings::where('key',config('app.correo_filters_key'))->first()->value);
        $filters = array_filter($filters, function ($value) use($request){
            return $value != $request->input('filter');
        });
        Settings::where('key', config('app.correo_filters_key'))->update([
            'value'=>json_encode($filters)
        ]);
        return $request->input('filter');
    }
}
