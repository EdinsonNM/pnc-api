<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\models\Catalogo;
use Validator;

class CatalogosController extends Controller
{
    public function index(Request $request)
	{
		$codcatalogo=$request->query('codcatalogo','');
		$codigo=$request->query('codigo','');
		$catalogos = Catalogo::where(function($q) use($codcatalogo,$codigo){
			if($codcatalogo!='')
				$q=$q->where('codcatalogo','=',$codcatalogo);

			if($codigo!='')
				$q=$q->where('codigo','=',$codigo);
			return $q;
		})->paginate($request->query('count'));

		return Response()->json($catalogos,200);
    }

    public function store(Request $request)
	{
        $entity=null;
        $validator = Validator::make($data = $request->all(), Catalogo::$rules);
		$success=false;
		if (!$validator->fails())
		{

			$entity=Catalogo::create($data);
			$success=true;
		}

		return Response()->json(array('success' => $success, 'entity'=>$entity));
	}


	public function show($id)
	{
		$catalogo = Catalogo::findOrFail($id);

		return Response()->json($catalogo,200);
	}

	public function update(Request $request, $id)
	{
		$success=false;
		$catalogo = Catalogo::findOrFail($id);
		$data = $request->all();
		$data['estado']=($data['estado']=='true')?true:false;
		$validator = Validator::make($data, Catalogo::$rules);

		if (!$validator->fails())
		{
			$catalogo->update($data);
			$success=true;
		}

		return Response()->json(array('success' => $success, 'entity'=>$catalogo), 201);
	}


	public function destroy($id)
	{
		Catalogo::destroy($id);
		return Response()->json(array('success' => true),200);
	}
}
