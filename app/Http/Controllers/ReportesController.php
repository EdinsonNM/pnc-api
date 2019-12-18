<?php
namespace App\Http\Controllers;

use view;
use Validator;
use App\models\Catalogo;
use App\models\Concurso;
use App\models\Evaluacion;
use App\models\Inscripcion;
use App\Exports\ExportExcel;
use Illuminate\Http\Request;
use App\models\CriterioInforme;
use App\models\GrupoEvaluacion;
use App\models\ConcursoCriterio;
use App\models\CriterioAprobacion;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;
use App\models\GrupoEvaluacionEvaluador;
use Illuminate\Support\Facades\Response;
use App\models\GrupoEvaluacionPostulante;

class ReportesController extends Controller {

	public function getEvaluacionIndividualCuaderno(Request $request)
	{
		$idpadre=$request->query('idpadre');
		$id=$request->query('id');
		$evaluador_id=$request->query('evaluador_id','');
		$inscripcion_id=$request->query('inscripcion_id','');
		$tipoetapa_id=$request->query('tipoetapa_id','');

		$concurso_id=$request->query('concurso_id');

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

		$items=ConcursoCriterio::select('concursocriterio.*')
		->with(array('puntajes'=>function($q) use($evaluacion){
			return $q->where('evaluacion_id','=',$evaluacion->id);
		}))
		->with(array('visitas'=>function($q) use($evaluacion){
			return $q->where('evaluacion_id','=',$evaluacion->id);
		}))
		->with(array('respuestas'=>function($q) use($evaluacion){
			return $q->with('pregunta')->where('evaluacion_id','=',$evaluacion->id)->where('estado','=',1);
		}))
		->with(array('criterio_aspectosclaves'=>function($q) use($evaluacion){
			return $q->with('aspectoclave')->where('evaluacion_id','=',$evaluacion->id)->where('estado','=',1);
		}))
		->where(function($q) use($idpadre,$id){
			if($idpadre!='')
				$q=$q->where('idpadre','=','idpadre');
			if($id!='')
				$q=$q->where('id','=','id');
		})
		->join('concursocriterio as c','concursocriterio.idpadre','=','c.id')
		->whereNotIn('c.tipocriterio_id',array(7,8))//diferente de aspecto y factor clave
		->where('concursocriterio.tipoarbol_id','=',10)
		->where('concursocriterio.concurso_id','=',$concurso_id)
		->orderByRaw('concursocriterio.codigo+0 asc')
		->get();
		return Response()->json($items,200);
	}

	public function getEvaluacionIndividualFactoresClave(Request $request)
	{
		$idpadre=$request->query('idpadre');
		$id=$request->query('id');
		$evaluador_id=$request->query('evaluador_id','');
		$inscripcion_id=$request->query('inscripcion_id','');
		$tipoetapa_id=$request->query('tipoetapa_id','');

		$concurso_id=$request->query('concurso_id');

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

		$items=ConcursoCriterio::select('concursocriterio.*')

		->with(array('criterio_aspectosclaves'=>function($q) use($evaluacion){
			return $q->with('aspectoclave')->where('evaluacion_id','=',$evaluacion->id);
		}))
		->where(function($q) use($idpadre,$id){
			if($idpadre!='')
				$q=$q->where('idpadre','=','idpadre');
			if($id!='')
				$q=$q->where('id','=','id');
		})
		->join('concursocriterio as c','concursocriterio.idpadre','=','c.id')
		->whereNotIn('c.tipocriterio_id',array(7,8))//diferente de aspecto y factor clave
		->where('concursocriterio.tipoarbol_id','=',10)
		->where('concursocriterio.concurso_id','=',$concurso_id)
		->orderByRaw('concursocriterio.codigo+0 asc')
		->get();
		return Response()->json($items,200);
	}

	public function getEvaluacionIndividualResumen(Request $request)
	{
		$evaluador_id=$request->query('evaluador_id','');
		$inscripcion_id=$request->query('inscripcion_id','');
		$tipoetapa_id=$request->query('tipoetapa_id','');
		$concurso_id=$request->query('concurso_id','');

		$evaluacion = Evaluacion::where('evaluador_id','=',$evaluador_id)
		->where('inscripcion_id','=',$inscripcion_id)
		->where('tipoetapa_id','=',$tipoetapa_id)
		->first();


		$criterios=ConcursoCriterio::select(DB::raw('concursocriterio.*'))
		->where('tipoarbol_id','=',9)
		->where('concurso_id','=',$concurso_id)
		->whereNotIn('tipocriterio_id',array(7,8))
		->with(array('children'=>function($q) use($evaluacion){
			return $q->with(array('puntajes'=>function($q) use($evaluacion){
				return $q->where('evaluacion_id','=',$evaluacion->id);
			}));
		}))
		->orderByRaw('concursocriterio.codigo+0 asc')
		->get();

		return Response()->json($criterios,200);

	}

	public function getEvaluacionIndividualPorEquipo(Request $request)
	{
		$grupoevaluacion_id=$request->query('grupoevaluacion_id','');
		$inscripcion_id=$request->query('inscripcion_id','');
		$tipoetapa_id=$request->query('tipoetapa_id','');
		$concurso_id=$request->query('concurso_id','');

		$gevaluadores = GrupoEvaluacionEvaluador::with(array('inscripcion'=>function($q){
			return $q->with('evaluador');
		}))
		->where('grupoevaluacion_id','=',$grupoevaluacion_id)
		->get();


		$ids=array();
		foreach ($gevaluadores as $inscripcion){
			$ids[]=$inscripcion->inscripcion->evaluador->id;
		}

		$evaluaciones = Evaluacion::whereIn('evaluador_id',$ids)
		->where('inscripcion_id','=',$inscripcion_id)
		->where('tipoetapa_id','=',$tipoetapa_id)
		->get();

		$evaluaciones_id=array();
		foreach ($evaluaciones as $evaluacion){
			$evaluaciones_id[]=$evaluacion->id;
		}

		//return Response()->json($evaluaciones_id,200);


		$criterios=ConcursoCriterio::select(DB::raw('concursocriterio.*'))
		->where('tipoarbol_id','=',9)
		->where('concurso_id','=',$concurso_id)
		->whereNotIn('tipocriterio_id',array(7,8))
		->with(array('children'=>function($q) use($evaluaciones_id){
			return $q->with(array('puntajes'=>function($q) use($evaluaciones_id){
				return $q->with(array('evaluacion'=>function($q){
					return $q->with('evaluador');
				}))->whereIn('evaluacion_id',$evaluaciones_id);
			}));
		}))
		->orderByRaw('concursocriterio.codigo+0 asc')
		->get();

		return Response()->json($criterios,200);
	}
	public function getResumenAprobacionInformeEjecutivo(Request $request)
	{
		$evaluacion_id=$request->query('evaluacion_id','');
		$aprobaciones=CriterioAprobacion::where('evaluacion_id','=',$evaluacion_id)
		->where('concursocriterio_id',0)
		->get();
		return Response()->json($aprobaciones->toArray(),200);
	}
	public function getResumenAprobacionConcenso(Request $request)
	{
		$evaluacion_id=$request->query('evaluacion_id','');
		$concurso_id=$request->query('concurso_id','');
		$criterios=ConcursoCriterio::select(DB::raw('concursocriterio.*'))
		->where('tipoarbol_id','=',9)
		->where('concurso_id','=',$concurso_id)
		->whereNotIn('tipocriterio_id',array(7,8))
		->with(array('children'=>function($q) use($evaluacion_id){
			return $q->with(array('criterio_aprobaciones'=>function($q) use($evaluacion_id){
				return $q->where('evaluacion_id','=',$evaluacion_id);
			}));
		}))
		->with(array('criterio_aprobaciones'=>function($q) use($evaluacion_id){
			return $q->where('evaluacion_id','=',$evaluacion_id);
		}))
		->orderByRaw('concursocriterio.codigo+0 asc')
		->get();

		return Response()->json($criterios,200);
	}


	public function postExportExcel(Request $request){
        $statusCode=200;
        $data=$request->input('data_download');
        return Excel::download(new ExportExcel($data), 'reporte.xlsx');


    }

	public function postExportWord(Request $request){
        $statusCode=200;
        $data=($request->query('data_download_word'));
        $contents = View::make('downloadTable')->with('data', $data);
        $response = Response::make($contents, $statusCode);
        $response->header('Content-Type', 'application/msword');
        $response->header('Content-Disposition', 'attachment; filename="report.doc"');
        return $response;
    }

    public function getEvaluacionFactoresClave(Request $request){
    	$idpadre=$request->query('idpadre');
		$id=$request->query('id');
		$evaluador_id=$request->query('evaluador_id','');
		$inscripcion_id=$request->query('inscripcion_id','');
		$tipoetapa_id=$request->query('tipoetapa_id','');

		$concurso_id=$request->query('concurso_id');

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

		$items=ConcursoCriterio::select('concursocriterio.*')
		->with(array('aspectosclaves'=>function($q) use($evaluacion){
			return $q->where('evaluacion_id','=',$evaluacion->id)->where('estado','=','1');
		}))
		->where(function($q) use($idpadre,$id){
			if($idpadre!='')
				$q=$q->where('idpadre','=','idpadre');
			if($id!='')
				$q=$q->where('id','=','id');
		})
		->join('concursocriterio as c','concursocriterio.idpadre','=','c.id')
		->where('c.tipocriterio_id',7)//diferente de aspecto y factor clave
		->where('concursocriterio.tipoarbol_id','=',10)
		->where('concursocriterio.concurso_id','=',$concurso_id)
		->orderByRaw('concursocriterio.codigo+0 asc')
		->get();
		return Response()->json($items,200);

    }

    public function getSeguimientoEvaluadores(Request $request){
    	$grupoevaluacion_id=$request->query('grupoevaluacion_id','');
    	$inscripcion_id=$request->query('inscripcion_id','');
    	$results = DB::select(DB::raw("SELECT CONCAT(ev.nombres,' ',ev.apellidos) AS evaluador
,grueval.id AS grupo_id
,grueval.nombre AS grupo
,con.nombreconcurso AS concurso
,individual.fechaCierre AS individual
,gep_individual.fechaextension AS permiso_individual
,consenso.fechaCierre AS consenso
,gep_consenso.fechaextension AS permiso_consenso
,visita.fechaCierre AS visita
,gep_visita.fechaextension AS permiso_visita
,retroalimentacion.fechaCierre AS retroalimentacion
,gep_retro.fechaextension AS permiso_retroalimentacion

FROM grupoevaluacionevaluador ge
JOIN grupoevaluacion grueval ON ge.grupoevaluacion_id=grueval.id
JOIN concurso con ON con.id=grueval.concurso_id
JOIN inscripcionevaluador ie ON ge.inscripcion_id=ie.id
JOIN evaluador ev ON ev.id=ie.evaluador_id
LEFT JOIN evaluacion AS individual ON individual.inscripcion_id=$inscripcion_id AND individual.evaluador_id=ev.id AND individual.tipoetapa_id=15
LEFT JOIN grupoevaluacionevaluador_permiso gep_individual ON  gep_individual.grupoevaluacionevaluador_id=ge.id AND gep_individual.tipoetapa_id=15

LEFT JOIN evaluacion AS consenso ON consenso.inscripcion_id=$inscripcion_id AND consenso.evaluador_id=0 AND consenso.tipoetapa_id=16
LEFT JOIN grupoevaluacionevaluador_permiso gep_consenso ON  gep_consenso.grupoevaluacionevaluador_id=ge.id AND gep_consenso.tipoetapa_id=16

LEFT JOIN evaluacion AS visita ON visita.inscripcion_id=$inscripcion_id AND visita.evaluador_id=0 AND visita.tipoetapa_id=17
LEFT JOIN grupoevaluacionevaluador_permiso gep_visita ON  gep_visita.grupoevaluacionevaluador_id=ge.id AND gep_visita.tipoetapa_id=17

LEFT JOIN evaluacion AS retroalimentacion ON retroalimentacion.inscripcion_id=$inscripcion_id AND retroalimentacion.evaluador_id=0 AND retroalimentacion.tipoetapa_id=18
LEFT JOIN grupoevaluacionevaluador_permiso gep_retro ON  gep_retro.grupoevaluacionevaluador_id=ge.id AND gep_retro.tipoetapa_id=18

WHERE ge.grupoevaluacion_id=$grupoevaluacion_id") );

return Response()->json($results, 200);
    }


    public function getEvaluacionConsensoPorEquipo(Request $request)
	{
		$grupoevaluacion_id=$request->query('grupoevaluacion_id','');
		$inscripcion_id=$request->query('inscripcion_id','');
		$tipoetapa_id=$request->query('tipoetapa_id','');
		$concurso_id=$request->query('concurso_id','');

		$postulante = GrupoEvaluacionPostulante::with(array('inscripcion'=>function($q){
			return $q->with('postulante');
		}))
		->where('grupoevaluacion_id','=',$grupoevaluacion_id)
		->get();


		$ids=array();
		foreach ($postulante as $inscripcion){
			$ids[]=$inscripcion->inscripcion->id;
		}

		$evaluaciones = Evaluacion::whereIn('inscripcion_id',$ids)
		//->where('inscripcion_id','=',$inscripcion_id)
		->where('tipoetapa_id','=',$tipoetapa_id)
		->get();

		$evaluaciones_id=array();
		foreach ($evaluaciones as $evaluacion){
			$evaluaciones_id[]=$evaluacion->id;
		}

		//return Response()->json($evaluaciones_id,200);


		$criterios=ConcursoCriterio::select(DB::raw('concursocriterio.*'))
		->where('tipoarbol_id','=',9)
		->where('concurso_id','=',$concurso_id)
		->whereNotIn('tipocriterio_id',array(7,8))
		->with(array('children'=>function($q) use($evaluaciones_id){
			return $q->with(array('puntajes'=>function($q) use($evaluaciones_id){
				return $q->with(array('evaluacion'=>function($q){
					return $q->with('evaluador');
				}))->whereIn('evaluacion_id',$evaluaciones_id);
			}));
		}))
		->orderByRaw('concursocriterio.codigo+0 asc')
		->get();

		return Response()->json($criterios,200);
	}



	public function getEvaluacionTemasVisita(Request $request)
	{
		$idpadre=$request->query('idpadre');
		$id=$request->query('id');
		$evaluador_id=$request->query('evaluador_id','');
		$inscripcion_id=$request->query('inscripcion_id','');
		$tipoetapa_id=$request->query('tipoetapa_id','');

		$concurso_id=$request->query('concurso_id');

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

		$items=ConcursoCriterio::select('concursocriterio.*')

		->with(array('visitas'=>function($q) use($evaluacion){
			return $q->where('evaluacion_id','=',$evaluacion->id);
		}))

		->where(function($q) use($idpadre,$id){
			if($idpadre!='')
				$q=$q->where('idpadre','=','idpadre');
			if($id!='')
				$q=$q->where('id','=','id');
		})
		->join('concursocriterio as c','concursocriterio.idpadre','=','c.id')
		->whereNotIn('c.tipocriterio_id',array(7,8))//diferente de aspecto y factor clave
		->where('concursocriterio.tipoarbol_id','=',10)
		->where('concursocriterio.concurso_id','=',$concurso_id)
		->orderByRaw('concursocriterio.codigo+0 asc')
		->get();
		return Response()->json($items,200);
	}

	public function getEvaluacionPorEquipo(Request $request)
	{
		$grupoevaluacion_id=$request->query('grupoevaluacion_id','');
		$inscripcion_id=$request->query('inscripcion_id','');
		$tipoetapa_id=$request->query('tipoetapa_id','');
		$concurso_id=$request->query('concurso_id','');

		$postulante = GrupoEvaluacionPostulante::with(array('inscripcion'=>function($q){
			return $q->with('postulante');
		}))
		->join('grupoevaluacion as g','g.id','=','grupoevaluacionpostulante.grupoevaluacion_id')
		->where('g.concurso_id','=',$concurso_id)
		->get();


		$ids=array();
		foreach ($postulante as $inscripcion){
			$ids[]=$inscripcion->inscripcion->id;
		}

		$evaluaciones = Evaluacion::whereIn('inscripcion_id',$ids)
		//->where('inscripcion_id','=',$inscripcion_id)
		->where('tipoetapa_id','=',$tipoetapa_id)
		->get();

		$evaluaciones_id=array();
		foreach ($evaluaciones as $evaluacion){
			$evaluaciones_id[]=$evaluacion->id;
		}

		//return Response()->json($evaluaciones_id,200);


		$criterios=ConcursoCriterio::select(DB::raw('concursocriterio.*'))
		->where('tipoarbol_id','=',9)
		->where('concurso_id','=',$concurso_id)
		->whereNotIn('tipocriterio_id',array(7,8))
		->with(array('children'=>function($q) use($evaluaciones_id){
			return $q->with(array('puntajes'=>function($q) use($evaluaciones_id){
				return $q->with(array('evaluacion'=>function($q){
					return $q->with('evaluador');
				}))->whereIn('evaluacion_id',$evaluaciones_id);
			}));
		}))
		->orderByRaw('concursocriterio.codigo+0 asc')
		->get();

		return Response()->json($criterios,200);
	}

	public function getInformeRetroalimentacion(Request $request){
		set_time_limit (240);
		$grupoevaluacion_id=$request->query('grupoevaluacion_id','');
		$inscripcion_id=$request->query('inscripcion_id','');
		$tipoetapa_id=$request->query('tipoetapa_id','');
		$concurso_id=$request->query('concurso_id','');
		$evaluador_id=$request->query('evaluador_id','');
		$inscripcion=Inscripcion::with('postulante')->find($inscripcion_id);
		$concurso=Concurso::find($concurso_id);
		$evaluacion = Evaluacion::where(function($q) use($evaluador_id,$inscripcion_id,$tipoetapa_id){
			if($evaluador_id!=''){
				$q=$q->where('evaluador_id','=',0);
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
		$evaluacion_id=$evaluacion->id;
		$informes=CriterioInforme::where('evaluacion_id',$evaluacion->id)->get();

		$criterios=ConcursoCriterio::select(DB::raw('concursocriterio.*'))
		->where('tipoarbol_id','=',9)
		->where('concurso_id','=',$concurso_id)
		->whereNotIn('tipocriterio_id',array(7,8))
		->with(array('children'=>function($q) use($evaluacion_id){
			return $q->with(array('respuestas'=>function($q2) use($evaluacion_id){
				return $q2->where('evaluacion_id',$evaluacion_id)->where('estado',1)->orderByRaw('puntaje desc');
			}));
		}))
		->orderByRaw('concursocriterio.codigo+0 asc')
		->get();

	 	return Response()->json(
		array(
			'criterios'=>$criterios->toArray(),
			'concurso'=>$concurso->toArray(),
			'inscripcion'=>$inscripcion->toArray(),
			'informes'=>$informes->toArray()
		),200);
	}

	public function getSeguimientoEncuesta(Request $request){
		$concurso_id=$request->query('concurso_id','');

		$entities = GrupoEvaluacion::with(array('concurso'=>function($q){
				return $q->with(array('etapas'=>function($q2){
					return $q2->with(array('etapa'=>function($q3){
							return $q3->with('TipoEtapa');
						}));
					}));
			}))
		->with(array('encuestaevaluaciones'=>function($q){
			return $q->with('encuesta')->with(array('respuestas'=>function($q2){
				return $q2->with('evaluador');
			}));
		}))
		->with(array('evaluadores'=>function($q){
				return $q->with(array('inscripcion'=>function($q2){
					return $q2->with('evaluador');
				}))->with('evaluaciones');
			}))
		->where('concurso_id','=',$concurso_id)
		->get();

		return Response()->json($entities,200);
	}

}
