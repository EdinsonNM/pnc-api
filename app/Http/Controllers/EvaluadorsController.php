<?php
namespace App\Http\Controllers;

use Validator;
use App\models\Evaluador;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\models\InscripcionEvaluador;

class EvaluadorsController extends Controller {


	public function index(Request $request)
	{
		$onlyactive=$request->query('onlyactive','false');
		$onlyactive=($onlyactive=='true')?true:false;
		$ids=array();
		if($onlyactive){
			$ids=$this->OnlyActive();
		}
		$evaluadors = Evaluador::where(function($q) use($ids){
			if(count($ids)>0){
				$q=$q->whereIn('id',$ids);
			}
			return $q;
		})->orderBy('nombres','asc')->get();

		return Response()->json($evaluadors, 200);
	}

	public function OnlyActive()
	{
		$entities=InscripcionEvaluador::select("evaluador.*")
            ->join('evaluador','evaluador.id','=','inscripcionevaluador.evaluador_id')
            ->join('concurso','concurso.id','=','inscripcionevaluador.concurso_id')
            ->where('concurso.estado','=','1')
            ->distinct()
            ->get();
        $ids=array();
        foreach ($entities as $entity) {
            $ids[]= $entity->id;
        }
        //var_dump($ids);die();
        return $ids;

	}

	public function store(Request $request)
	{
		$entity=null;
		$msg='';
		$success=false;
		$validator = Validator::make($data = $request->all(), Evaluador::$rules);

		if (!$validator->fails())
		{
			$entity=Evaluador::create($data);
			$success=true;
		}

		return Response()->json(array('success'=>$success,'message'=>$msg,'entity'=>$entity), 201);
	}


	public function show($id)
	{
		$evaluador = Evaluador::with('usuario')
		->with('disponibilidades')
		->with('sexo')
		->findOrFail($id);

		return Response()->json($evaluador, 200);
	}


	public function update(Request $request, $id)
	{
		$success=false;
		$msg='';
		$entity = Evaluador::findOrFail($id);

		$validator = Validator::make($data = $request->all(), Evaluador::$rules);

		if (!$validator->fails())
		{
			$entity->update($data);
			$success=true;
		}

		return Response()->json(array('success'=>$success,'message'=>$msg,'entity'=>$entity->toArray()), 201);
	}

	public function destroy($id)
	{
		Evaluador::destroy($id);

		return Redirect::route('evaluadors.index');
	}

	public function History(Request $request){
		$id=$request->query('evaluador_id','');
		$entitites =array();
		if($id!=''){
			$inscripciones=InscripcionEvaluador::where('evaluador_id','=',$id)->get();
			$idInscripciones=array();
			foreach ($inscripciones as $inscripcion) {
				$idInscripciones[]=$inscripcion->id;
			}

			if(count($idInscripciones)>0){
				$entitites = GrupoEvaluacionEvaluador::with(array('inscripcion'=>function($q){
					return $q->with('concurso');
				}))
				->with('grupoevaluacion')
				->whereIn('inscripcion_id',$idInscripciones)
				->paginate($request->query('count'));
			}
		}

		return Response()->json($entitites,200);

	}

    public function reportExcel(Request $request){
		$estado=$request->query('estado','');
		$tipoconcurso_id=$request->query('tipoconcurso_id','');

        $entities=InscripcionEvaluador::select(DB::raw("evaluador.*
        	,group_concat(DISTINCT conflictointeresevaluador.razonsocial) as conflictos
        	,group_concat(DISTINCT  concat(ch.nombreconcurso,IF(evaluadorhistorial.lider = '1', ' (lider)','')) ORDER BY ch.nombreconcurso ASC) as historial"))
            ->join('evaluador','evaluador.id','=','inscripcionevaluador.evaluador_id')
            ->join('concurso','concurso.id','=','inscripcionevaluador.concurso_id')

			->leftjoin('evaluadorhistorial','evaluadorhistorial.evaluador_id','=','evaluador.id')
			->leftjoin('concurso as ch','ch.id','=','evaluadorhistorial.concurso_id')
			->leftjoin('conflictointeresevaluador','evaluador.id','=','conflictointeresevaluador.evaluador_id')
            ->where(function($q) use($estado,$tipoconcurso_id){
                if($estado!=''){
                    $q=$q->where('concurso.estado','=',$estado);
                }
                if($tipoconcurso_id!=''){
                	$q=$q->where('concurso.tipoconcurso_id','=',$tipoconcurso_id);
                }
                return $q;
            })

            ->orderBy('evaluador.apellidos','asc')
            ->orderBy('evaluador.nombres','asc')
            ->groupBy('evaluador.id')
            ->get();

        $statusCode=200;
        //return Response()->json($entities,200);
        $contents = View::make('reports.evaluadores')
	        ->with('title', 'Listado de Evaluadores')
	        ->with('entities',$entities);
        $response = Response::make($contents, $statusCode);
        $response->header('Content-Type', 'application/vnd.ms-excel;');
        $response->header('Content-Disposition', 'attachment; filename="report.xls"');
        return $response;
    }

    public function reportFicha()
    {
         $evaluador=Evaluador::with('usuario')
             ->with('sexo')
             ->find($request->query('id',0));

         $contents = View::make('reports.evaluador')
	        ->with('entity', $evaluador)->render();
	    //return Response()->json($contents, 200);
        return PDF::load($contents, 'A4', 'portrait')->download('ficha_evaluador');
    }

}
