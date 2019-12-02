<?php
namespace App\Http\Controllers;

use Validator;
use App\models\Catalogo;
use App\models\Respuesta;
use App\models\Evaluacion;
use App\models\Inscripcion;
use App\models\AspectoClave;
use Illuminate\Http\Request;
use App\models\CriterioVisita;
use App\models\CriterioInforme;
use App\models\ConcursoCriterio;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\models\CriterioAspectoClave;
use App\models\GrupoEvaluacionEvaluador;

class EvaluacionsController extends Controller {

	public function index(Request $request)
	{
		$evaluador_id=$request->query('evaluador_id','');
		$inscripcion_id=$request->query('inscripcion_id','');
		$tipoetapa_id=$request->query('tipoetapa_id','');
		$evaluacions = Evaluacion::where(function($q) use($evaluador_id,$inscripcion_id,$tipoetapa_id){
			if($evaluador_id!=''){
				$q=$q->where('evaluador_id','=',$evaluador_id);
			}
			if($inscripcion_id!=''){
				$q=$q->where('inscripcion_id','=',$inscripcion_id);
			}
			if($tipoetapa_id!=''){
				$q=$q->where('tipoetapa_id','=',$tipoetapa_id);
			}
			return $q;
		})->get();

		return Response()->json($evaluacions,200);
	}

	public function store(Request $request)
	{
		$entity=null;
		$success=false;
		$validator = Validator::make($data = Input::all(), Evaluacion::$rules);

		if (!$validator->fails())
		{
			$entity=Evaluacion::create($data);
			$success=true;
			$entity=$entity->toArray();
		}

		return Response()->json(array('success' => $success, 'entity'=>$entity), 201);
	}

	public function show($id)
	{
		$evaluacion = Evaluacion::findOrFail($id);

		return View::make('evaluacions.show', compact('evaluacion'));
	}

	public function update(Request $request, $id)
	{
		$evaluacion = Evaluacion::findOrFail($id);

		$validator = Validator::make($data = Input::all(), Evaluacion::$rules);

		if ($validator->fails())
		{
			return Redirect::back()->withErrors($validator)->withInput();
		}

		$evaluacion->update($data);

		return Redirect::route('evaluacions.index');
	}

	public function destroy($id)
	{
		Evaluacion::destroy($id);

		return Redirect::route('evaluacions.index');
	}

	public function CierreEvaluacion(Request $request)
	{
		$ETAPA_RETRO=18;
		$success=false;
		$evaluador_id=$request->query('evaluador_id','');
		$inscripcion_id=$request->query('inscripcion_id','');
		$tipoetapa_id=$request->query('tipoetapa_id','');
		$grupoevaluacion_id=$request->query('grupoevaluacion_id','');


		$evaluadores = GrupoEvaluacionEvaluador::with('inscripcion')->where('grupoevaluacion_id','=',$grupoevaluacion_id)->get();

		$evaluacion = Evaluacion::where(function($q) use($evaluador_id,$inscripcion_id,$tipoetapa_id){
			if($evaluador_id!=''){
				$q=$q->where('evaluador_id','=',$evaluador_id);
			}
			if($inscripcion_id!=''){
				$q=$q->where('inscripcion_id','=',$inscripcion_id);
			}
			if($tipoetapa_id!=''){
				$q=$q->where('tipoetapa_id','=',$tipoetapa_id);
			}
			return $q;
		})
		->first();
		//->get();

		if($evaluacion)
		{
			if($evaluacion->abierta)
			{
				$inscripcion=Inscripcion::find($evaluacion->inscripcion_id);
				if($inscripcion)
				{
					$criterios=ConcursoCriterio::where('concurso_id','=',$inscripcion->concurso_id)
						->where('tipocriterio_id','=',6)
						->get();
					$ids=array();
					foreach ($criterios as $criterio) {
						$ids[]=$criterio->id;
					}
					$evaluacion_id=$evaluacion->id;

					$subcriterios=ConcursoCriterio::select(DB::raw('concursocriterio.*,puntaje.valor'))->with(array('criterio_aprobaciones'=>function($q) use($evaluacion_id){
							return $q->where('evaluacion_id','=',$evaluacion_id);
						}))
						->leftjoin('puntaje',function($q) use($evaluacion_id){
							$q->on( 'concursocriterio.id', '=','puntaje.concursocriterio_id')
							->where('evaluacion_id','=',$evaluacion_id);
						})
						->where('concurso_id','=',$inscripcion->concurso_id)
						->whereIn('idpadre',$ids)

						->get();


					$result=array();

					foreach ($subcriterios as $subcriterio)
					{
						if(isset($subcriterio->valor))
						{
							if($subcriterio->valor<0){
								$result[]['message']=$subcriterio->codigo.' '.$subcriterio->descripcion.' - no contiene puntaje asignado';
							}else{
								$countEvaluadoresAprobacion=0;
								foreach ($evaluadores as $evaluador) {
									foreach ($subcriterio->criterio_aprobaciones as $aprobacion) {
										if(intval($evaluador->inscripcion->evaluador_id)==intval ($aprobacion->evaluador_id)){
											if($aprobacion->aprobado=='1'){
												$countEvaluadoresAprobacion++;
											}
										}
									}
								}
								if($countEvaluadoresAprobacion<count($evaluadores)){
									$result[]['message']=$subcriterio->codigo.' '.$subcriterio->descripcion.' - debe ser aprobado por todos los evaluadores';
								}

							}
						}else{
							if($tipoetapa_id!=$ETAPA_RETRO){
								$result[]['message']=$subcriterio->codigo.' '.$subcriterio->descripcion.' - no contiene puntaje asignado';
							}

						}
					}

					if(count($result)==0)
					{

						$evaluacion->abierta=false;
						$evaluacion->fechaCierre= DB::raw('NOW()');
						$evaluacion->save();
						$success=true;
					}
				}
			}else{
				$result[]['message']="Evaluación ya se encuentra cerrada";
			}

		}else{
			$result[]['message']="No se ha encontrado la evaluacion sobre la cual que desea realizar el cierre";
		}

		//$queries = DB::getQueryLog();
		//return Response()->json($queries, 200);
		return Response()->json(array('result'=>$result,'success'=>$success),200);
	}

	public function ImportDataEtapaAnterior(Request $request)
	{
		$tipoetapaanterior_id=$request->query('tipoetapaanterior_id','');

		$inscripcion_id=$request->query('inscripcion_id','');
		$tipoetapa_id=$request->query('tipoetapa_id','');
		$evaluacion_id=$request->query('evaluacion_id','');
		$success=false;
		$evaluacion=Evaluacion::find($evaluacion_id);
		if($evaluacion)
		{


			$aspectos=AspectoClave::select('aspectoclave.*')
			->join('evaluacion','evaluacion.id','=','aspectoclave.evaluacion_id')
			->where('evaluacion.inscripcion_id','=',$inscripcion_id)
			->where('evaluacion.tipoetapa_id','=',$tipoetapaanterior_id)
			->where('evaluacion.abierta','=',false)
			->where('estado','=',true)
			->get();

			CriterioAspectoClave::where('evaluacion_id','=',$evaluacion_id)->delete();
			AspectoClave::where('evaluacion_id','=',$evaluacion_id)->delete();

			CriterioInforme::where('evaluacion_id','=',$evaluacion_id)->delete();
	 		$maxId=AspectoClave::max('id');
	 		$data=array();
	 		$aspectosid=array();
			foreach ($aspectos as $item) {

				 $aspecto=new AspectoClave();
				 $aspecto->id=++$maxId;
				 $aspecto->concursocriterio_id=$item->concursocriterio_id;
				 $aspecto->descripcion=$item->descripcion;
				 $aspecto->estado=true;
				 $aspecto->evaluacion_id=$evaluacion_id;
				 $aspecto->evaluador_created=$item->evaluador_created;
				 $data[]=$aspecto->toArray();

				 $aspectosid[]=array('newid'=>$aspecto->id,'oldid'=>$item->id);


			}
			if(count($data)>0)
			{
				AspectoClave::insert($data);
			}

			//criterios aspectos claves
			if($tipoetapaanterior_id!=15){
				$criteriosaspectos=CriterioAspectoClave::select('criterioaspectoclave.*')
				->join('evaluacion','evaluacion.id','=','criterioaspectoclave.evaluacion_id')
				->where('evaluacion.inscripcion_id','=',$inscripcion_id)
				->where('evaluacion.tipoetapa_id','=',$tipoetapaanterior_id)
				->where('evaluacion.abierta','=',false)
				->where('estado','=',true)
				->get();
				$maxId=CriterioAspectoClave::max('id');
		 		$data=array();

				foreach ($criteriosaspectos as $item) {

					 $criterioaspecto=new CriterioAspectoClave();
					 $criterioaspecto->id=++$maxId;
					 $criterioaspecto->concursocriterio_id=$item->concursocriterio_id;
					 foreach ($aspectosid as $id) {
					 	if($item->aspectoclave_id==$id['oldid']){
					 		$criterioaspecto->aspectoclave_id=$id['newid'];
					 	}
					 }

					 $criterioaspecto->estado=true;
					 $criterioaspecto->evaluacion_id=$evaluacion_id;
					 $criterioaspecto->evaluador_created=$item->evaluador_created;
					 $data[]=$criterioaspecto->toArray();

				}
				if(count($data)>0)
				{
					CriterioAspectoClave::insert($data);
				}
			}
			//importando respuestas
			$respuestas=Respuesta::select('respuesta.*')
			->join('evaluacion','evaluacion.id','=','respuesta.evaluacion_id')
			->where('evaluacion.inscripcion_id','=',$inscripcion_id)
			->where('evaluacion.tipoetapa_id','=',$tipoetapaanterior_id)
			->where('evaluacion.abierta','=',false)
			->where('estado','=',true)
			->get();

			Respuesta::where('evaluacion_id','=',$evaluacion_id)->delete();

	 		$maxId=Respuesta::max('id');
	 		$data=array();
			foreach ($respuestas as $item) {

				 $respuesta=new Respuesta();
				 $respuesta->id=++$maxId;
				 $respuesta->concursocriterio_id=$item->concursocriterio_id;
				 $respuesta->respuesta=$item->respuesta;
				 $respuesta->puntaje=$item->puntaje;
				 $respuesta->aspectoclave_id=$item->aspectoclave_id;
				 $respuesta->evaluacion_id=$evaluacion_id;
				 $respuesta->areaanalisis_id=$item->areaanalisis_id;
				 $respuesta->pregunta_id=$item->pregunta_id;
				 $respuesta->evaluador_created=$item->evaluador_created;
				 $respuesta->estado=true;
				 $respuesta->created_at=new DateTime();
				 $data[]=$respuesta->toArray();
			}
			if(count($data)>0)
			{
				Respuesta::insert($data);
			}

			//NOTE: importando resumenes
			if($tipoetapaanterior_id===18){
				$informes=CriterioInforme::select('criterioinforme.*')
				->join('evaluacion','evaluacion.id','=','criterioinforme.evaluacion_id')
				->where('evaluacion.inscripcion_id','=',$inscripcion_id)
				->where('evaluacion.tipoetapa_id','=',$tipoetapaanterior_id)
				->where('evaluacion.abierta','=',false)

				->get();

				CriterioInforme::where('evaluacion_id','=',$evaluacion_id)->delete();

		 		$maxId=CriterioInforme::max('id');
		 		$data=array();
				foreach ($informes as $item) {

					 $informe=new CriterioInforme();
					 $informe->id=++$maxId;
					 $informe->concursocriterio_id=$item->concursocriterio_id;
					 $informe->informe=$item->informe;
					 $informe->evaluacion_id=$evaluacion_id;
					 $informe->tipo=$item->tipo;
					 $informe->created_at=new DateTime();
					 $data[]=$informe->toArray();
				}
				if(count($data)>0)
				{
					CriterioInforme::insert($data);
				}
			}
			//importando visita

			DB::statement("SET @@group_concat_max_len = 50000;");
			$visitas=CriterioVisita::select('criteriovisita.concursocriterio_id', DB::raw("GROUP_CONCAT(criteriovisita.descripcion SEPARATOR '\\n') as descripcion"))
			->join('evaluacion','evaluacion.id','=','criteriovisita.evaluacion_id')
			->where('evaluacion.inscripcion_id','=',$inscripcion_id)
			->where('evaluacion.tipoetapa_id','=',$tipoetapaanterior_id)
			->where('evaluacion.abierta','=',false)
			->groupBy('criteriovisita.concursocriterio_id')
			->get();

			CriterioVisita::where('evaluacion_id','=',$evaluacion_id)->delete();


	 		$data=array();
	 		$maxId=CriterioVisita::max('id');
			foreach ($visitas as $item) {
				 $visita=new CriterioVisita();
				 $visita->id=++$maxId;
				 $visita->concursocriterio_id=$item->concursocriterio_id;
				 $visita->descripcion=$item->descripcion;
				 $visita->evaluacion_id=$evaluacion_id;
				 $data[]=$visita->toArray();
			}
			if(count($data)>0)
			{
				CriterioVisita::insert($data);
			}

			$evaluacion->importdata=true;
			$evaluacion->save();
			$success=true;


		}
		$queries = DB::getQueryLog();
		return Response()->json($queries, 200);
		return Response()->json(array('success'=>$success), 201);


	}


	public function AbrirEvaluacion(Request $request)
	{
		$success=false;
		$evaluador_id=$request->query('evaluador_id','');
		$inscripcion_id=$request->query('inscripcion_id','');
		$tipoetapa_id=$request->query('tipoetapa_id','');
		if($evaluador_id!=''&&$inscripcion_id!=''&&$tipoetapa_id!=''){
			$evaluacion = Evaluacion::where(function($q) use($evaluador_id,$inscripcion_id,$tipoetapa_id){
				if($evaluador_id!=''){
					$q=$q->where('evaluador_id','=',$evaluador_id);
				}
				if($inscripcion_id!=''){
					$q=$q->where('inscripcion_id','=',$inscripcion_id);
				}
				if($tipoetapa_id!=''){
					$q=$q->where('tipoetapa_id','=',$tipoetapa_id);
				}
				return $q;
			})->update(array('abierta'=>1));
			$success=true;
		}
		return Response()->json(array('success'=>$success), 200);
	}

}
