<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\models\Catalogo;
use Validator;
class PostulanteCategoriasController extends Controller {

	public function store(Request $request)
	{
		$success=false;
		$entity=null;
		$validator = Validator::make($data = $request->all(), PostulanteCategoria::$rules);

		if (!$validator->fails())
		{
			$entity=PostulanteCategoria::create($data);
			$success=true;
		}



		return Response()->json(array('success' => $success, 'entity'=>$entity), 201);
	}

	public function show($id)
	{
		$postulantecategoria = PostulanteCategoria::findOrFail($id);

		return Response()->json($postulantecategoria, 201);
	}

	public function update(Request $request, $id)
	{
		$success=false;
		$msg='';
		$entity = PostulanteCategoria::findOrFail($id);

		$validator = Validator::make($data = $request->all(), PostulanteCategoria::$rules);

		if (!$validator->fails())
		{
			$entity->update($data);
			$success=true;
		}

		return Response()->json(array('success'=>$success,'message'=>$msg,'entity'=>$entity->toArray()), 201);
	}


	public function destroy(Request $request, $id)
	{
		$postulante_id=$request->input('postulante_id');
		$catalogo_id=$request->input('catalogo_id');
		PostulanteCategoria::where('postulante_id','=',$postulante_id)
			->where('catalogo_id','=',$catalogo_id)
			->delete();

		return Response()->json(array('success'=>true),200);
	}

}
