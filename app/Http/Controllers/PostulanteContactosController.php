<?php
namespace App\Http\Controllers;

use Validator;
use App\models\Catalogo;
use Illuminate\Http\Request;
use App\models\PostulanteContacto;
use App\Http\Controllers\Controller;

class PostulanteContactosController extends Controller {

	/**
	 * Display a listing of postulantecontactos
	 *
	 * @return Response
	 */
	public function index(Request $request)
	{
		$postulante_id=$request->query('postulante_id','');
		$postulantecontactos = PostulanteContacto::with('TipoContacto')
			->where(function($q) use($postulante_id){
			if($postulante_id!='')
				$q=$q->where('postulante_id','=',$postulante_id);
				return $q;
			})
			->get();

		return Response()->json($postulantecontactos,200);
	}

	public function store(Request $request)
	{

		$success=false;
		$entity=null;
		$validator = Validator::make($data = $request->all(), PostulanteContacto::$rules);

		if (!$validator->fails())
		{
			$entity=PostulanteContacto::create($data);
			$success=true;
		}



		return Response()->json(array('success' => $success, 'entity'=>$entity), 201);
	}

	public function update(Request $request,$id)
	{
		$success=false;
		$entity=null;
		$postulantecontacto = PostulanteContacto::findOrFail($id);

		$validator = Validator::make($data = $request->all(), PostulanteContacto::$rules);

		if (!$validator->fails())
		{
			$postulantecontacto->update($data);
			$success=true;
		}

		return Response()->json(array('success' => $success, 'entity'=>$postulantecontacto), 201);
	}


	public function destroy($id)
	{
		PostulanteContacto::destroy($id);

		return Response()->json(array('success'=>true),200);
	}

}
