<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\models\Group;
use Validator;

class GroupsController extends Controller {

	/**
	 * Display a listing of groups
	 *
	 * @return Response
	 */
	public function index(Request $request)
	{
		$groups = Group::with(array('accesos'=>function($q){
			return $q->with('menu');
		}))->paginate($request->query('count'));

		return Response()->json($groups,200);
	}

	public function store()
	{
		$validator = Validator::make($data = $request->all(), Group::$rules);

		if ($validator->fails())
		{
			return Redirect::back()->withErrors($validator)->withInput();
		}

		Group::create($data);

		return Redirect::route('groups.index');
	}

	/**
	 * Display the specified group.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		$group = Group::with(array('accesos'=>function($q){
			return $q->join('menu', function($join)
	        {
	            $join->on('menu.id', '=', 'acceso.menu_id');//->where('menu.idpadre','=','0');
	        });

		}))->findOrFail($id);
		return Response()->json($group,200);

	}

	public function update($id)
	{
		$group = Group::findOrFail($id);

		$validator = Validator::make($data = $request->all(), Group::$rules);

		if ($validator->fails())
		{
			return Redirect::back()->withErrors($validator)->withInput();
		}

		$group->update($data);

		return Redirect::route('groups.index');
	}

	/**
	 * Remove the specified group from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		Group::destroy($id);

		return Redirect::route('groups.index');
	}

	public function reportExcel(){
        $statusCode=200;
        $entities = Group::all();
        $contents = View::make('reports.perfiles')
	        ->with('title', 'Listado de Grupos')
	        ->with('entities',$entities);
        $response = Response::make($contents, $statusCode);
        $response->header('Content-Type', 'application/vnd.ms-excel;');
        $response->header('Content-Disposition', 'attachment; filename="report.xls"');
        return $response;
    }

}
