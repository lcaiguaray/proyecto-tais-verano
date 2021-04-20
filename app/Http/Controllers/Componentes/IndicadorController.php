<?php

namespace App\Http\Controllers\Componentes;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use App\Modelos\Gestion\Empresa;
use App\Modelos\Componentes\MapaProceso;
use App\Modelos\Componentes\Subproceso;
use App\Modelos\Componentes\Indicador;
use App\Enums\TipoObjeto;
use App\Enums\TipoFrecuencia;
use App\Enums\TipoFormula;
use App\Enums\TipoProceso;
use App\Enums\TipoCondicionIndicador;
use Carbon\Carbon;

class IndicadorController extends Controller
{
    public function __construct(){
        $this->middleware('auth');
    }

    // DATATABLES
    function datatable_indicadores($id){
        $indicadores = Indicador::join('mapa_procesos as mapa', 'mapa.id', 'indicadors.mapa_proceso_id')
            ->where([
                ['mapa.empresa_id', $id],
                ['indicadors.activo', true]
            ])->select('indicadors.*')->get();

        return Datatables()->of($indicadores)
            ->editColumn('objeto_id', function(Indicador $indicador){
                if($indicador->objeto_tipo == TipoObjeto::PROCESO) return $indicador->proceso->nombre;
                return $indicador->subproceso->nombre;
            })
            ->editColumn('objeto_tipo', function(Indicador $indicador){
                if($indicador->objeto_tipo == TipoObjeto::PROCESO) return TipoObjeto::getDescription($indicador->objeto_tipo).' ('.TipoProceso::getDescription($indicador->proceso->tipo).')';
                return TipoObjeto::getDescription($indicador->objeto_tipo).' ('.$indicador->subproceso->proceso->nombre.')';
            })
            ->editColumn('mapa_proceso_id', function(Indicador $indicador){
                return 'Mapa de Proceso: '.$indicador->mapa_proceso->nombre;
            })
            ->addColumn('actions', function(Indicador $indicador){
                return '<a href="'.route("indicadores.show", $indicador->id).'" class="btn btn-sm action-btn btn-inverse-success" title="Editar" data-original-title="Editar">
                    <i class="mdi mdi-eye"></i></a>
                    <a href="'.route("indicadores.edit", $indicador->id).'" class="btn btn-sm action-btn btn-inverse-primary" title="Editar" data-original-title="Editar">
                    <i class="mdi mdi-lead-pencil"></i></a>
                    <button type="button" class="btn btn-sm action-btn btn-inverse-danger" data-toggle="modal" data-target="#modal-delete" title="Eliminar" data-original-title="Eliminar" data-id="'.$indicador->id.'" data-nombre="'.$indicador->nombre.'">
                        <i class="mdi mdi-delete"></i>
                    </button>';
            })
            ->rawColumns(['actions', 'activo'])
            ->toJson();
    }
    // END DATATABLE

    public function index($id){
        $empresa = Empresa::FindOrFail($id);

        return view('componentes.indicadores.index', compact('empresa'));
    }

    public function create($id){
        $empresa = Empresa::FindOrFail($id);

        return view('componentes.indicadores.create', compact('empresa'));
    }

    public function cargar_datos_objeto(Request $request){
        $error = false;
        $message = "";
        $theme = "";
        $type = "";
        $collection = collect([]);
        $objeto = $request->objeto;
        $idMapa = $request->mapa;

        if($idMapa){
            if($objeto == TipoObjeto::PROCESO){
                $datos = MapaProceso::FindOrFail($idMapa)->proceso->where('activo', true);
            }else{
                if($objeto == TipoObjeto::SUBPROCESO){
                    $datos = Subproceso::join('procesos as pro', 'pro.id', 'subprocesos.proceso_id')
                        ->where([
                            ['pro.mapa_proceso_id', $idMapa],
                            ['pro.activo', true],
                            ['subprocesos.activo', true]
                        ])->select('subprocesos.*')->get();
                }else{
                    $error = true;
                    $message = '<span class="font-weight-bold"><i class="mdi mdi-bell"></i> No se han encontrado datos del tipo.</span>';
                    $theme = 'sunset';
                    $type = 'error';
                }
            }
            
            if($datos->count() > 0){
                foreach ($datos as $dato) {
                    $collection->push([
                        'id' => $dato->id,
                        'text' => $dato->nombre
                    ]);
                }
            }else{
                $error = true;
                $message = '<span class="font-weight-bold"><i class="mdi mdi-bell"></i> No se han encontrado datos del tipo.</span>';
                $theme = 'sunset';
                $type = 'warning';
            }
        }else{
            $error = true;
            $message = '<span class="font-weight-bold"><i class="mdi mdi-bell"></i> No se ha seleccionado el mapa de proceso.</span>';
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

    public function store(Request $request){
        $data = $request->all();

        if($request->formula == TipoFormula::COMPLEMENTO){
            $rules = [
                'mapa_proceso' => 'required',
                'tipo_objeto' => ['required', Rule::in(TipoObjeto::getValues())],
                'objeto_id' => 'required',
                'nombre' => 'required|string|max:255',
                'responsable' => 'required|string|max:255',
                'objetivo' => 'required',
                'descripcion' => 'required',
                'realizar' => 'required',
                'mecanismo' => 'required',
                'tolerancia' => 'required',
                'resultados' => 'required',
                'meta' => 'required',
                'iniciativa' => 'required',
                'frecuencia' => ['required', Rule::in(TipoFrecuencia::getValues())],
                'formula' => ['required', Rule::in(TipoFormula::getValues())],
                'tipo_condicion' => ['required', Rule::in(TipoCondicionIndicador::getValues())],
                'formula1_parametro1' => 'required|string|max:255',
                'formula1_parametro2' => 'required|string|max:255',
                'condicion_rojo' => 'required',
                'condicion_verde' => 'required',
            ];

            $message = [
                'mapa_proceso.required' => 'El mapa de proceso es obligatorio.',
                'tipo_objeto.required' => 'El tipo es obligatorio.',
                'objeto_id.required' => 'El proceso/subproceso es obligatorio.',
                'nombre.required' => 'El nombre es obligatorio.',
                'nombre.string' => 'El nombre debe ser una cadena de caracteres.',
                'nombre.max:255' => 'El nombre debe ser maximo de 255 caracteres.',
                'responsable.required' => 'El responsable es obligatorio.',
                'responsable.string' => 'El responsable debe ser una cadena de caracteres.',
                'responsable.max:255' => 'El responsable debe ser maximo de 255 caracteres.',
                'objetivo.required' => 'El objetivo es obligatorio.',
                'descripcion.required' => 'El campo es obligatorio.',
                'realizar.required' => 'El campo es obligatorio.',
                'mecanismo.required' => 'El campo es obligatorio.',
                'tolerancia.required' => 'El campo es obligatorio.',
                'resultados.required' => 'El campo es obligatorio.',
                'meta.required' => 'El campo es obligatorio.',
                'iniciativa.required' => 'El campo es obligatorio.',
                'frecuencia.required' => 'El frecuencia es obligatorio.',
                'formula.required' => 'La formula es obligatorio.',
                'tipo_condicion.required' => 'El tipo de condición es obligatorio.',
                'formula1_parametro1.required' => 'El primer parametro es obligatorio.',
                'formula1_parametro1.string' => 'El primer parametro debe ser una cadena de caracteres.',
                'formula1_parametro1.max:255' => 'El primer parametro debe ser maximo de 255 caracteres.',
                'formula1_parametro2.required' => 'El segundo parametro es obligatorio.',
                'formula1_parametro2.string' => 'El segundo parametro debe ser una cadena de caracteres.',
                'formula1_parametro2.max:255' => 'El segundo parametro debe ser maximo de 255 caracteres.',
                'condicion_rojo.required' => 'La condición es obligatorio.',
                'condicion_verde.required' => 'La condición es obligatorio.',
            ];
        }

        if($request->formula == TipoFormula::PORCENTUAL){
            $rules = [
                'mapa_proceso' => 'required',
                'tipo_objeto' => ['required', Rule::in(TipoObjeto::getValues())],
                'objeto_id' => 'required',
                'nombre' => 'required|string|max:255',
                'responsable' => 'required|string|max:255',
                'objetivo' => 'required',
                'descripcion' => 'required',
                'realizar' => 'required',
                'mecanismo' => 'required',
                'tolerancia' => 'required',
                'resultados' => 'required',
                'meta' => 'required',
                'iniciativa' => 'required',
                'frecuencia' => ['required', Rule::in(TipoFrecuencia::getValues())],
                'formula' => ['required', Rule::in(TipoFormula::getValues())],
                'tipo_condicion' => ['required', Rule::in(TipoCondicionIndicador::getValues())],
                'formula2_parametro1' => 'required|string|max:255',
                'formula2_parametro2' => 'required|string|max:255',
                'condicion_rojo' => 'required',
                'condicion_verde' => 'required',
            ];

            $message = [
                'mapa_proceso.required' => 'El mapa de proceso es obligatorio.',
                'tipo_objeto.required' => 'El tipo es obligatorio.',
                'objeto_id.required' => 'El proceso/subproceso es obligatorio.',
                'nombre.required' => 'El nombre es obligatorio.',
                'nombre.string' => 'El nombre debe ser una cadena de caracteres.',
                'nombre.max:255' => 'El nombre debe ser maximo de 255 caracteres.',
                'responsable.required' => 'El responsable es obligatorio.',
                'responsable.string' => 'El responsable debe ser una cadena de caracteres.',
                'responsable.max:255' => 'El responsable debe ser maximo de 255 caracteres.',
                'objetivo.required' => 'El objetivo es obligatorio.',
                'descripcion.required' => 'El campo es obligatorio.',
                'realizar.required' => 'El campo es obligatorio.',
                'mecanismo.required' => 'El campo es obligatorio.',
                'tolerancia.required' => 'El campo es obligatorio.',
                'resultados.required' => 'El campo es obligatorio.',
                'meta.required' => 'El campo es obligatorio.',
                'iniciativa.required' => 'El campo es obligatorio.',
                'frecuencia.required' => 'El frecuencia es obligatorio.',
                'formula.required' => 'La formula es obligatorio.',
                'tipo_condicion.required' => 'El tipo de condición es obligatorio.',
                'formula2_parametro1.required' => 'El primer parametro es obligatorio.',
                'formula2_parametro1.string' => 'El primer parametro debe ser una cadena de caracteres.',
                'formula2_parametro1.max:255' => 'El primer parametro debe ser maximo de 255 caracteres.',
                'formula2_parametro2.required' => 'El segundo parametro es obligatorio.',
                'formula2_parametro2.string' => 'El segundo parametro debe ser una cadena de caracteres.',
                'formula2_parametro2.max:255' => 'El segundo parametro debe ser maximo de 255 caracteres.',
                'condicion_rojo.required' => 'La condición es obligatorio.',
                'condicion_verde.required' => 'La condición es obligatorio.',
            ];
        }

        if($request->formula == TipoFormula::SUMA){
            $rules = [
                'mapa_proceso' => 'required',
                'tipo_objeto' => ['required', Rule::in(TipoObjeto::getValues())],
                'objeto_id' => 'required',
                'nombre' => 'required|string|max:255',
                'responsable' => 'required|string|max:255',
                'objetivo' => 'required',
                'descripcion' => 'required',
                'realizar' => 'required',
                'mecanismo' => 'required',
                'tolerancia' => 'required',
                'resultados' => 'required',
                'meta' => 'required',
                'iniciativa' => 'required',
                'frecuencia' => ['required', Rule::in(TipoFrecuencia::getValues())],
                'formula' => ['required', Rule::in(TipoFormula::getValues())],
                'tipo_condicion' => ['required', Rule::in(TipoCondicionIndicador::getValues())],
                'formula3_parametro1' => 'required|string|max:255',
                'condicion_rojo' => 'required',
                'condicion_verde' => 'required',
            ];

            $message = [
                'mapa_proceso.required' => 'El mapa de proceso es obligatorio.',
                'tipo_objeto.required' => 'El tipo es obligatorio.',
                'objeto_id.required' => 'El proceso/subproceso es obligatorio.',
                'nombre.required' => 'El nombre es obligatorio.',
                'nombre.string' => 'El nombre debe ser una cadena de caracteres.',
                'nombre.max:255' => 'El nombre debe ser maximo de 255 caracteres.',
                'responsable.required' => 'El responsable es obligatorio.',
                'responsable.string' => 'El responsable debe ser una cadena de caracteres.',
                'responsable.max:255' => 'El responsable debe ser maximo de 255 caracteres.',
                'objetivo.required' => 'El objetivo es obligatorio.',
                'descripcion.required' => 'El campo es obligatorio.',
                'realizar.required' => 'El campo es obligatorio.',
                'mecanismo.required' => 'El campo es obligatorio.',
                'tolerancia.required' => 'El campo es obligatorio.',
                'resultados.required' => 'El campo es obligatorio.',
                'meta.required' => 'El campo es obligatorio.',
                'iniciativa.required' => 'El campo es obligatorio.',
                'frecuencia.required' => 'El frecuencia es obligatorio.',
                'formula.required' => 'La formula es obligatorio.',
                'tipo_condicion.required' => 'El tipo de condición es obligatorio.',
                'formula3_parametro1.required' => 'El primer parametro es obligatorio.',
                'formula3_parametro1.string' => 'El primer parametro debe ser una cadena de caracteres.',
                'formula3_parametro1.max:255' => 'El primer parametro debe ser maximo de 255 caracteres.',
                'condicion_rojo.required' => 'La condición es obligatorio.',
                'condicion_verde.required' => 'La condición es obligatorio.',
            ];
        }

        Validator::make($data, $rules, $message)->validate();

        $rojo = $request->condicion_rojo;
        $verde = $request->condicion_verde;
        $meta = $request->meta;

        if($request->tipo_condicion == TipoCondicionIndicador::CONDICION_MENOR){
            $rojo++;
            if($rojo > $verde){
                $message = '<span class="font-weight-bold"><i class="mdi mdi-bell"></i> El semáforo verde debe ser mayor al menos en una unidad al semáforo rojo.</span>';
                $theme = 'sunset';
                $type = 'error';

                $notificacion = [
                    'message' => $message,
                    'theme' => $theme,
                    'type' => $type,
                ];

                return back()->withErrors([
                    'condicion_rojo' => 'El semáforo rojo debe ser menor al menos en una unidad al semáforo verde.',
                    'condicion_verde' => 'El semáforo verde debe ser mayor al menos en una unidad al semáforo rojo.'
                ])->withInput($request->all())
                ->with(['notificacion' => $notificacion]);
            }
            $rojo--;
            if($meta < $rojo){
                $message = '<span class="font-weight-bold"><i class="mdi mdi-bell"></i> La meta debe ser mayor o igual al valor del semáforo rojo.</span>';
                $theme = 'sunset';
                $type = 'error';

                $notificacion = [
                    'message' => $message,
                    'theme' => $theme,
                    'type' => $type,
                ];

                return back()->withErrors([
                    'meta' => 'La meta debe ser mayor o igual al valor del semáforo rojo.',
                ])->withInput($request->all())
                ->with(['notificacion' => $notificacion]);
            }
        }else{
            $verde++;
            if($verde > $rojo){
                $message = '<span class="font-weight-bold"><i class="mdi mdi-bell"></i> El semáforo rojo debe ser mayor al menos en una unidad al semáforo verde.</span>';
                $theme = 'sunset';
                $type = 'error';

                $notificacion = [
                    'message' => $message,
                    'theme' => $theme,
                    'type' => $type,
                ];

                return back()->withErrors([
                    'condicion_rojo' => 'El semáforo rojo debe ser mayor al menos en una unidad al semáforo verde.',
                    'condicion_verde' => 'El semáforo verde debe ser menor al menos en una unidad al semáforo rojo.'
                ])->withInput($request->all())
                ->with(['notificacion' => $notificacion]);
            }
            if($meta > $rojo){
                $message = '<span class="font-weight-bold"><i class="mdi mdi-bell"></i> La meta debe ser menor o igual al valor del semáforo rojo.</span>';
                $theme = 'sunset';
                $type = 'error';

                $notificacion = [
                    'message' => $message,
                    'theme' => $theme,
                    'type' => $type,
                ];

                return back()->withErrors([
                    'meta' => 'La meta debe ser menor o igual al valor del semáforo rojo.',
                ])->withInput($request->all())
                ->with(['notificacion' => $notificacion]);
            }
        }

        $indicador = new Indicador();
        $indicador->mapa_proceso_id = $request->mapa_proceso;
        $indicador->objeto_tipo = $request->tipo_objeto;
        $indicador->objeto_id = $request->objeto_id;
        $indicador->nombre = $request->nombre;
        $indicador->responsable = $request->responsable;
        $indicador->objetivo = $request->objetivo;
        $indicador->descripcion = $request->descripcion;
        $indicador->realizar = $request->realizar;
        $indicador->mecanismo = $request->mecanismo;
        $indicador->tolerancia = $request->tolerancia;
        $indicador->resultados = $request->resultados;
        $indicador->meta = $request->meta;
        $indicador->iniciativa = $request->iniciativa;
        $indicador->frecuencia = $request->frecuencia;
        $indicador->formula = $request->formula;
        $indicador->tipo_condicion = $request->tipo_condicion;
        $indicador->condicion_rojo = $request->condicion_rojo;
        $indicador->condicion_verde = $request->condicion_verde;
        if($request->formula == TipoFormula::COMPLEMENTO){
            $indicador->primer_parametro = $request->formula1_parametro1;
            $indicador->segundo_parametro = $request->formula1_parametro2;
        }
        if($request->formula == TipoFormula::PORCENTUAL){
            $indicador->primer_parametro = $request->formula2_parametro1;
            $indicador->segundo_parametro = $request->formula2_parametro2;
        }
        if($request->formula == TipoFormula::SUMA){
            $indicador->primer_parametro = $request->formula3_parametro1;
        }
        $indicador->created_by = Auth::user()->id;
        $indicador->save();

        $message = '<span class="font-weight-bold"><i class="mdi mdi-bell"></i> Se ha registrado correctamente el indicador.</span>';
        $theme = 'sunset';
        $type = 'success';

        $notificacion = [
            'message' => $message,
            'theme' => $theme,
            'type' => $type,
        ];

        return redirect()->route('indicadores', $indicador->mapa_proceso->empresa_id)->with(['notificacion' => $notificacion]);
    }

    public function edit($id){
        $indicador = Indicador::FindOrFail($id);
        $empresa = Empresa::FindOrFail($indicador->mapa_proceso->empresa_id);

        return view('componentes.indicadores.edit', compact('indicador', 'empresa'));
    }

    public function update(Request $request, $id){
        $data = $request->all();

        if($request->formula == TipoFormula::COMPLEMENTO){
            $rules = [
                'mapa_proceso' => 'required',
                'tipo_objeto' => ['required', Rule::in(TipoObjeto::getValues())],
                'objeto_id' => 'required',
                'nombre' => 'required|string|max:255',
                'responsable' => 'required|string|max:255',
                'objetivo' => 'required',
                'descripcion' => 'required',
                'realizar' => 'required',
                'mecanismo' => 'required',
                'tolerancia' => 'required',
                'resultados' => 'required',
                'meta' => 'required',
                'iniciativa' => 'required',
                'frecuencia' => ['required', Rule::in(TipoFrecuencia::getValues())],
                'formula' => ['required', Rule::in(TipoFormula::getValues())],
                'tipo_condicion' => ['required', Rule::in(TipoCondicionIndicador::getValues())],
                'formula1_parametro1' => 'required|string|max:255',
                'formula1_parametro2' => 'required|string|max:255',
                'condicion_rojo' => 'required',
                'condicion_verde' => 'required',
            ];

            $message = [
                'mapa_proceso.required' => 'El mapa de proceso es obligatorio.',
                'tipo_objeto.required' => 'El tipo es obligatorio.',
                'objeto_id.required' => 'El proceso/subproceso es obligatorio.',
                'nombre.required' => 'El nombre es obligatorio.',
                'nombre.string' => 'El nombre debe ser una cadena de caracteres.',
                'nombre.max:255' => 'El nombre debe ser maximo de 255 caracteres.',
                'responsable.required' => 'El responsable es obligatorio.',
                'responsable.string' => 'El responsable debe ser una cadena de caracteres.',
                'responsable.max:255' => 'El responsable debe ser maximo de 255 caracteres.',
                'objetivo.required' => 'El objetivo es obligatorio.',
                'descripcion.required' => 'El campo es obligatorio.',
                'realizar.required' => 'El campo es obligatorio.',
                'mecanismo.required' => 'El campo es obligatorio.',
                'tolerancia.required' => 'El campo es obligatorio.',
                'resultados.required' => 'El campo es obligatorio.',
                'meta.required' => 'El campo es obligatorio.',
                'iniciativa.required' => 'El campo es obligatorio.',
                'frecuencia.required' => 'El frecuencia es obligatorio.',
                'formula.required' => 'La formula es obligatorio.',
                'tipo_condicion.required' => 'El tipo de condición es obligatorio.',
                'formula1_parametro1.required' => 'El primer parametro es obligatorio.',
                'formula1_parametro1.string' => 'El primer parametro debe ser una cadena de caracteres.',
                'formula1_parametro1.max:255' => 'El primer parametro debe ser maximo de 255 caracteres.',
                'formula1_parametro2.required' => 'El segundo parametro es obligatorio.',
                'formula1_parametro2.string' => 'El segundo parametro debe ser una cadena de caracteres.',
                'formula1_parametro2.max:255' => 'El segundo parametro debe ser maximo de 255 caracteres.',
                'condicion_rojo.required' => 'La condición es obligatorio.',
                'condicion_verde.required' => 'La condición es obligatorio.',
            ];
        }

        if($request->formula == TipoFormula::PORCENTUAL){
            $rules = [
                'mapa_proceso' => 'required',
                'tipo_objeto' => ['required', Rule::in(TipoObjeto::getValues())],
                'objeto_id' => 'required',
                'nombre' => 'required|string|max:255',
                'responsable' => 'required|string|max:255',
                'objetivo' => 'required',
                'descripcion' => 'required',
                'realizar' => 'required',
                'mecanismo' => 'required',
                'tolerancia' => 'required',
                'resultados' => 'required',
                'meta' => 'required',
                'iniciativa' => 'required',
                'frecuencia' => ['required', Rule::in(TipoFrecuencia::getValues())],
                'formula' => ['required', Rule::in(TipoFormula::getValues())],
                'tipo_condicion' => ['required', Rule::in(TipoCondicionIndicador::getValues())],
                'formula2_parametro1' => 'required|string|max:255',
                'formula2_parametro2' => 'required|string|max:255',
                'condicion_rojo' => 'required',
                'condicion_verde' => 'required',
            ];

            $message = [
                'mapa_proceso.required' => 'El mapa de proceso es obligatorio.',
                'tipo_objeto.required' => 'El tipo es obligatorio.',
                'objeto_id.required' => 'El proceso/subproceso es obligatorio.',
                'nombre.required' => 'El nombre es obligatorio.',
                'nombre.string' => 'El nombre debe ser una cadena de caracteres.',
                'nombre.max:255' => 'El nombre debe ser maximo de 255 caracteres.',
                'responsable.required' => 'El responsable es obligatorio.',
                'responsable.string' => 'El responsable debe ser una cadena de caracteres.',
                'responsable.max:255' => 'El responsable debe ser maximo de 255 caracteres.',
                'objetivo.required' => 'El objetivo es obligatorio.',
                'descripcion.required' => 'El campo es obligatorio.',
                'realizar.required' => 'El campo es obligatorio.',
                'mecanismo.required' => 'El campo es obligatorio.',
                'tolerancia.required' => 'El campo es obligatorio.',
                'resultados.required' => 'El campo es obligatorio.',
                'meta.required' => 'El campo es obligatorio.',
                'iniciativa.required' => 'El campo es obligatorio.',
                'frecuencia.required' => 'El frecuencia es obligatorio.',
                'formula.required' => 'La formula es obligatorio.',
                'tipo_condicion.required' => 'El tipo de condición es obligatorio.',
                'formula2_parametro1.required' => 'El primer parametro es obligatorio.',
                'formula2_parametro1.string' => 'El primer parametro debe ser una cadena de caracteres.',
                'formula2_parametro1.max:255' => 'El primer parametro debe ser maximo de 255 caracteres.',
                'formula2_parametro2.required' => 'El segundo parametro es obligatorio.',
                'formula2_parametro2.string' => 'El segundo parametro debe ser una cadena de caracteres.',
                'formula2_parametro2.max:255' => 'El segundo parametro debe ser maximo de 255 caracteres.',
                'condicion_rojo.required' => 'La condición es obligatorio.',
                'condicion_verde.required' => 'La condición es obligatorio.',
            ];
        }

        if($request->formula == TipoFormula::SUMA){
            $rules = [
                'mapa_proceso' => 'required',
                'tipo_objeto' => ['required', Rule::in(TipoObjeto::getValues())],
                'objeto_id' => 'required',
                'nombre' => 'required|string|max:255',
                'responsable' => 'required|string|max:255',
                'objetivo' => 'required',
                'descripcion' => 'required',
                'realizar' => 'required',
                'mecanismo' => 'required',
                'tolerancia' => 'required',
                'resultados' => 'required',
                'meta' => 'required',
                'iniciativa' => 'required',
                'frecuencia' => ['required', Rule::in(TipoFrecuencia::getValues())],
                'formula' => ['required', Rule::in(TipoFormula::getValues())],
                'tipo_condicion' => ['required', Rule::in(TipoCondicionIndicador::getValues())],
                'formula3_parametro1' => 'required|string|max:255',
                'condicion_rojo' => 'required',
                'condicion_verde' => 'required',
            ];

            $message = [
                'mapa_proceso.required' => 'El mapa de proceso es obligatorio.',
                'tipo_objeto.required' => 'El tipo es obligatorio.',
                'objeto_id.required' => 'El proceso/subproceso es obligatorio.',
                'nombre.required' => 'El nombre es obligatorio.',
                'nombre.string' => 'El nombre debe ser una cadena de caracteres.',
                'nombre.max:255' => 'El nombre debe ser maximo de 255 caracteres.',
                'responsable.required' => 'El responsable es obligatorio.',
                'responsable.string' => 'El responsable debe ser una cadena de caracteres.',
                'responsable.max:255' => 'El responsable debe ser maximo de 255 caracteres.',
                'objetivo.required' => 'El objetivo es obligatorio.',
                'descripcion.required' => 'El campo es obligatorio.',
                'realizar.required' => 'El campo es obligatorio.',
                'mecanismo.required' => 'El campo es obligatorio.',
                'tolerancia.required' => 'El campo es obligatorio.',
                'resultados.required' => 'El campo es obligatorio.',
                'meta.required' => 'El campo es obligatorio.',
                'iniciativa.required' => 'El campo es obligatorio.',
                'frecuencia.required' => 'El frecuencia es obligatorio.',
                'formula.required' => 'La formula es obligatorio.',
                'tipo_condicion.required' => 'El tipo de condición es obligatorio.',
                'formula3_parametro1.required' => 'El primer parametro es obligatorio.',
                'formula3_parametro1.string' => 'El primer parametro debe ser una cadena de caracteres.',
                'formula3_parametro1.max:255' => 'El primer parametro debe ser maximo de 255 caracteres.',
                'condicion_rojo.required' => 'La condición es obligatorio.',
                'condicion_verde.required' => 'La condición es obligatorio.',
            ];
        }

        Validator::make($data, $rules, $message)->validate();

        $rojo = $request->condicion_rojo;
        $verde = $request->condicion_verde;
        $meta = $request->meta;

        if($request->tipo_condicion == TipoCondicionIndicador::CONDICION_MENOR){
            $rojo++;
            if($rojo > $verde){
                $message = '<span class="font-weight-bold"><i class="mdi mdi-bell"></i> El semáforo verde debe ser mayor al menos en una unidad al semáforo rojo.</span>';
                $theme = 'sunset';
                $type = 'error';

                $notificacion = [
                    'message' => $message,
                    'theme' => $theme,
                    'type' => $type,
                ];

                return back()->withErrors([
                    'condicion_rojo' => 'El semáforo rojo debe ser menor al menos en una unidad al semáforo verde.',
                    'condicion_verde' => 'El semáforo verde debe ser mayor al menos en una unidad al semáforo rojo.'
                ])->withInput($request->all())
                ->with(['notificacion' => $notificacion]);
            }
            $rojo--;
            if($meta < $rojo){
                $message = '<span class="font-weight-bold"><i class="mdi mdi-bell"></i> La meta debe ser mayor o igual al valor del semáforo rojo.</span>';
                $theme = 'sunset';
                $type = 'error';

                $notificacion = [
                    'message' => $message,
                    'theme' => $theme,
                    'type' => $type,
                ];

                return back()->withErrors([
                    'meta' => 'La meta debe ser mayor o igual al valor del semáforo rojo.',
                ])->withInput($request->all())
                ->with(['notificacion' => $notificacion]);
            }
        }else{
            $verde++;
            if($verde > $rojo){
                $message = '<span class="font-weight-bold"><i class="mdi mdi-bell"></i> El semáforo rojo debe ser mayor al menos en una unidad al semáforo verde.</span>';
                $theme = 'sunset';
                $type = 'error';

                $notificacion = [
                    'message' => $message,
                    'theme' => $theme,
                    'type' => $type,
                ];

                return back()->withErrors([
                    'condicion_rojo' => 'El semáforo rojo debe ser mayor al menos en una unidad al semáforo verde.',
                    'condicion_verde' => 'El semáforo verde debe ser menor al menos en una unidad al semáforo rojo.'
                ])->withInput($request->all())
                ->with(['notificacion' => $notificacion]);
            }
            if($meta > $rojo){
                $message = '<span class="font-weight-bold"><i class="mdi mdi-bell"></i> La meta debe ser menor o igual al valor del semáforo rojo.</span>';
                $theme = 'sunset';
                $type = 'error';

                $notificacion = [
                    'message' => $message,
                    'theme' => $theme,
                    'type' => $type,
                ];

                return back()->withErrors([
                    'meta' => 'La meta debe ser menor o igual al valor del semáforo rojo.',
                ])->withInput($request->all())
                ->with(['notificacion' => $notificacion]);
            }
        }

        $indicador = Indicador::FindOrFail($id);
        $indicador->mapa_proceso_id = $request->mapa_proceso;
        $indicador->objeto_tipo = $request->tipo_objeto;
        $indicador->objeto_id = $request->objeto_id;
        $indicador->nombre = $request->nombre;
        $indicador->responsable = $request->responsable;
        $indicador->objetivo = $request->objetivo;
        $indicador->descripcion = $request->descripcion;
        $indicador->realizar = $request->realizar;
        $indicador->mecanismo = $request->mecanismo;
        $indicador->tolerancia = $request->tolerancia;
        $indicador->resultados = $request->resultados;
        $indicador->meta = $request->meta;
        $indicador->iniciativa = $request->iniciativa;
        if($indicador->data_fuente->where('activo', true)->count() == 0) $indicador->frecuencia = $request->frecuencia;
        $indicador->formula = $request->formula;
        $indicador->tipo_condicion = $request->tipo_condicion;
        $indicador->condicion_rojo = $request->condicion_rojo;
        $indicador->condicion_verde = $request->condicion_verde;
        if($request->formula == TipoFormula::COMPLEMENTO){
            $indicador->primer_parametro = $request->formula1_parametro1;
            $indicador->segundo_parametro = $request->formula1_parametro2;
        }
        if($request->formula == TipoFormula::PORCENTUAL){
            $indicador->primer_parametro = $request->formula2_parametro1;
            $indicador->segundo_parametro = $request->formula2_parametro2;
        }
        if($request->formula == TipoFormula::SUMA){
            $indicador->primer_parametro = $request->formula3_parametro1;
        }
        $indicador->updated_by = Auth::user()->id;
        $indicador->update();

        $message = '<span class="font-weight-bold"><i class="mdi mdi-bell"></i> Se ha modificado correctamente el indicador.</span>';
        $theme = 'sunset';
        $type = 'success';

        $notificacion = [
            'message' => $message,
            'theme' => $theme,
            'type' => $type,
        ];

        return redirect()->route('indicadores.edit', $indicador->id)->with(['notificacion' => $notificacion]);
    }

    public function show($id){
        $indicador = Indicador::FindOrFail($id);
        $empresa = Empresa::FindOrFail($indicador->mapa_proceso->empresa_id);

        return view('componentes.indicadores.show', compact('indicador', 'empresa'));
    }

    public function delete(Request $request, $id){
        $error = false;
        $message = "";
        $collection = collect([]);

        $verificar = Indicador::FindOrFail($id)->data_fuente;
        if($verificar->count() > 0){
            $error = true;
            $message = '<span class="font-weight-bold"><i class="mdi mdi-bell"></i> El indicador no se ha podido eliminar, esta siendo usado.</span>';
            $theme = 'sunset';
            $type = 'error';
        }

        if(!$error){
            $indicador = Indicador::FindOrFail($id);
            $indicador->activo = false;
            $indicador->deleted_by = Auth::user()->id;
            $indicador->updated_by = Auth::user()->id;
            $indicador->deleted_at = Carbon::now();
            $indicador->update();

            $message = '<span class="font-weight-bold"><i class="mdi mdi-bell"></i> Se ha eliminado correctamente el indicador.</span>';
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
}
