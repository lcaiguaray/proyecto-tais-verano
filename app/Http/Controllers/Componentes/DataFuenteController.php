<?php

namespace App\Http\Controllers\Componentes;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Modelos\Componentes\DataFuente;
use Carbon\Carbon;

class DataFuenteController extends Controller
{
    public function __construct(){
        $this->middleware('auth');
    }

    // DATATABLES
    function datatable_datos($id){
        $datos = DataFuente::where([
            ['indicador_id', $id],
            ['activo', true]
        ])->get();

        return Datatables()->of($datos)
            ->editColumn('fecha', function(DataFuente $data){
                return getFecha($data->fecha);
            })
            ->addColumn('actions', function(DataFuente $data){
                return '<button type="button" class="btn btn-sm action-btn btn-inverse-info" data-toggle="modal" data-target="#modal-edit-fuente" title="Editar" data-original-title="Editar" data-id="'.$data->id.'" data-valor="'.$data->valor.'" data-fecha="'.getFecha($data->fecha).'">
                        <i class="mdi mdi-lead-pencil"></i>
                    </button>
                    <button type="button" class="btn btn-sm action-btn btn-inverse-danger" data-toggle="modal" data-target="#modal-delete-fuente" title="Deshabilitar" data-original-title="Deshabilitar" data-id="'.$data->id.'" data-valor="'.$data->valor.'" data-fecha="'.getFecha($data->fecha).'">
                        <i class="mdi mdi-arrow-down-bold-hexagon-outline"></i>
                    </button>';
            })
            ->rawColumns(['actions'])
            ->toJson();
    }
    // END DATATABLE

    public function store(Request $request, $id){
        $error = false;
        $message = "";
        $collection = collect([]);

        $data = new DataFuente();
        $data->indicador_id = $id;
        $data->fecha = convertirA_fecha($request->Cfecha, false, 'd/m/Y');
        $data->valor = $request->Cvalor;
        $data->created_by = Auth::user()->id;
        $data->save();

        $message = '<span class="font-weight-bold"><i class="mdi mdi-bell"></i> Se ha registrado correctamente la data fuente.</span>';
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

    public function update(Request $request, $id){
        $error = false;
        $message = "";
        $collection = collect([]);

        $data = DataFuente::FindOrFail($id);
        $data->fecha = convertirA_fecha($request->Efecha, false, 'd/m/Y');
        $data->valor = $request->Evalor;
        $data->updated_by = Auth::user()->id;
        $data->update();

        $message = '<span class="font-weight-bold"><i class="mdi mdi-bell"></i> Se ha registrado correctamente la data fuente.</span>';
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

    public function delete(Request $request, $id){
        $error = false;
        $message = "";
        $collection = collect([]);

        $data = DataFuente::FindOrFail($id);
        $data->activo = false;
        $data->deleted_by = Auth::user()->id;
        $data->updated_by = Auth::user()->id;
        $data->deleted_at = Carbon::now();
        $data->update();

        $message = '<span class="font-weight-bold"><i class="mdi mdi-bell"></i> Se ha eliminado correctamente la data fuente.</span>';
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
