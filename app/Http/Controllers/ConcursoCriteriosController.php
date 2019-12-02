<?php
namespace App\Http\Controllers;

use Validator;
use App\models\Concurso;
use Illuminate\Http\Request;
use App\models\ConcursoCriterio;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class ConcursoCriteriosController extends Controller {


	public function index(Request $request)
	{
		$concursocriterios = ConcursoCriterio::all();

		return Response()->json($concursocriterios,200);
	}

	public function store(Request $request)
	{

		$entity=null;
		$success=false;
		$validator = Validator::make($data = $request->all(), ConcursoCriterio::$rules);

		if (!$validator->fails())
		{
			$entity=ConcursoCriterio::create($data);
			$success=true;
		}

		return Response()->json(array('success' => $success, 'entity'=>$entity), 201);
	}


	public function show($id)
	{
		$concursocriterio = ConcursoCriterio::findOrFail($id);

		return Response()->json($concursocriterio, 200);
	}


	public function update(Request $request, $id)
	{


		$success=false;
		$concursocriterio = ConcursoCriterio::findOrFail($id);

		$validator = Validator::make($data = $request->all(), ConcursoCriterio::$rules);

		if (!$validator->fails())
		{
			$concursocriterio->update($data);
			$success=true;
		}

		return Response()->json(array('success' => $success, 'entity'=>$concursocriterio), 201);
	}

	public function destroy($id)
	{
		ConcursoCriterio::destroy($id);

		return Response()->json(array('success' => true),200);
	}

	public function Tree(Request $request)
	{
		$nivel=$request->query('nivel',4);
		$concurso_id=$request->query('concurso_id','');
		$criterios=ConcursoCriterio::select(DB::raw('concursocriterio.*,catalogo.nombre as tipoarbol'))
		->where('tipoarbol_id','=',9)
		->where('concurso_id','=',$concurso_id)
		->join('catalogo','catalogo.id','=','concursocriterio.tipoarbol_id')
		->with(array('children'=>function($q) use($nivel){

			return $q->select(DB::raw('concursocriterio.*,catalogo.nombre as tipoarbol'))
				->join('catalogo','catalogo.id','=','concursocriterio.tipoarbol_id')
				->with(array('children'=>function($q){
				return $q->select(DB::raw('concursocriterio.*,catalogo.nombre as tipoarbol'))
					->join('catalogo','catalogo.id','=','concursocriterio.tipoarbol_id')
					->with(array('children'=>function($q){
					return $q->select(DB::raw('concursocriterio.*,catalogo.nombre as tipoarbol'))
					->join('catalogo','catalogo.id','=','concursocriterio.tipoarbol_id')
					->with('children')
					->orderByRaw('concursocriterio.codigo,concursocriterio.codigo+0 asc');
				}))
				->orderByRaw('concursocriterio.codigo,concursocriterio.codigo+0 asc');
			}))->orderByRaw('concursocriterio.codigo,concursocriterio.codigo+0 asc');
		}))
		->orderByRaw('catalogo.codigo asc,concursocriterio.codigo+0 asc')
		->get();

		return Response()->json($criterios,200);
	}

	public function reportExcel(Request $request){
        $statusCode=200;
        $nivel=$request->query('nivel',4);
		$concurso_id=$request->query('concurso_id','');
		$concurso=Concurso::find($concurso_id);
		$criterios=ConcursoCriterio::select(DB::raw('concursocriterio.*,catalogo.nombre as tipoarbol'))
		->where('tipoarbol_id','=',9)
		->where('concurso_id','=',$concurso_id)
		->join('catalogo','catalogo.id','=','concursocriterio.tipoarbol_id')
		->with(array('children'=>function($q) use($nivel){

			return $q->select(DB::raw('concursocriterio.*,catalogo.nombre as tipoarbol'))
				->join('catalogo','catalogo.id','=','concursocriterio.tipoarbol_id')
				->with(array('children'=>function($q){
				return $q->select(DB::raw('concursocriterio.*,catalogo.nombre as tipoarbol'))
					->join('catalogo','catalogo.id','=','concursocriterio.tipoarbol_id')
					->with(array('children'=>function($q){
					return $q->select(DB::raw('concursocriterio.*,catalogo.nombre as tipoarbol'))
					->join('catalogo','catalogo.id','=','concursocriterio.tipoarbol_id')
					->with('children')
					->orderByRaw('concursocriterio.codigo,concursocriterio.codigo+0 asc');
				}))
				->orderByRaw('concursocriterio.codigo,concursocriterio.codigo+0 asc');
			}))->orderByRaw('concursocriterio.codigo,concursocriterio.codigo+0 asc');
		}))
		->orderByRaw('catalogo.codigo asc,concursocriterio.codigo+0 asc')
		->get();

        $contents = View::make('reports.concursocriterios')
	        ->with('title', 'Listado de Criterios')
	        ->with('entities',$criterios)
	        ->with('concurso',$concurso);
        $response = Response::make($contents, $statusCode);
        $response->header('Content-Type', 'application/vnd.ms-excel;');
        $response->header('Content-Disposition', 'attachment; filename="report.xls"');
        return $response;
    }

}
