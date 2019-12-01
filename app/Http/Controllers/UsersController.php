<?php

namespace App\Http\Controllers;

use Mail;
use Validator;
use App\models\User;
use App\models\Catalogo;
use App\models\Evaluador;
use App\models\Postulante;

use Illuminate\Http\Request;
use App\Mail\MessageUserActivated;
use App\Mail\MessageUserRegistered;
use App\Http\Controllers\Controller;

class UsersController extends Controller
{
    public $salt='$6$rounds=1000$YourSaltyStringz$';
  public function signin(Request $request){
		$success=false;
		$message="";
		$entity=null;
        $username=$request->query('username');
        $password = crypt($request->query('password'), $this->salt);
        $user = User::where('username', '=', $username)->where('password', '=', $password)->first();
        if($user){
                $success=true;
                $entity=$user;
        }
		return Response()->json(array('success' => $success, 'entity'=>$entity,'message'=>$message), 200);
    }


    public function store(Request $request)
	{
		$data = $request->all();
		$message='';
		$user=null;
		$success=false;


        $validator = Validator::make($data, User::$rules);
		$success=false;
		if (!$validator->fails())
		{
            $data['activation_code'] =  uniqid('user_');
            $data['password'] = crypt($data['password'], $this->salt);
			$entity=User::create($data);
			$success=true;
        }
        Mail::to($data['email'])->send(new MessageUserRegistered($data));

		return Response()->json(array('success' => $success, 'entity'=>$entity,'message'=>$message), 201);
    }

    public function ValidateUnique(Request $request, $attribute){
		$value=$request->query('value');
		$users = User::where($attribute,'=',$value)->get();
		$success=(count($users)>0)?false:true;
		return Response()->json(array('success' => $success), 200);

    }

    public function activatedUser(Request $request){
		$code=$request->input('code','');

		$success=false;
		$message='';
		$sendemail=false;

        $user = User::where('activation_code', '=', $code)->first();
        if($user){
            if (!$user->activated){
                switch($user->group_id){
                    case 2:
                        $evaluador=Evaluador::where('usuario_id','=',$user->id)->first();
                        if(!$evaluador){
                            $evaluador=new Evaluador();
                            $evaluador->usuario_id=$user->id;
                            $evaluador->nombres=$user->first_name;
                            $evaluador->apellidos=$user->last_name;
                            $evaluador->save();
                            $sendemail=true;
                        }
                        break;
                    case 3:
                        $message='postulante';
                        $postulante=Postulante::where('usuario_id','=',$user->id)->first();
                        if(!$postulante){
                            $postulante=new Postulante();
                            $postulante->usuario_id=$user->id;
                            $postulante->save();
                            $sendemail=true;
                        }

                        break;
                }

                $success=true;
                $user->activated=true;
                $message="Su cuenta ha sido activada satisfactoriamente";

                if($sendemail){
                    $dataEmail = array('usuario'=>$user->first_name.' '.$user->last_name);
                    Mail::to($user->email)->send(new MessageUserActivated($dataEmail));
                }
                $user->save();
            }else{
                $message="Su usuario ya se encuentra activado en el sistema. Si tiene algun problema para acceder al sistema puede contactarnos a cgc@sni.org.pe";
            }
        }else{
            $message="No se ha encontrado el usuario que desea activar o puede que ya se encuentre activado. Si tiene algun problema para acceder al sistema puede contactarnos a cgc@sni.org.pe";
        }
        return Response()->json(array('success' => $success, 'message'=>$message), 200);

    }
}
