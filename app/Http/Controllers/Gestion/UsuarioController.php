<?php

namespace App\Http\Controllers\Gestion;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use App\Modelos\Auth\Usuario;
use Carbon\Carbon;

class UsuarioController extends Controller
{
    public function __construct(){
        $this->middleware('auth');
    }

    // DATATABLES
    function datatable_usuarios(){
        $usuarios = Usuario::all();

        return Datatables()->of($usuarios)
            ->editColumn('activo', function(Usuario $usuario){
                if($usuario->activo) return '<span class="badge badge-success">ACTIVA</span>';
                else return '<span class="badge badge-danger">INACTIVA</span>';
            })
            ->editColumn('nombre', function(Usuario $usuario){
                return $usuario->nombre.' '.$usuario->apellido_paterno.' '.$usuario->apellido_materno;
            })
            ->addColumn('actions', function(Usuario $usuario){
                $buttons = '';

                if($usuario->activo)
                    $buttons = '<a href="'.route("usuarios.edit", $usuario->id).'" class="btn btn-sm action-btn btn-inverse-info" title="Editar" data-original-title="Editar">
                        <i class="mdi mdi-lead-pencil"></i></a>
                    <button type="button" class="btn btn-sm action-btn btn-inverse-danger" data-toggle="modal" data-target="#delete" title="Deshabilitar" data-original-title="Deshabilitar" data-id="'.$usuario->id.'" data-nombre="'.$usuario->nombre.'">
                        <i class="mdi mdi-arrow-down-bold-hexagon-outline"></i>
                    </button>';
                else
                    $buttons = '<button type="button" class="btn btn-sm action-btn btn-inverse-success" data-toggle="modal" data-target="#active" title="Habilitar" data-original-title="Habilitar" data-id="'.$usuario->id.'" data-nombre="'.$usuario->nombre.'">
                        <i class="mdi mdi-arrow-up-bold-hexagon-outline"></i></button>';
                return $buttons;
            })
            ->rawColumns(['actions', 'activo'])
            ->toJson();
    }
    // END DATATABLE

    public function index(){
        return view('gestion.usuarios.index');
    }

    public function create(){
        return view('gestion.usuarios.create');
    }

    public function store(Request $request){
        $data = $request->all();

        $rules = [
            'nombre' => 'required',
            'apellido_paterno' => 'required',
            'apellido_materno' => 'required',
            'dni' => ['required', 'digits:8', Rule::unique('usuarios', 'dni')],
            'telefono' => 'nullable|numeric|max:20',
            'sexo' => 'required',
            'fecha_nacimiento' => 'nullable|date_format:d/m/Y',
            'email' => ['required', 'max:255', 'email', Rule::unique('usuarios', 'email')],
            'direccion' => 'nullable|max:255',
        ];

        $message = [
            'nombre.required' => 'El nombre es obligatorio.',
            'apellido_paterno.required' => 'El apellido paterno es obligatorio.',
            'apellido_materno.required' => 'El apellido materno es obligatorio.',
            'dni.required' => 'El DNI es obligatorio.',
            'dni.digits' => 'El DNI debe ser de 8 digitos.',
            'dni.unique' => 'El DNI ya existe, ingrese otro por favor.',
            'telefono.numeric' => 'El telefono debe ser numérico.',
            'telefono.max:20' => 'El telefono debe ser maximo de 20 digitos.',
            'sexo.required' => 'El sexo es obligatorio',
            'fecha_nacimiento.date_format:d/m/Y' => 'El formato de la fecha de nacimiento es incorrecto.',
            'email.required' => 'El Email es obligatorio.',
            'email.max:255' => 'El correo electrónico debe ser maximo de 20 caracteres.',
            'email.email' => 'El formato del correo electrónico es incorrecto.',
            'email.unique' => 'El Email ya existe, ingrese otro por favor',
            'direccion.max:255' => 'La dirección debe ser maximo de 255 caracteres.',
        ];

        Validator::make($data, $rules, $message)->validate();
        
        $usuario = new Usuario();
        $usuario->nombre = $request->nombre;
        $usuario->apellido_paterno = $request->apellido_paterno;
        $usuario->apellido_materno = $request->apellido_materno;
        $usuario->dni = $request->dni;
        $usuario->telefono = $request->telefono;
        $usuario->sexo = $request->sexo;
        $usuario->fecha_nacimiento = Carbon::parse($request->fecha_nacimiento)->format('Y-m-d');
        $usuario->email = $request->email;
        $usuario->direccion = $request->direccion;
        $usuario->name = $request->dni;
        $usuario->password = Hash::make($request->dni);
        $usuario->created_by = Auth::user()->id;
        $usuario->save();
        
        $message = '<span class="font-weight-bold"><i class="mdi mdi-bell"></i> Se ha registrado correctamente el usuario.</span>';
        $theme = 'sunset';
        $type = 'success';

        $notificacion = [
            'message' => $message,
            'theme' => $theme,
            'type' => $type,
        ];

        return redirect()->route('usuarios')->with(['notificacion' => $notificacion]);
    }

    public function edit($id){
        $usuario = Usuario::FindOrFail($id);

        return view('gestion.usuarios.edit', compact('usuario'));
    }

    public function update(Request $request, $id){
        $data = $request->all();

        $rules = [
            'nombre' => 'required',
            'apellido_paterno' => 'required',
            'apellido_materno' => 'required',
            'dni' => ['required', 'digits:8', Rule::unique('usuarios', 'dni')->ignore($id)],
            'telefono' => 'nullable|numeric|max:20',
            'sexo' => 'required',
            'fecha_nacimiento' => 'nullable|date_format:d/m/Y',
            'email' => ['required', 'max:255', 'email', Rule::unique('usuarios', 'email')->ignore($id)],
            'direccion' => 'nullable|max:255',
        ];

        $message = [
            'nombre.required' => 'El nombre es obligatorio.',
            'apellido_paterno.required' => 'El apellido paterno es obligatorio.',
            'apellido_materno.required' => 'El apellido materno es obligatorio.',
            'dni.required' => 'El DNI es obligatorio.',
            'dni.digits' => 'El DNI debe ser de 8 digitos.',
            'dni.unique' => 'El DNI ya existe, ingrese otro por favor.',
            'telefono.numeric' => 'El telefono debe ser numérico.',
            'telefono.max:20' => 'El telefono debe ser maximo de 20 digitos.',
            'sexo.required' => 'El sexo es obligatorio',
            'fecha_nacimiento.date_format:d/m/Y' => 'El formato de la fecha de nacimiento es incorrecto.',
            'email.required' => 'El Email es obligatorio.',
            'email.max:255' => 'El correo electrónico debe ser maximo de 20 caracteres.',
            'email.email' => 'El formato del correo electrónico es incorrecto.',
            'email.unique' => 'El Email ya existe, ingrese otro por favor',
            'direccion.max:255' => 'La dirección debe ser maximo de 255 caracteres.',
        ];

        Validator::make($data, $rules, $message)->validate();
        
        $usuario = Usuario::FindOrFail($id);
        $usuario->nombre = $request->nombre;
        $usuario->apellido_paterno = $request->apellido_paterno;
        $usuario->apellido_materno = $request->apellido_materno;
        $usuario->dni = $request->dni;
        $usuario->telefono = $request->telefono;
        $usuario->sexo = $request->sexo;
        $usuario->fecha_nacimiento = Carbon::parse($request->fecha_nacimiento)->format('Y-m-d');
        $usuario->email = $request->email;
        $usuario->direccion = $request->direccion;
        $usuario->update();
        
        $message = '<span class="font-weight-bold"><i class="mdi mdi-bell"></i> Se ha actualizado correctamente el usuario.</span>';
        $theme = 'sunset';
        $type = 'success';

        $notificacion = [
            'message' => $message,
            'theme' => $theme,
            'type' => $type,
        ];

        return redirect()->route('usuarios')->with(['notificacion' => $notificacion]);
    }

    public function delete(Request $request, $id){
        $error = false;
        $message = "";
        $collection = collect([]);

        $usuario = Usuario::FindOrFail($id);
        $usuario->activo = false;
        $usuario->deleted_by = Auth::user()->id;
        $usuario->updated_by = Auth::user()->id;
        $usuario->deleted_at = Carbon::now();
        $usuario->update();

        $message = '<span class="font-weight-bold"><i class="mdi mdi-bell"></i> Se ha deshabilitado correctamente el usuario.</span>';
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

        $usuario = Usuario::FindOrFail($id);
        $usuario->activo = true;
        $usuario->activated_by = Auth::user()->id;
        $usuario->updated_by = Auth::user()->id;
        $usuario->activated_at = Carbon::now();
        $usuario->update();
        
        $message = '<span class="font-weight-bold"><i class="mdi mdi-bell"></i> Se ha habilitado correctamente el usuario.</span>';
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
