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
use App\Modelos\Auth\Usuario;
use App\Enums\Rol;
use Carbon\Carbon;

class EmpresaController extends Controller
{
    public function __construct(){
        $this->middleware('auth');
    }

    // DATATABLES
    function datatable_empresas(){
        $usuario = Usuario::FindOrFail(Auth::user()->id);
        $rol = $usuario->getRoleNames()->first();

        if($rol == Rol::ADMINISTRADOR){
            $empresas = Empresa::all();
        }else{
            $empresas = Empresa::join('asignars as asg', 'empresas.id', 'asg.empresa_id')
                ->where([
                    ['empresas.activo', true],
                    ['asg.usuario_id', $usuario->id]
                ])->select('empresas.*', 'asg.activo as act_detalle')
                ->get();
        }
       
        return Datatables()->of($empresas)
            ->editColumn('activo', function(Empresa $empresa){
                if(!$empresa->act_detalle){
                    if($empresa->activo) return '<span class="badge badge-success">ACTIVA</span>';
                    else return '<span class="badge badge-danger">INACTIVA</span>';
                }else{
                    if($empresa->act_detalle) return '<span class="badge badge-success">ACTIVA</span>';
                    else return '<span class="badge badge-danger">INACTIVA</span>';
                }
            })
            ->addColumn('actions', function(Empresa $empresa){
                $buttons = '';

                if($empresa->activo)
                    if(!$empresa->act_detalle){
                        $buttons = '<a href="'.route("empresas.componentes", $empresa->id).'" class="btn btn-sm action-btn btn-inverse-success" title="Componentes" data-original-title="Componentes">
                            <i class="mdi mdi-folder"></i></a>
                            <a href="'.route("empresas.edit", $empresa->id).'" class="btn btn-sm action-btn btn-inverse-info" title="Editar" data-original-title="Editar">
                            <i class="mdi mdi-lead-pencil"></i></a>
                        <button type="button" class="btn btn-sm action-btn btn-inverse-danger" data-toggle="modal" data-target="#delete" title="Deshabilitar" data-original-title="Deshabilitar" data-id="'.$empresa->id.'" data-nombre="'.$empresa->razon_social.'">
                            <i class="mdi mdi-arrow-down-bold-hexagon-outline"></i>
                        </button>';
                    }else{
                        $buttons = '<a href="'.route("empresas.componentes", $empresa->id).'" class="btn btn-sm action-btn btn-inverse-success" title="Componentes" data-original-title="Componentes">
                        <i class="mdi mdi-folder"></i></a>
                        <a href="'.route("empresas.edit", $empresa->id).'" class="btn btn-sm action-btn btn-inverse-info" title="Editar" data-original-title="Editar">
                        <i class="mdi mdi-lead-pencil"></i></a>';
                    }
                else
                    $buttons = '<button type="button" class="btn btn-sm action-btn btn-inverse-success" data-toggle="modal" data-target="#active" title="Habilitar" data-original-title="Habilitar" data-id="'.$empresa->id.'" data-nombre="'.$empresa->razon_social.'">
                        <i class="mdi mdi-arrow-up-bold-hexagon-outline"></i></button>';
                return $buttons;
            })
            ->rawColumns(['actions', 'activo'])
            ->toJson();
    }
    // END DATATABLE
    
    public function index(){
        return view('gestion.empresas.index');
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
            'telefono.numeric' => 'El telefono debe ser num??rico.',
            'email.required' => 'El Email es obligatorio',
            'email.email' => 'El formato del correo electr??nico es incorrecto',
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
        
        $message = '<span class="font-weight-bold"><i class="mdi mdi-bell"></i> Se ha registrado correctamente la empresa.</span>';
        $theme = 'sunset';
        $type = 'success';

        $notificacion = [
            'message' => $message,
            'theme' => $theme,
            'type' => $type,
        ];

        return redirect()->route('empresas')->with(['notificacion' => $notificacion]);
    }

    public function edit($id){
        $empresa = Empresa::FindOrFail($id);

        return view('gestion.empresas.edit', compact('empresa'));
    }

    public function componentes($id){
        $empresa = Empresa::FindOrFail($id);

        return view('componentes.index', compact('empresa'));
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
            'telefono.numeric' => 'El telefono debe ser num??rico.',
            'email.required' => 'El Email es obligatorio',
            'email.email' => 'El formato del correo electr??nico es incorrecto',
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
        
        $message = '<span class="font-weight-bold"><i class="mdi mdi-bell"></i> Se ha actualizado correctamente la empresa.</span>';
        $theme = 'sunset';
        $type = 'success';

        $notificacion = [
            'message' => $message,
            'theme' => $theme,
            'type' => $type,
        ];

        return redirect()->route('empresas')->with(['notificacion' => $notificacion]);
    }

    public function delete(Request $request, $id){
        $error = false;
        $message = "";
        $collection = collect([]);

        $empresa = Empresa::FindOrFail($id);
        $empresa->activo = false;
        $empresa->deleted_by = Auth::user()->id;
        $empresa->updated_by = Auth::user()->id;
        $empresa->deleted_at = Carbon::now();
        $empresa->update();

        $message = '<span class="font-weight-bold"><i class="mdi mdi-bell"></i> Se ha deshabilitado correctamente la empresa.</span>';
        $theme = 'sunset';
        $type = 'success';

        $response = [
            'error' => $error,
            'message' => $message,
            'theme' => $theme,
            'type' => $type,
            'datos' => $collection
        ];

        return response()->json($response);
    }

    public function active(Request $request, $id){
        $error = false;
        $message = "";
        $collection = collect([]);

        $empresa = Empresa::FindOrFail($id);
        $empresa->activo = true;
        $empresa->activated_by = Auth::user()->id;
        $empresa->updated_by = Auth::user()->id;
        $empresa->activated_at = Carbon::now();
        $empresa->update();
        
        $message = '<span class="font-weight-bold"><i class="mdi mdi-bell"></i> Se ha habilitado correctamente la empresa.</span>';
        $theme = 'sunset';
        $type = 'success';

        $response = [
            'error' => $error,
            'message' => $message,
            'theme' => $theme,
            'type' => $type,
            'datos' => $collection
        ];

        return response()->json($response);
    }
}
