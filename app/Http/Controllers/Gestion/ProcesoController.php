<?php

namespace App\Http\Controllers\Gestion;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use App\Modelos\Gestion\Empresa;
use App\Modelos\Gestion\Proceso;
use App\Enums\TipoProceso;
use Illuminate\Support\Str;
use Carbon\Carbon;

class ProcesoController extends Controller
{
    public function __construct(){
        $this->middleware('auth');
    }

    // DATATABLES
    function datatable_empresas(){
        $empresas = Empresa::all();

        return Datatables()->of($empresas)
            ->editColumn('activo', function(Empresa $empresa){
                if($empresa->activo) return '<span class="badge badge-success">ACTIVA</span>';
                else return '<span class="badge badge-danger">INACTIVA</span>';
            })
            ->addColumn('actions', function(Empresa $empresa){
                return '<a href="'.route("procesos.index", $empresa->id).'" class="btn btn-sm action-btn btn-inverse-info" title="Procesos" data-original-title="Procesos">
                        <i class="mdi mdi-sitemap"></i></a>';
            })
            ->rawColumns(['actions', 'activo'])
            ->toJson();
    }

    function datatable_procesos($id){
        $procesos = Proceso::where('empresa_id', $id)->get();

        return Datatables()->of($procesos)
            ->editColumn('tipo', function(Proceso $proceso){
                return TipoProceso::getDescription($proceso->tipo);
            })
            ->editColumn('activo', function(Proceso $proceso){
                if($proceso->activo) return '<span class="badge badge-success">ACTIVA</span>';
                else return '<span class="badge badge-danger">INACTIVA</span>';
            })
            ->addColumn('actions', function(Proceso $proceso){
                $buttons = '';

                if($proceso->activo)
                    $buttons = '<a href="javascript:void(0)" class="btn btn-sm action-btn btn-inverse-success" title="Editar" data-original-title="Editar">
                        <i class="mdi mdi-folder"></i></a>
                        <button type="button" class="btn btn-sm action-btn btn-inverse-info" data-toggle="modal" data-target="#edit" title="Editar" data-original-title="Editar" data-id="'.$proceso->id.'" data-nombre="'.$proceso->nombre.'" data-tipo="'.$proceso->tipo.'" data-descripcion="'.$proceso->descripcion.'">
                            <i class="mdi mdi-lead-pencil"></i>
                        </button>
                        <button type="button" class="btn btn-sm action-btn btn-inverse-danger" data-toggle="modal" data-target="#delete" title="Deshabilitar" data-original-title="Deshabilitar" data-id="'.$proceso->id.'" data-nombre="'.$proceso->nombre.'">
                            <i class="mdi mdi-arrow-down-bold-hexagon-outline"></i>
                        </button>';
                else
                    $buttons = '<button type="button" class="btn btn-sm action-btn btn-inverse-success" data-toggle="modal" data-target="#active" title="Habilitar" data-original-title="Habilitar" data-id="'.$proceso->id.'" data-nombre="'.$proceso->nombre.'">
                        <i class="mdi mdi-arrow-up-bold-hexagon-outline"></i></button>';
                return $buttons;
            })
            ->rawColumns(['actions', 'activo'])
            ->toJson();
    }
    // END DATATABLE

    public function empresa(){
        return view('gestion.procesos-subprocesos.index');
    }

    public function index($id){
        $empresa = Empresa::FindOrFail($id);

        return view('gestion.procesos-subprocesos.procesos.index', compact('empresa'));
    }

    public function store(Request $request, $id){
        $error = false;
        $message = "";
        $theme = "";
        $type = "";
        $collection = collect([]);

        if(!TipoProceso::getDescription($request->tipo)){
            $error = true;
            $message = '<span class="font-weight-bold"><i class="mdi mdi-bell"></i> El tipo de proceso es incorrecto.</span>';
            $theme = 'sunset';
            $type = 'danger';
        }
        
        if(!$error){
            $proceso = new Proceso();
            $proceso->empresa_id = $id;
            $proceso->tipo = $request->tipo;
            $proceso->nombre = $request->nombre;
            $proceso->descripcion = $request->descripcion;
            $proceso->created_by = Auth::user()->id;
            $proceso->save();

            $message = '<span class="font-weight-bold"><i class="mdi mdi-bell"></i> Se ha registrado correctamente el proceso.</span>';
            $theme = 'sunset';
            $type = 'success';
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

    public function update(Request $request, $id){
        $error = false;
        $message = "";
        $theme = "";
        $type = "";
        $collection = collect([]);

        if(!TipoProceso::getDescription($request->Etipo)){
            $error = true;
            $message = '<span class="font-weight-bold"><i class="mdi mdi-bell"></i> El tipo de proceso es incorrecto.</span>';
            $theme = 'sunset';
            $type = 'danger';
        }
        
        if(!$error){
            $proceso = Proceso::FindOrFail($id);
            $proceso->tipo = $request->Etipo;
            $proceso->nombre = $request->Enombre;
            $proceso->descripcion = $request->Edescripcion;
            $proceso->updated_by = Auth::user()->id;
            $proceso->update();

            $message = '<span class="font-weight-bold"><i class="mdi mdi-bell"></i> Se ha actualizado correctamente el proceso.</span>';
            $theme = 'sunset';
            $type = 'success';
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

    public function delete(Request $request, $id){
        $error = false;
        $message = "";
        $collection = collect([]);

        $proceso = Proceso::FindOrFail($id);
        $proceso->activo = false;
        $proceso->deleted_by = Auth::user()->id;
        $proceso->updated_by = Auth::user()->id;
        $proceso->deleted_at = Carbon::now();
        $proceso->update();

        $message = '<span class="font-weight-bold"><i class="mdi mdi-bell"></i> Se ha deshabilitado correctamente el proceso.</span>';
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

        $proceso = Proceso::FindOrFail($id);
        $proceso->activo = true;
        $proceso->activated_by = Auth::user()->id;
        $proceso->updated_by = Auth::user()->id;
        $proceso->activated_at = Carbon::now();
        $proceso->update();
        
        $message = '<span class="font-weight-bold"><i class="mdi mdi-bell"></i> Se ha habilitado correctamente el proceso.</span>';
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
