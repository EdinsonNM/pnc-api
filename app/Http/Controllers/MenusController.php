<?php
namespace App\Http\Controllers;

use Validator;
use App\models\Menu;
use App\models\Catalogo;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class MenusController extends Controller {

	public function index(Request $request)
	{
		$menus = Menu::with('children')->where('idpadre','=','0')->get();

		return Response()->json($menus, 200);
	}

	public function create()
	{
		return View::make('menus.create');
	}

	public function store(Request $request)
	{
		$validator = Validator::make($data = Input::all(), Menus::$rules);

		if ($validator->fails())
		{
			return Redirect::back()->withErrors($validator)->withInput();
		}

		Menus::create($data);

		return Redirect::route('menus.index');
	}


	public function update(Request $request, $id)
	{
		$menus = Menus::findOrFail($id);

		$validator = Validator::make($data = Input::all(), Menus::$rules);

		if ($validator->fails())
		{
			return Redirect::back()->withErrors($validator)->withInput();
		}

		$menus->update($data);

		return Redirect::route('menus.index');
	}


}
