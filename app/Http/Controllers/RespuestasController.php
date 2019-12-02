<?php
namespace App\Http\Controllers;

use Validator;
use App\models\Catalogo;
use App\models\Respuesta;
use Illuminate\Http\Request;
use App\models\CriterioAprobacion;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\models\CriterioAspectoClave;

class RespuestasController extends Controller {

	public function index(Request $request)
	{
		$evaluacion_id=$request->query('evaluacion_id','');
		$concursocriterio_id=$request->query('concursocriterio_id','');

		$evaluador_id=$request->query('evaluador_id','');
		$tipoetapa_id=$request->query('tipoetapa_id','');
		$inscripcion_id=$request->query('inscripcion_id','');

		$entities = Respuesta::select(DB::raw("respuesta.*, concat(cr.nombres,' ',cr.apellidos) as user_created, concat(upd.nombres,' ',upd.apellidos) as user_updated"))->with('areaanalisis')
		->leftJoin('evaluador as cr','cr.id','=','respuesta.evaluador_created')
		->leftJoin('evaluador as upd','upd.id','=','respuesta.evaluador_updated')
		->with('pregunta')
		->with('creado_por')
		->with('actualizado_por')
		->where(function($q) use($evaluacion_id,$concursocriterio_id,$evaluador_id,$tipoetapa_id,$inscripcion_id){
			if($evaluacion_id!=''){
				$q=$q->where('evaluacion_id','=',$evaluacion_id);
			}
			if($concursocriterio_id!=''){
				$q=$q->where('concursocriterio_id','=',$concursocriterio_id);
			}
			/*if($evaluador_id!=''){
				$q=$q->where('evaluacion.evaluador_id','=',$evaluador_id);
			}
			if($tipoetapa_id!=''){
				$q=$q->where('evaluacion.tipoetapa_id','=',$tipoetapa_id);
			}
			if($inscripcion_id!=''){
				$q=$q->where('evaluacion.inscripcion_id','=',$inscripcion_id);
			}*/

			return $q;
		})->get();

		return Response()->json($entities,200);
	}

	public function store(Request $request)
	{
		$entity=null;
		$msg='';
		$success=false;
		$validator = Validator::make($data = $request->all(), Respuesta::$rules);

		if (!$validator->fails())
		{
			$entity=Respuesta::create($data);
			$success=true;

			CriterioAprobacion::where('evaluacion_id','=',$entity->evaluacion_id)
			->where('concursocriterio_id','=', $entity->concursocriterio_id)
            ->delete();

			$aspectoclave_id=$request->query('aspectoclave_id',0);
			if($aspectoclave_id!=0){
				$criterioaspectoclave=CriterioAspectoClave::where('aspectoclave_id','=',$aspectoclave_id)
					->where('evaluacion_id','=',$request->query('evaluacion_id'))
					->first();
				if(!$criterioaspectoclave){
					$criterioaspectoclave=new CriterioAspectoClave();
					$criterioaspectoclave->evaluacion_id=$request->query('evaluacion_id');
					$criterioaspectoclave->concursocriterio_id=$request->query('concursocriterio_id');
					$criterioaspectoclave->aspectoclave_id=$request->query('aspectoclave_id');
					$criterioaspectoclave->estado=true;
					$criterioaspectoclave->save();
				}
			}
		}

		return Response()->json(array('success'=>$success,'message'=>$msg,'entity'=>$entity), 201);
	}

	public function show($id)
	{
		$respuesta = Respuesta::findOrFail($id);

		return View::make('respuestas.show', compact('respuesta'));
	}

	public function update(Request $request, $id)
	{
		$success=false;
		$msg='';
		$entity = Respuesta::findOrFail($id);

		$validator = Validator::make($data = $request->all(), Respuesta::$rules);

		if (!$validator->fails())
		{
			$entity->update($data);
			$success=true;

			CriterioAprobacion::where('evaluacion_id','=',$entity->evaluacion_id)
			->where('concursocriterio_id','=', $entity->concursocriterio_id)
            ->delete();
		}

		return Response()->json(array('success'=>$success,'message'=>$msg,'entity'=>$entity->toArray()), 201);
	}

	public function destroy($id)
	{
		Respuesta::destroy($id);
		$success=true;

		return Response()->json(array('success'=>$success));
	}

}
