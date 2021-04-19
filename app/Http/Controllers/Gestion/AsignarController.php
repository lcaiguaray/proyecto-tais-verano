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
use App\Modelos\Gestion\Asignar;
use App\Modelos\Auth\Usuario;
use Carbon\Carbon;

class AsignarController extends Controller
{
    public function __construct(){
        $this->middleware('auth');
    }

    // DATATABLES
    function datatable_asignaciones(){
        $asignaciones = Asignar::all();

        return Datatables()->of($asignaciones)
            ->editColumn('empresa_id', function(Asignar $asignar){
                return 'Empresa: '.$asignar->empresa->razon_social;
            })
            ->editColumn('usuario_id', function(Asignar $asignar){
                return $asignar->usuario->nombre.' '.$asignar->usuario->apellido_paterno.' '.$asignar->usuario->apellido_materno;
            })
            ->editColumn('activo', function(Asignar $asignar){
                if($asignar->activo) return '<span class="badge badge-success">ACTIVA</span>';
                else return '<span class="badge badge-danger">INACTIVA</span>';
            })
            ->addColumn('actions', function(Asignar $asignar){
                $buttons = '';

                if($asignar->activo)
                    $buttons = '<a href="'.route("asignaciones.edit", $asignar->id).'" class="btn btn-sm action-btn btn-inverse-info" title="Editar" data-original-title="Editar">
                        <i class="mdi mdi-lead-pencil"></i></a>
                    <button type="button" class="btn btn-sm action-btn btn-inverse-danger" data-toggle="modal" data-target="#modal-delete" title="Deshabilitar" data-original-title="Deshabilitar" data-id="'.$asignar->id.'" data-nombre="'.$asignar->usuario->nombre.' '.$asignar->usuario->apellido_paterno.' '.$asignar->usuario->apellido_materno.'">
                        <i class="mdi mdi-arrow-down-bold-hexagon-outline"></i>
                    </button>';
                else
                    $buttons = '<button type="button" class="btn btn-sm action-btn btn-inverse-success" data-toggle="modal" data-target="#modal-active" title="Habilitar" data-original-title="Habilitar" data-id="'.$asignar->id.'" data-nombre="'.$asignar->usuario->nombre.' '.$asignar->usuario->apellido_paterno.' '.$asignar->usuario->apellido_materno.'">
                        <i class="mdi mdi-arrow-up-bold-hexagon-outline"></i></button>';
                return $buttons;
            })
            ->rawColumns(['actions', 'activo'])
            ->toJson();
    }
    // END DATATABLE

    public function cargar_usuarios(Request $request){
        $error = false;
        $message = '';
        $theme = '';
        $type = '';
        $collection = collect([]);
        $id = $request->id;
        $usuario = $request->usuario;

        if($usuario){
            $usuariosND = Asignar::where([
                ['empresa_id', $id],
                ['usuario_id', '<>', $usuario],
                ['activo', true]
            ])->select('usuario_id')->get();
        }else{
            $usuariosND = Asignar::where([
                ['empresa_id', $id],
                ['activo', true]
            ])->select('usuario_id')->get();
        }
        
        $usuarios = Usuario::where('activo', true)
            ->whereNotIn('id', $usuariosND)
            ->get();

        if($usuarios->count() > 0){
            foreach ($usuarios as $usuario) {
                $collection->push([
                    'id' => $usuario->id,
                    'text' => $usuario->nombre.' '.$usuario->apellido_paterno.' '.$usuario->apellido_materno
                ]);
            }
        }else{
            $error = true;
            $message = '<span class="font-weight-bold"><i class="mdi mdi-bell"></i> No se han encontrado usuarios.</span>';
            $theme = 'sunset';
            $type = 'warning';
        }

        $response = [
            'error' => $error,
            'message' => $message,
            'theme' => $theme,
            'type' => $type,
            'datos' => $collection
        ];

        return response()->json($response);
    }

    public function index(){
        return view('gestion.asignacion.index');
    }

    public function create(){
        $empresas = Empresa::where('activo', true)->get();

        return view('gestion.asignacion.create', compact('empresas'));
    }

    public function store(Request $request){
        $data = $request->all();

        $rules = [
            'empresa' => 'required',
            'usuario' => 'required',
        ];

        $message = [
            'empresa.required' => 'La empresa es obligatorio.',
            'usuario.required' => 'El usuario es obligatorio.',
        ];

        Validator::make($data, $rules, $message)->validate();

        $asignar = new Asignar();
        $asignar->empresa_id = $request->empresa;
        $asignar->usuario_id = $request->usuario;
        $asignar->descripcion = $request->descripcion;
        $asignar->created_by = Auth::user()->id;
        $asignar->save();

        $message = '<span class="font-weight-bold"><i class="mdi mdi-bell"></i> Se ha registrado correctamente la asignación.</span>';
        $theme = 'sunset';
        $type = 'success';

        $notificacion = [
            'message' => $message,
            'theme' => $theme,
            'type' => $type,
        ];

        return redirect()->route('asignaciones')->with(['notificacion' => $notificacion]);
    }

    public function edit($id){
        $asignacion = Asignar::FindOrFail($id);
        $empresas = Empresa::where('activo', true)->get();

        return view('gestion.asignacion.edit', compact('asignacion', 'empresas'));
    }

    public function update(Request $request, $id){
        $data = $request->all();

        $rules = [
            'empresa' => 'required',
            'usuario' => 'required',
        ];

        $message = [
            'empresa.required' => 'La empresa es obligatorio.',
            'usuario.required' => 'El usuario es obligatorio.',
        ];

        Validator::make($data, $rules, $message)->validate();

        $asignar = Asignar::FindOrFail($id);
        $asignar->empresa_id = $request->empresa;
        $asignar->usuario_id = $request->usuario;
        $asignar->descripcion = $request->descripcion;
        $asignar->updated_by = Auth::user()->id;
        $asignar->update();

        $message = '<span class="font-weight-bold"><i class="mdi mdi-bell"></i> Se ha actualizado correctamente la asignación.</span>';
        $theme = 'sunset';
        $type = 'success';

        $notificacion = [
            'message' => $message,
            'theme' => $theme,
            'type' => $type,
        ];

        return redirect()->route('asignaciones.edit', $asignar->id)->with(['notificacion' => $notificacion]);
    }

    public function delete(Request $request, $id){
        $error = false;
        $message = "";
        $collection = collect([]);

        $empresa = Asignar::FindOrFail($id);
        $empresa->activo = false;
        $empresa->deleted_by = Auth::user()->id;
        $empresa->updated_by = Auth::user()->id;
        $empresa->deleted_at = Carbon::now();
        $empresa->update();

        $message = '<span class="font-weight-bold"><i class="mdi mdi-bell"></i> Se ha deshabilitado correctamente la asignación.</span>';
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

        $empresa = Asignar::FindOrFail($id);
        $empresa->activo = true;
        $empresa->activated_by = Auth::user()->id;
        $empresa->updated_by = Auth::user()->id;
        $empresa->activated_at = Carbon::now();
        $empresa->update();
        
        $message = '<span class="font-weight-bold"><i class="mdi mdi-bell"></i> Se ha habilitado correctamente la asignación.</span>';
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
