<?php
namespace App\Http\Controllers;

use Validator;
use App\models\Catalogo;
use App\models\Postulante;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PostulantesController extends Controller {

	public function store(Request $request)
	{
		$entity=null;
		$msg='';
		$success=false;
		$validator = Validator::make($data = $request->all(), Postulante::$rules);

		if (!$validator->fails())
		{
			$entity=Postulante::create($data);
			$success=true;
		}

		return Response()->json(array('success'=>$success,'message'=>$msg,'entity'=>$entity), 201);
	}

	public function show($id)
	{
		$postulante = Postulante::with('usuario')->with('categorias')->findOrFail($id);

		return Response()->json($postulante->toArray(),200);
	}

	public function update(Request $request, $id)
	{
		$success=false;
		$msg='';
		$postulante = Postulante::findOrFail($id);

		$validator = Validator::make($data = $request->all(), Postulante::$rules);

		if (!$validator->fails())
		{
			$postulante->update($data);
			$success=true;
		}

		return Response()->json(array('success'=>$success,'message'=>$msg,'entity'=>$postulante->toArray()), 201);
	}

	public function destroy($id)
	{
		Postulante::destroy($id);

		return Redirect::route('postulantes.index');
	}

    public function reportExcel(Request $request)
    {
		$estado=$request->query('estado','');
		$tipoconcurso_id=$request->query('tipoconcurso_id','');

        $entities=Inscripcion::select("postulante.*")
            ->join('postulante','postulante.id','=','inscripcion.postulante_id')
            ->join('concurso','concurso.id','=','inscripcion.concurso_id')
            ->where(function($q) use($estado,$tipoconcurso_id){
                if($estado!=''){
                    $q=$q->where('concurso.estado','=',$estado);
                }
                if($tipoconcurso_id!=''){
                	$q=$q->where('concurso.tipoconcurso_id','=',$tipoconcurso_id);
                }
                return $q;
            })
            ->orderBy('postulante.razonsocial','asc')
            ->distinct()
            ->get();

        $statusCode=200;

        $contents = View::make('reports.postulantes')
	        ->with('title', 'Listado de Postulantes')
	        ->with('entities',$entities);
        $response = Response::make($contents, $statusCode);
        $response->header('Content-Type', 'application/vnd.ms-excel;');
        $response->header('Content-Disposition', 'attachment; filename="report.xls"');
        return $response;
    }

    public function reportFicha()
    {
         $postulante=Postulante::with('usuario')
             ->with(array('categorias'=>function($q){
                return $q->with('catalogo');
             }))
             ->with('contactos')
             ->find($request->query('id',0));

         $contents = View::make('reports.postulante')
	        ->with('entity', $postulante)->render();
				//return Response()->json($contents, 200);
        return PDF::load($contents, 'A4', 'portrait')->download('ficha_postulante');
    }

}
