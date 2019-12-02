<?php
namespace App\Http\Controllers;

use Validator;
use App\models\Catalogo;
use App\models\Concurso;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;


class ConcursosController extends Controller {

	public function index(Request $request)
	{
		$filter=$request->query("filter");
		$nombre=(!isset($filter['nombreconcurso']))?'':$filter['nombreconcurso'];
		$anio=(!isset($filter['anio']))?'':$filter['anio'];
		$estado=(!isset($filter['estado']))?'':$filter['estado'];
		$tipoconcurso_id=(!isset($filter['tipoconcurso_id']))?'':$filter['tipoconcurso_id'];
		//$estado=$request->query('estado','');
		$onlyTest=$request->query('onlyTest','');

		$entities = Concurso::with('tipo_concurso')->where(function($q) use($anio,$nombre,$tipoconcurso_id,$estado,$onlyTest){
			if($nombre!='')
				$q=$q->where('nombreconcurso','like','%'.$nombre.'%');

			if($anio!='')
				$q=$q->where('anio','=',$anio);

			if($tipoconcurso_id!='')
				$q=$q->where('tipoconcurso_id','=',$tipoconcurso_id);

			if($estado!='')
				$q=$q->where('estado','=',$estado);

			if($onlyTest!='')
				$q=$q->where('onlyTest','=',$onlyTest);

			return $q;
		})
		->orderBy('anio','desc')
		->paginate($request->query('count',9999));

		return Response()->json($entities,200);
	}

	public function store(Request $request)
	{
		$entity=null;
		$success=false;
		$validator = Validator::make($data = $request->all(), Concurso::$rules);

		if (!$validator->fails())
		{
			$entity=Concurso::create($data);
			$success=true;
			$entity=$entity->toArray();
		}

		return Response()->json(array('success' => $success, 'entity'=>$entity), 201);
	}


	public function show(Request $request, $id)
	{
		$postulante_id=$request->query('postulante_id','');
		$evaluador_id=$request->query('evaluador_id','');
		$concurso = Concurso::with(array('inscripciones'=>function($q) use($postulante_id){
			if($postulante_id!='')
				$q=$q->where('postulante_id','=',$postulante_id);
			return $q;

		}))
		->with(array('inscripcionesevaluador'=>function($q) use($evaluador_id){
			if($evaluador_id!='')
				$q=$q->where('evaluador_id','=',$evaluador_id);
			return $q;

		}))->findOrFail($id);
		return Response()->json($concurso,200);
	}

	/**
	 * Show the form for editing the specified concurso.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		$concurso = Concurso::find($id);

		return View::make('concursos.edit', compact('concurso'));
	}

	/**
	 * Update the specified concurso in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update(Request $request, $id)
	{
		$success=false;
		$concurso = Concurso::findOrFail($id);

		$validator = Validator::make($data = $request->all(), Concurso::$rules);

		if (!$validator->fails())
		{
			$concurso->update($data);
			$success=true;
		}

		return Response()->json(array('success' => $success, 'entity'=>$concurso), 201);


	}

	/**
	 * Remove the specified concurso from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{

        $message="";
        $success=false;
        $inscripciones=Inscripcion::where('concurso_id','=',$id)->get();
        $inscripcionesE=InscripcionEvaluador::where('concurso_id','=',$id)->get();
        if(count($inscripciones)<=0){
            if(count($inscripcionesE)<=0){
                Concurso::destroy($id);
                $success=true;
            }else{
                $message="Uno o mas evaluadores se encuentran inscritos al concurso";
            }
        }else{
            $message="Uno o mas postulantes se encuentran inscritos al concurso";
        }


		return Response()->json(array('success' => $success, 'message'=>$message),200);
	}

	public function concursosPostulante(Request $request){
		$postulante_id=$request->query('postulante_id','');
		$entities = Concurso::with('tipo_concurso')
		->with(array('inscripciones'=>function($q) use($postulante_id){
			return $q->where('postulante_id','=',$postulante_id);
		}))
		->where('estado','=',1)
		->where('onlyTest','=',0)
		->paginate($request->query('count'));

		return Response()->json($entities,200);
	}

	public function concursosEvaluador(Request $request){
		$evaluador_id=$request->query('evaluador_id','');
		$entities = Concurso::with('tipo_concurso')
		->with(array('inscripcionesevaluador'=>function($q) use($evaluador_id){
			return $q->where('evaluador_id','=',$evaluador_id);
		}))
		->where('estado','=',1)
		->where('onlyTest','=',0)
		->paginate($request->query('count'));

		return Response()->json($entities,200);
	}

	public function CopyCriterios(Request $request){
		$success=false;
		$idorigen=$request->query('idorigen',0);
		$iddestino=$request->query('iddestino',0);
		$entities = array();
		$ids=array();
		$maxcriterioId=ConcursoCriterio::max('id');
		if($idorigen!=0 && $iddestino!=0){
			$criterios=ConcursoCriterio::where('concurso_id','=',$idorigen)->get();
			foreach ($criterios as $criterio) {
				$maxcriterioId++;
				$entity=new ConcursoCriterio();
				$entity->id=$maxcriterioId;
				$entity->concurso_id=$iddestino;
				$entity->tipocriterio_id=$criterio->tipocriterio_id;
				$entity->tipoarbol_id=$criterio->tipoarbol_id;
				$entity->idpadre=$criterio->idpadre;
				$entity->codigo=$criterio->codigo;
				$entity->descripcion=$criterio->descripcion;
				$entity->puntaje=$criterio->puntaje;
				$entity->glosa=$criterio->glosa;
				$entity->comentario=$criterio->comentario;
				$entity->detalle=$criterio->detalle;
				$entity->proposito=$criterio->proposito;
				$entity->nota=$criterio->nota;
				$ids[]=array('old'=>$criterio->id,'new'=>$entity->id);

				$entities[]=$entity->toArray();
			}
			$count=0;
			foreach ($entities as $entity) {

				foreach ($ids as $id) {
					if($entities[$count]['idpadre']==$id['old']){
						$entities[$count]['idpadre']=$id['new'];
					}
				}
				$count++;
			}
			if(count($criterios)>0){
            	ConcursoCriterio::insert($entities);
        	}
        	$success=true;
		}

		return Response()->json(array('success'=>$success,'criterios'=>$entities), 201);
	}

	public function CierreConcurso($id){
		$status = 500;
		$message ="Concurso finalizado satisfactoriamente";
		if (Sentry::check()){
			$user=Sentry::getUser();
			if((int)$user->group_id ===1){
				$concurso = Concurso::find($id);
				if($concurso){
					$concurso->estado = 0;
					$concurso->save();
					$status = 202;
				}else{
					$status = 404;
					$message = "No se encontro el concurso seleccionado";
				}
			}else{
				$status = 401;
				$message = "No tiene privilegios suficientes para realizar esta operación";
			}
		}else{
			$status = 403 ;
			$message = "Acceso restringido";
		}
		return Response()->json(array('message'=>$message,'user'=>$user->toArray()), $status);
	}

}
