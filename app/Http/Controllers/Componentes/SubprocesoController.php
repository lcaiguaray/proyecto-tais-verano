<?php

namespace App\Http\Controllers\Componentes;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use App\Modelos\Componentes\Subproceso;
use App\Modelos\Componentes\Proceso;
use App\Modelos\Gestion\Empresa;
use Carbon\Carbon;

class SubprocesoController extends Controller
{
    public function __construct(){
        $this->middleware('auth');
    }
    
    // DATATABLES
    function datatable_subprocesos($id){
        $subprocesos = Subproceso::where('proceso_id', $id)->get();

        return Datatables()->of($subprocesos)
            ->editColumn('activo', function(Subproceso $subproceso){
                if($subproceso->activo) return '<span class="badge badge-success">ACTIVA</span>';
                else return '<span class="badge badge-danger">INACTIVA</span>';
            })
            ->addColumn('actions', function(Subproceso $subproceso){
                $buttons = '';

                if($subproceso->activo)
                    $buttons = '<button type="button" class="btn btn-sm action-btn btn-inverse-info" data-toggle="modal" data-target="#modal-edit" title="Editar" data-original-title="Editar" data-id="'.$subproceso->id.'" data-nombre="'.$subproceso->nombre.'" data-descripcion="'.$subproceso->descripcion.'">
                            <i class="mdi mdi-lead-pencil"></i>
                        </button>
                        <button type="button" class="btn btn-sm action-btn btn-inverse-danger" data-toggle="modal" data-target="#modal-delete" title="Deshabilitar" data-original-title="Deshabilitar" data-id="'.$subproceso->id.'" data-nombre="'.$subproceso->nombre.'">
                            <i class="mdi mdi-arrow-down-bold-hexagon-outline"></i>
                        </button>';
                else
                    $buttons = '<button type="button" class="btn btn-sm action-btn btn-inverse-success" data-toggle="modal" data-target="#modal-active" title="Habilitar" data-original-title="Habilitar" data-id="'.$subproceso->id.'" data-nombre="'.$subproceso->nombre.'">
                        <i class="mdi mdi-arrow-up-bold-hexagon-outline"></i></button>';
                return $buttons;
            })
            ->rawColumns(['actions', 'activo'])
            ->toJson();
    }
    // END DATATABLE

    public function index($id){
        $proceso = Proceso::FindOrFail($id);
        $empresa = Empresa::FindOrFail($proceso->mapa_proceso->empresa_id);

        return view('componentes.procesos-subprocesos.subprocesos.index', compact('proceso', 'empresa'));
    }

    public function store(Request $request, $id){
        $error = false;
        $message = "";
        $theme = "";
        $type = "";
        $collection = collect([]);

        if(!$error){
            $subproceso = new Subproceso();
            $subproceso->proceso_id = $id;
            $subproceso->nombre = $request->nombre;
            $subproceso->descripcion = $request->descripcion;
            $subproceso->created_by = Auth::user()->id;
            $subproceso->save();

            $message = '<span class="font-weight-bold"><i class="mdi mdi-bell"></i> Se ha registrado correctamente el subproceso.</span>';
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

        if(!$error){
            $subproceso = Subproceso::FindOrFail($id);
            $subproceso->nombre = $request->Enombre;
            $subproceso->descripcion = $request->Edescripcion;
            $subproceso->updated_by = Auth::user()->id;
            $subproceso->update();

            $message = '<span class="font-weight-bold"><i class="mdi mdi-bell"></i> Se ha modificado correctamente el subproceso.</span>';
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

        $subproceso = Subproceso::FindOrFail($id);
        $subproceso->activo = false;
        $subproceso->deleted_by = Auth::user()->id;
        $subproceso->updated_by = Auth::user()->id;
        $subproceso->deleted_at = Carbon::now();
        $subproceso->update();

        $message = '<span class="font-weight-bold"><i class="mdi mdi-bell"></i> Se ha deshabilitado correctamente el subproceso.</span>';
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

        $subproceso = Subproceso::FindOrFail($id);
        $subproceso->activo = true;
        $subproceso->activated_by = Auth::user()->id;
        $subproceso->updated_by = Auth::user()->id;
        $subproceso->activated_at = Carbon::now();
        $subproceso->update();
        
        $message = '<span class="font-weight-bold"><i class="mdi mdi-bell"></i> Se ha habilitado correctamente el subproceso.</span>';
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
