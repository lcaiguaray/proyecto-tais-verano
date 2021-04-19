<?php

namespace App\Http\Controllers\Componentes;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Modelos\Gestion\Empresa;
use App\Modelos\Componentes\MapaProceso;
use Carbon\Carbon;

class MapaProcesoController extends Controller
{
    public function __construct(){
        $this->middleware('auth');
    }

    // DATATABLES
    function datatable_mapa_procesos($id){
        $mapa_procesos = Empresa::FindOrFail($id)->mapa_proceso;

        return Datatables()->of($mapa_procesos)
            ->editColumn('activo', function(MapaProceso $mapa_proceso){
                if($mapa_proceso->activo) return '<span class="badge badge-success">ACTIVA</span>';
                else return '<span class="badge badge-danger">INACTIVA</span>';
            })
            ->addColumn('actions', function(MapaProceso $mapa_proceso){
                $buttons = '';

                if($mapa_proceso->activo)
                    $buttons = '<button type="button" class="btn btn-sm action-btn btn-inverse-info" data-toggle="modal" data-target="#modal-edit" title="Editar" data-original-title="Editar" data-id="'.$mapa_proceso->id.'" data-nombre="'.$mapa_proceso->nombre.'" data-descripcion="'.$mapa_proceso->descripcion.'">
                            <i class="mdi mdi-lead-pencil"></i>
                        </button>
                        <button type="button" class="btn btn-sm action-btn btn-inverse-danger" data-toggle="modal" data-target="#modal-delete" title="Deshabilitar" data-original-title="Deshabilitar" data-id="'.$mapa_proceso->id.'" data-nombre="'.$mapa_proceso->nombre.'">
                            <i class="mdi mdi-arrow-down-bold-hexagon-outline"></i>
                        </button>';
                else
                    $buttons = '<button type="button" class="btn btn-sm action-btn btn-inverse-success" data-toggle="modal" data-target="#modal-active" title="Habilitar" data-original-title="Habilitar" data-id="'.$mapa_proceso->id.'" data-nombre="'.$mapa_proceso->nombre.'">
                        <i class="mdi mdi-arrow-up-bold-hexagon-outline"></i></button>';
                return $buttons;
            })
            ->rawColumns(['actions', 'activo'])
            ->toJson();
    }
    // END DATATABLE

    public function index($id){
        $empresa = Empresa::FindOrFail($id);

        return view('componentes.mapa-procesos.index', compact('empresa'));
    }

    public function store(Request $request, $id){
        $error = false;
        $message = "";
        $theme = "";
        $type = "";
        $collection = collect([]);

        $verificar = MapaProceso::where([
            ['empresa_id', $id],
            ['nombre', $request->nombre]
        ])->get();

        if($verificar->count() > 0){
            $error = true;
            $message = '<span class="font-weight-bold"><i class="mdi mdi-bell"></i> El nombre ingresado ya existe, ingrese otro por favor.</span>';
            $theme = 'sunset';
            $type = 'error';
        }

        if(!$error){
            $mapa_proceso = new MapaProceso();
            $mapa_proceso->empresa_id = $id;
            $mapa_proceso->nombre = $request->nombre;
            $mapa_proceso->descripcion = $request->descripcion;
            $mapa_proceso->created_by = Auth::user()->id;
            $mapa_proceso->save();

            $message = '<span class="font-weight-bold"><i class="mdi mdi-bell"></i> Se ha registrado correctamente el mapa de proceso.</span>';
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
            $mapa_proceso = MapaProceso::FindOrFail($id);
            $mapa_proceso->nombre = $request->Enombre;
            $mapa_proceso->descripcion = $request->Edescripcion;
            $mapa_proceso->updated_by = Auth::user()->id;
            $mapa_proceso->update();

            $message = '<span class="font-weight-bold"><i class="mdi mdi-bell"></i> Se ha modificado correctamente el mapa de proceso.</span>';
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

        $mapa_proceso = MapaProceso::FindOrFail($id);
        $mapa_proceso->activo = false;
        $mapa_proceso->deleted_by = Auth::user()->id;
        $mapa_proceso->updated_by = Auth::user()->id;
        $mapa_proceso->deleted_at = Carbon::now();
        $mapa_proceso->update();

        $message = '<span class="font-weight-bold"><i class="mdi mdi-bell"></i> Se ha deshabilitado correctamente el mapa de proceso.</span>';
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

        $mapa_proceso = MapaProceso::FindOrFail($id);
        $mapa_proceso->activo = true;
        $mapa_proceso->activated_by = Auth::user()->id;
        $mapa_proceso->updated_by = Auth::user()->id;
        $mapa_proceso->activated_at = Carbon::now();
        $mapa_proceso->update();
        
        $message = '<span class="font-weight-bold"><i class="mdi mdi-bell"></i> Se ha habilitado correctamente el mapa de proceso.</span>';
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
