<?php
namespace App\Http\Controllers;

use Validator;
use App\models\Catalogo;
use Illuminate\Http\Request;
use App\models\Impactoproyecto;
use App\Http\Controllers\Controller;

class ImpactoproyectosController extends Controller {

	public function store(Request $request)
	{

		$entity=null;
		$success=false;
		$validator = Validator::make($data = $request->all(), Impactoproyecto::$rules);
		//return  $request->query('inscripcion_id');
		if (!$validator->fails())
		{
			$entity=Impactoproyecto::create($data);
			$success=true;
			$entity=$entity->toArray();
		}

		return Response()->json(array('success' => $success, 'entity'=>$entity), 201);	}



	public function MultipleSave(Request $request){
		$inscripcion_id = $request->input('inscripcion_id','');
		Impactoproyecto::where('inscripcion_id','=',$inscripcion_id)->delete();
		$ids = $request->input('ids','');
		$arr_ids = explode(",", $ids);
		$data = array();
		foreach ($arr_ids as $id) {
			$entity = new Impactoproyecto();
			$entity->inscripcion_id= $inscripcion_id;
			$entity->impactoproyecto_id= $id;
			$data[]=$entity->toArray();
		}
		Impactoproyecto::insert($data);
		return Response()->json(array('success'=>true),200);
	}

}
