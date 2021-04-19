<?php

namespace App\Http\Controllers\Componentes;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use App\Modelos\Componentes\Estrategia;
use App\Modelos\Componentes\Proceso;
use App\Modelos\Componentes\Subproceso;
use App\Modelos\Gestion\Empresa;
use App\Enums\TipoEstrategia;
use App\Enums\TipoObjeto;
use App\Enums\TipoProceso;
use Carbon\Carbon;

class EstrategiaController extends Controller
{
    public function __construct(){
        $this->middleware('auth');
    }

    // DATATABLES
    function datatable_estrategias($id){
        $estrategias = Estrategia::join('mapa_procesos as mapa', 'mapa.id', 'estrategias.mapa_proceso_id')
            ->where([
                ['mapa.empresa_id', $id],
                ['estrategias.activo', true]
            ])->select('estrategias.*')->get();

        return Datatables()->of($estrategias)
            ->editColumn('tipo_estrategia', function(Estrategia $estrategia){
                return TipoEstrategia::getDescription($estrategia->tipo_estrategia);
            })
            ->editColumn('mapa_proceso_id', function(Estrategia $estrategia){
                if($estrategia->objeto_tipo == TipoObjeto::PROCESO) return 'PROCESO: '.$estrategia->proceso->nombre.' ('.$estrategia->mapa_proceso->nombre.')'.'
                <a href="'.route("estrategias.show", ['tipo' => 0, 'id' => $estrategia->proceso->id]).'" class="btn btn-sm action-btn btn-inverse-warning" title="Mapa Estrategico" data-original-title="Mapa Estrategico">
                    <i class="mdi mdi-eye"></i></a>';
                return 'SUBPROCESO: '.$estrategia->subproceso->nombre.' ('.$estrategia->mapa_proceso->nombre.')'.'
                <a href="'.route("estrategias.show", ['tipo' => 1, 'id' => $estrategia->subproceso->id]).'" class="btn btn-sm action-btn btn-inverse-warning" title="Mapa Estrategico" data-original-title="Mapa Estrategico">
                    <i class="mdi mdi-eye"></i></a>';
            })
            ->editColumn('ligado', function(Estrategia $estrategia){
                if($estrategia->ligado) return '<span class="badge badge-warning">LIGADO</span>';
                else return '<span class="badge badge-secondary">NO LIGADO</span>';
            })
            ->addColumn('actions', function(Estrategia $estrategia){
                return '<a href="'.route("estrategias.edit", $estrategia->id).'" class="btn btn-sm action-btn btn-inverse-primary" title="Editar" data-original-title="Editar">
                    <i class="mdi mdi-lead-pencil"></i></a>
                    <button type="button" class="btn btn-sm action-btn btn-inverse-danger" data-toggle="modal" data-target="#modal-delete" title="Eliminar" data-original-title="Eliminar" data-id="'.$estrategia->id.'" data-nombre="'.$estrategia->nombre.'">
                        <i class="mdi mdi-delete"></i>
                    </button>';
            })
            ->rawColumns(['actions', 'mapa_proceso_id', 'ligado'])
            ->toJson();
    }
    // END DATATABLE

    public function index($id){
        $empresa = Empresa::FindOrFail($id);

        return view('componentes.mapa-estrategico.index', compact('empresa'));
    }

    public function create($id){
        $empresa = Empresa::FindOrFail($id);

        return view('componentes.mapa-estrategico.create', compact('empresa'));
    }

    public function store(Request $request){
        $data = $request->all();

        $rules = [
            'mapa_proceso' => 'required',
            'tipo_objeto' => ['required', Rule::in(TipoObjeto::getValues())],
            'objeto_id' => 'required',
            'nombre' => 'required|string|max:255',
            'tipo_estrategia' => ['required', Rule::in(TipoEstrategia::getValues())],
        ];

        $message = [
            'mapa_proceso.required' => 'El mapa de proceso es obligatorio.',
            'tipo_objeto.required' => 'El tipo es obligatorio.',
            'objeto_id.required' => 'El proceso/subproceso es obligatorio.',
            'nombre.required' => 'El nombre es obligatorio.',
            'nombre.string' => 'El nombre debe ser una cadena de caracteres.',
            'nombre.max:255' => 'El nombre debe ser maximo de 255 caracteres.',
            'tipo_estrategia.required' => 'El tipo de estratégia es obligatorio.',
        ];

        Validator::make($data, $rules, $message)->validate();
        
        $estrategia = new Estrategia();
        $estrategia->mapa_proceso_id = $request->mapa_proceso;
        $estrategia->objeto_tipo = $request->tipo_objeto;
        $estrategia->objeto_id = $request->objeto_id;
        $estrategia->tipo_estrategia = $request->tipo_estrategia;
        $estrategia->nombre = $request->nombre;
        if($request->ligado == 'on') $estrategia->ligado = true;
        else $estrategia->ligado = false;
        $estrategia->created_by = Auth::user()->id;
        $estrategia->save();
        
        $message = '<span class="font-weight-bold"><i class="mdi mdi-bell"></i> Se ha registrado correctamente la estratégia.</span>';
        $theme = 'sunset';
        $type = 'success';

        $notificacion = [
            'message' => $message,
            'theme' => $theme,
            'type' => $type,
        ];

        return redirect()->route('estrategias', $estrategia->mapa_proceso->empresa_id)->with(['notificacion' => $notificacion]);
    }

    public function edit($id){
        $estrategia = Estrategia::FindOrFail($id);
        $empresa = Empresa::FindOrFail($estrategia->mapa_proceso->empresa_id);

        return view('componentes.mapa-estrategico.edit', compact('empresa', 'estrategia'));
    }

    public function update(Request $request, $id){
        $data = $request->all();

        $rules = [
            'mapa_proceso' => 'required',
            'tipo_objeto' => ['required', Rule::in(TipoObjeto::getValues())],
            'objeto_id' => 'required',
            'nombre' => 'required|string|max:255',
            'tipo_estrategia' => ['required', Rule::in(TipoEstrategia::getValues())],
        ];

        $message = [
            'mapa_proceso.required' => 'El mapa de proceso es obligatorio.',
            'tipo_objeto.required' => 'El tipo es obligatorio.',
            'objeto_id.required' => 'El proceso/subproceso es obligatorio.',
            'nombre.required' => 'El nombre es obligatorio.',
            'nombre.string' => 'El nombre debe ser una cadena de caracteres.',
            'nombre.max:255' => 'El nombre debe ser maximo de 255 caracteres.',
            'tipo_estrategia.required' => 'El tipo de estratégia es obligatorio.',
        ];

        Validator::make($data, $rules, $message)->validate();
        
        $estrategia = Estrategia::FindOrFail($id);
        $estrategia->mapa_proceso_id = $request->mapa_proceso;
        $estrategia->objeto_tipo = $request->tipo_objeto;
        $estrategia->objeto_id = $request->objeto_id;
        $estrategia->tipo_estrategia = $request->tipo_estrategia;
        if($request->ligado == 'on') $estrategia->ligado = true;
        else $estrategia->ligado = false;
        $estrategia->nombre = $request->nombre;
        $estrategia->updated_by = Auth::user()->id;
        $estrategia->update();
        
        $message = '<span class="font-weight-bold"><i class="mdi mdi-bell"></i> Se ha actualizado correctamente la estratégia.</span>';
        $theme = 'sunset';
        $type = 'success';

        $notificacion = [
            'message' => $message,
            'theme' => $theme,
            'type' => $type,
        ];

        return redirect()->route('estrategias', $estrategia->mapa_proceso->empresa_id)->with(['notificacion' => $notificacion]);
    }

    public function show($tipo, $id){
        if($tipo == 0){
            $objeto = Proceso::FindOrFail($id);
            $empresa = Empresa::FindOrFail($objeto->mapa_proceso->empresa_id);
        }else{
            if($tipo == 1){
                $objeto = Subproceso::FindOrFail($id);
                $empresa = Empresa::FindOrFail($objeto->proceso->mapa_proceso->empresa_id);
            }else{
                abort(404);
            }
        }

        return view('componentes.mapa-estrategico.show', compact('objeto', 'empresa'));
    }

    public function delete(Request $request, $id){
        $error = false;
        $message = "";
        $collection = collect([]);

        if(!$error){
            $estrategia = Estrategia::FindOrFail($id);
            $estrategia->activo = false;
            $estrategia->deleted_by = Auth::user()->id;
            $estrategia->updated_by = Auth::user()->id;
            $estrategia->deleted_at = Carbon::now();
            $estrategia->update();

            $message = '<span class="font-weight-bold"><i class="mdi mdi-bell"></i> Se ha eliminado correctamente la estratégia.</span>';
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
