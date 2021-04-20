<?php

namespace App\Http\Controllers\Componentes;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Modelos\Componentes\DataFuente;
use App\Modelos\Componentes\Indicador;
use App\Enums\TipoFrecuencia;
use App\Enums\TipoFormula;
use App\Enums\TipoCondicionIndicador;
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
            ->editColumn('primer_parametro', function(DataFuente $data){
                if($data->indicador->formula != TipoFormula::SUMA) return $data->primer_parametro.'/'.$data->segundo_parametro;
                else return $data->primer_parametro;
            })
            ->editColumn('fecha', function(DataFuente $data){
                if($data->indicador->frecuencia == TipoFrecuencia::DIARIO) return getFecha($data->fecha);
                if($data->indicador->frecuencia == TipoFrecuencia::QUINCENAL) return getFecha($data->fecha);
                if($data->indicador->frecuencia == TipoFrecuencia::MENSUAL) return getFecha($data->fecha, 'm/Y');
                if($data->indicador->frecuencia == TipoFrecuencia::ANUAL) return getFecha($data->fecha, 'Y');
            })
            ->addColumn('actions', function(DataFuente $data){
                return '<button type="button" class="btn btn-sm action-btn btn-inverse-info" data-toggle="modal" data-target="#modal-edit-fuente" title="Editar" data-original-title="Editar" data-id="'.$data->id.'" data-primero="'.$data->primer_parametro.'" data-segundo="'.$data->segundo_parametro.'"  data-fecha="'.getFecha($data->fecha).'">
                        <i class="mdi mdi-lead-pencil"></i>
                    </button>
                    <button type="button" class="btn btn-sm action-btn btn-inverse-danger" data-toggle="modal" data-target="#modal-delete-fuente" title="Deshabilitar" data-original-title="Deshabilitar" data-id="'.$data->id.'" data-fecha="'.getFecha($data->fecha).'">
                        <i class="mdi mdi-arrow-down-bold-hexagon-outline"></i>
                    </button>';
            })
            ->rawColumns(['actions'])
            ->toJson();
    }
    // END DATATABLE

    // DATATABLES
    function datatable_resultados($id){
        $datos = DataFuente::where([
            ['indicador_id', $id],
            ['activo', true]
        ])->get();

        return Datatables()->of($datos)
            ->editColumn('primer_parametro', function(DataFuente $data){
                if($data->indicador->formula != TipoFormula::SUMA) return $data->primer_parametro.'/'.$data->segundo_parametro;
                else return $data->primer_parametro;
            })
            ->editColumn('fecha', function(DataFuente $data){
                if($data->indicador->frecuencia == TipoFrecuencia::DIARIO) return getFecha($data->fecha);
                if($data->indicador->frecuencia == TipoFrecuencia::QUINCENAL) return getFecha($data->fecha);
                if($data->indicador->frecuencia == TipoFrecuencia::MENSUAL) return getFecha($data->fecha, 'm/Y');
                if($data->indicador->frecuencia == TipoFrecuencia::ANUAL) return getFecha($data->fecha, 'Y');
            })
            ->addColumn('formula', function(DataFuente $data){
                if($data->indicador->formula == TipoFormula::COMPLEMENTO){
                    return '[1-('.$data->primer_parametro.'/'.$data->segundo_parametro.')]*100';
                }
                if($data->indicador->formula == TipoFormula::PORCENTUAL){
                    return '('.$data->primer_parametro.'/'.$data->segundo_parametro.')*100';
                }
                if($data->indicador->formula == TipoFormula::SUMA){
                    return '&pound;'.$data->primer_parametro;
                }
            })
            ->addColumn('resultado', function(DataFuente $data){
                if($data->indicador->formula == TipoFormula::COMPLEMENTO){
                    $division = $data->primer_parametro/$data->segundo_parametro;
                    $division = (1-$division)*100;
                    return $division;
                }
                if($data->indicador->formula == TipoFormula::PORCENTUAL){
                    $division = $data->primer_parametro/$data->segundo_parametro;
                    $division = ($division)*100;
                    return $division;
                }
                if($data->indicador->formula == TipoFormula::SUMA){
                    return $data->primer_parametro;
                }
            })
            ->addColumn('indicador', function(DataFuente $data){
                $condicion = $data->indicador->tipo_condicion;
                $menor = $data->indicador->condicion_rojo;
                $mayor = $data->indicador->condicion_verde;

                if($data->indicador->formula == TipoFormula::COMPLEMENTO){
                    $resultado = $data->primer_parametro/$data->segundo_parametro;
                    $resultado = (1-$resultado)*100;
                }
                if($data->indicador->formula == TipoFormula::PORCENTUAL){
                    $resultado = $data->primer_parametro/$data->segundo_parametro;
                    $resultado = ($resultado)*100;
                }
                if($data->indicador->formula == TipoFormula::SUMA){
                    $resultado = $data->primer_parametro;
                }

                if($condicion == TipoCondicionIndicador::CONDICION_MENOR){
                    if($resultado < $menor) return '<span class="badge badge-danger">Condición Roja</span>';
                    else if($resultado > $mayor) return '<span class="badge badge-success">Condición Verde</span>';
                    else return '<span class="badge" style="background-color: yellow;">Condición Amarilla</span>';
                }else{
                    if($resultado > $menor) return '<span class="badge badge-danger">Condición Roja</span>';
                    else if($resultado < $mayor) return '<span class="badge badge-success">Condición Verde</span>';
                    else return '<span class="badge" style="background-color: yellow;">Condición Amarilla</span>';
                }
            })->rawColumns(['formula', 'indicador'])
            ->toJson();
    }
    // END DATATABLE

    public function store(Request $request, $id){
        $error = false;
        $message = "";
        $theme = '';
        $type = '';
        $collection = collect([]);

        $indicador = Indicador::FindOrFail($id);
        $datas = $indicador->data_fuente->where('activo', true);
        $fecha = convertirA_fecha($request->Cfecha, true, 'd/m/Y');
        
        if($indicador->frecuencia == TipoFrecuencia::DIARIO){
            foreach($datas as $item){
                $fechaCompare = convertirA_fecha($item->fecha, true, 'Y-m-d');
                if($fechaCompare == $fecha){
                    $error = true;
                    $message = '<span class="font-weight-bold"><i class="mdi mdi-bell"></i> La fecha ya se encuentra registrada.</span>';
                    $theme = 'sunset';
                    $type = 'error';
                    break;
                }
            }
        }

        if($indicador->frecuencia == TipoFrecuencia::QUINCENAL){
            $dia = $fecha->format('d');
            if($dia == 15 || $dia == 1){
                foreach($datas as $item){
                    $fechaCompare = convertirA_fecha($item->fecha, true, 'Y-m-d');
                    if($fechaCompare == $fecha){
                        $error = true;
                        $message = '<span class="font-weight-bold"><i class="mdi mdi-bell"></i> La fecha ya se encuentra registrada.</span>';
                        $theme = 'sunset';
                        $type = 'error';
                        break;
                    }
                }
            }else{
                $error = true;
                $message = '<span class="font-weight-bold"><i class="mdi mdi-bell"></i> La fecha debe ser quincenal.</span>';
                $theme = 'sunset';
                $type = 'error';
            }
        }

        if($indicador->frecuencia == TipoFrecuencia::MENSUAL){
            $mes_año = $fecha->format('Y-m');
            foreach($datas as $item){
                $fechaCompare = convertirA_fecha($item->fecha, true, 'Y-m-d');
                if($mes_año == $fechaCompare->format('Y-m')){
                    $error = true;
                    $message = '<span class="font-weight-bold"><i class="mdi mdi-bell"></i> El mes ya se encuentra registrado.</span>';
                    $theme = 'sunset';
                    $type = 'error';
                    break;
                }
            }
        }

        if($indicador->frecuencia == TipoFrecuencia::ANUAL){
            $año = $fecha->format('Y');
            foreach($datas as $item){
                $fechaCompare = convertirA_fecha($item->fecha, true, 'Y-m-d');
                if($año == $fechaCompare->format('Y')){
                    $error = true;
                    $message = '<span class="font-weight-bold"><i class="mdi mdi-bell"></i> El año ya se encuentra registrado.</span>';
                    $theme = 'sunset';
                    $type = 'error';
                    break;
                }
            }
        }

        if(!$error){
            $data = new DataFuente();
            $data->indicador_id = $id;
            $data->fecha = convertirA_fecha($request->Cfecha, false, 'd/m/Y');
            $data->primer_parametro = $request->Cprimer_parametro;
            if($indicador->formula != TipoFormula::SUMA) $data->segundo_parametro = $request->Csegundo_parametro;
            $data->created_by = Auth::user()->id;
            $data->save();

            $message = '<span class="font-weight-bold"><i class="mdi mdi-bell"></i> Se ha registrado correctamente la data fuente.</span>';
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
        $collection = collect([]);

        $data = DataFuente::FindOrFail($id);
        $indicador = Indicador::FindOrFail($data->indicador_id);
        $datas = $indicador->data_fuente->where('activo', true)->where('id', '<>', $id);
        $fecha = convertirA_fecha($request->Efecha, true, 'd/m/Y');
        
        if($indicador->frecuencia == TipoFrecuencia::DIARIO){
            foreach($datas as $item){
                $fechaCompare = convertirA_fecha($item->fecha, true, 'Y-m-d');
                if($fechaCompare == $fecha){
                    $error = true;
                    $message = '<span class="font-weight-bold"><i class="mdi mdi-bell"></i> La fecha ya se encuentra registrada.</span>';
                    $theme = 'sunset';
                    $type = 'error';
                    break;
                }
            }
        }

        if($indicador->frecuencia == TipoFrecuencia::QUINCENAL){
            $dia = $fecha->format('d');
            if($dia == 15 || $dia == 1){
                foreach($datas as $item){
                    $fechaCompare = convertirA_fecha($item->fecha, true, 'Y-m-d');
                    if($fechaCompare == $fecha){
                        $error = true;
                        $message = '<span class="font-weight-bold"><i class="mdi mdi-bell"></i> La fecha ya se encuentra registrada.</span>';
                        $theme = 'sunset';
                        $type = 'error';
                        break;
                    }
                }
            }else{
                $error = true;
                $message = '<span class="font-weight-bold"><i class="mdi mdi-bell"></i> La fecha debe ser quincenal.</span>';
                $theme = 'sunset';
                $type = 'error';
            }
        }

        if($indicador->frecuencia == TipoFrecuencia::MENSUAL){
            $mes_año = $fecha->format('Y-m');
            foreach($datas as $item){
                $fechaCompare = convertirA_fecha($item->fecha, true, 'Y-m-d');
                if($mes_año == $fechaCompare->format('Y-m')){
                    $error = true;
                    $message = '<span class="font-weight-bold"><i class="mdi mdi-bell"></i> El mes ya se encuentra registrado.</span>';
                    $theme = 'sunset';
                    $type = 'error';
                    break;
                }
            }
        }

        if($indicador->frecuencia == TipoFrecuencia::ANUAL){
            $año = $fecha->format('Y');
            foreach($datas as $item){
                $fechaCompare = convertirA_fecha($item->fecha, true, 'Y-m-d');
                if($año == $fechaCompare->format('Y')){
                    $error = true;
                    $message = '<span class="font-weight-bold"><i class="mdi mdi-bell"></i> El año ya se encuentra registrado.</span>';
                    $theme = 'sunset';
                    $type = 'error';
                    break;
                }
            }
        }

        if(!$error){
            $data->fecha = convertirA_fecha($request->Efecha, false, 'd/m/Y');
            $data->primer_parametro = $request->Eprimer_parametro;
            if($indicador->formula != TipoFormula::SUMA) $data->segundo_parametro = $request->Esegundo_parametro;
            $data->updated_by = Auth::user()->id;
            $data->update();

            $message = '<span class="font-weight-bold"><i class="mdi mdi-bell"></i> Se ha registrado correctamente la data fuente.</span>';
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
