<?php

namespace App\Http\Controllers\Gestion;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use App\Modelos\Gestion\Empresa;
use Carbon\Carbon;

class EmpresaController extends Controller
{
    public function __construct(){
        $this->middleware('auth');
    }

    public function index(){
        $empresas = Empresa::all();

        return view('gestion.empresas.index', compact('empresas'));
    }

    public function create(){
        return view('gestion.empresas.create');
    }

    public function store(Request $request){
        $data = $request->all();

        $rules = [
            'razonsocial' => ['required', Rule::unique('empresas', 'razon_social')],
            'ruc' => ['required', 'digits:11', Rule::unique('empresas', 'ruc')],
            'telefono' => 'nullable|numeric',
            'email' => ['required', 'email', Rule::unique('empresas', 'email')],
            'direccion' => 'nullable',
        ];

        $message = [
            'razonsocial.required' => 'La Razon Social es obligatorio',
            'razonsocial.unique' => 'La Razon Social ya existe, ingrese otra por favor',
            'ruc.required' => 'El RUC es obligatorio',
            'ruc.digits' => 'El RUC debe ser de 11 digitos.',
            'ruc.unique' => 'El RUC ya existe, ingrese otro por favor',
            'telefono.numeric' => 'El telefono debe ser numérico.',
            'email.required' => 'El Email es obligatorio',
            'email.email' => 'El formato del correo electrónico es incorrecto',
            'email.unique' => 'El Email ya existe, ingrese otro por favor',
        ];

        Validator::make($data, $rules, $message)->validate();
        
        $empresa = new Empresa();
        $empresa->razon_social = $request->razonsocial;
        $empresa->ruc = $request->ruc;
        $empresa->telefono = $request->telefono;
        $empresa->email = $request->email;
        $empresa->direccion = $request->direccion;
        $empresa->created_by = Auth::user()->id;
        $empresa->save();

        $message = 'Se ha registrado correctamente la empresa.';
        $type = 's';
        $icon = 's';

        $arrayMessage = [
            'message' => $message,
            'type' => $type,
            'icon' => $icon
        ];

        return redirect()->route('empresas')->with(['message' => $arrayMessage]);
    }

    public function edit($id){
        $empresa = Empresa::FindOrFail($id);

        return view('gestion.empresas.edit', compact('empresa'));
    }

    public function update(Request $request, $id){
        $data = $request->all();

        $rules = [
            'razonsocial' => ['required', Rule::unique('empresas', 'razon_social')->ignore($id)],
            'ruc' => ['required', 'digits:11', Rule::unique('empresas', 'ruc')->ignore($id)],
            'telefono' => 'nullable|numeric',
            'email' => ['required', 'email', Rule::unique('empresas', 'email')->ignore($id)],
            'direccion' => 'nullable',
        ];

        $message = [
            'razonsocial.required' => 'La Razon Social es obligatorio',
            'razonsocial.unique' => 'La Razon Social ya existe, ingrese otra por favor',
            'ruc.required' => 'El RUC es obligatorio',
            'ruc.digits' => 'El RUC debe ser de 11 digitos.',
            'ruc.unique' => 'El RUC ya existe, ingrese otro por favor',
            'telefono.numeric' => 'El telefono debe ser numérico.',
            'email.required' => 'El Email es obligatorio',
            'email.email' => 'El formato del correo electrónico es incorrecto',
            'email.unique' => 'El Email ya existe, ingrese otro por favor',
        ];

        Validator::make($data, $rules, $message)->validate();
        
        $empresa = Empresa::FindOrFail($id);
        $empresa->razon_social = $request->razonsocial;
        $empresa->ruc = $request->ruc;
        $empresa->telefono = $request->telefono;
        $empresa->email = $request->email;
        $empresa->direccion = $request->direccion;
        $empresa->updated_by = Auth::user()->id;
        $empresa->update();

        $message = 'Se ha registrado correctamente la empresa.';
        $type = 's';
        $icon = 's';

        $arrayMessage = [
            'message' => $message,
            'type' => $type,
            'icon' => $icon
        ];

        return redirect()->route('empresas')->with(['message' => $arrayMessage]);
    }

    public function delete(Request $request, $id){        
        $empresa = Empresa::FindOrFail($id);
        $empresa->activo = false;
        $empresa->deleted_by = Auth::user()->id;
        $empresa->updated_by = Auth::user()->id;
        $empresa->deleted_at = Carbon::now();
        $empresa->update();

        return redirect()->route('empresas');
    }

    public function active(Request $request, $id){
        $empresa = Empresa::FindOrFail($id);
        $empresa->activo = true;
        $empresa->activated_by = Auth::user()->id;
        $empresa->updated_by = Auth::user()->id;
        $empresa->activated_at = Carbon::now();
        $empresa->update();

        return redirect()->route('empresas');
    }
}
