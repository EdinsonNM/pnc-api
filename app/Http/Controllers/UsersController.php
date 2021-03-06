<?php

namespace App\Http\Controllers;

use Mail;
use Validator;
use App\models\User;
use App\models\Catalogo;
use App\models\Evaluador;
use App\models\Postulante;

use Illuminate\Http\Request;
use App\Mail\MessageResetPassword;
use App\Mail\MessageUserActivated;
use Illuminate\Support\Facades\DB;
use App\Mail\MessageUserRegistered;
use App\Http\Controllers\Controller;
use App\models\InscripcionEvaluador;
use DateTime;

class UsersController extends Controller
{
    public $salt='$6$rounds=1000$pncorgpewapp2019$';
    public function index(Request $request)
	{
		$filter=$request->query("filter");
		$name=(!isset($filter['name']))?'':$filter['name'];
		$username=(!isset($filter['username']))?'':$filter['username'];
		$group_id=(!isset($filter['group_id']))?'':$filter['group_id'];
		$email=(!isset($filter['email']))?'':$filter['email'];
		$postulante_telefono=(!isset($filter['postulante.telefono']))?'':$filter['postulante.telefono'];
		$postulante_ruc=(!isset($filter['postulante.ruc']))?'':$filter['postulante.ruc'];
		$postulante_razonsocial=(!isset($filter['postulante.razonsocial']))?'':$filter['postulante.razonsocial'];
		$evaluador_nombres=(!isset($filter['evaluador.nombres']))?'':$filter['evaluador.nombres'];
		$evaluador_apellidos=(!isset($filter['evaluador.apellidos']))?'':$filter['evaluador.apellidos'];
		$evaluador_email=(!isset($filter['evaluador_email']))?'':$filter['evaluador_email'];
		$evaluador_profesion=(!isset($filter['evaluador.profesion']))?'':$filter['evaluador.profesion'];
		$evaluador_empresa=(!isset($filter['evaluador.empresa']))?'':$filter['evaluador.empresa'];
		$evaluador_celular=(!isset($filter['evaluador.celular']))?'':$filter['evaluador.celular'];
		$evaluador_direccion=(!isset($filter['evaluador.direccion']))?'':$filter['evaluador.direccion'];
		$onlyactive=$request->query('onlyactive','false');
		$onlyactive=($onlyactive=='true')?true:false;
		$ids=array();
		if($onlyactive){
			$ids=$this->OnlyActive();
		}

		$users = User::with('perfil')
		->select(DB::raw('users.*'))
		->with('postulante')
		->with('evaluador')
		->leftJoin('postulante','postulante.usuario_id','=','users.id')
		->leftJoin('evaluador','evaluador.usuario_id','=','users.id')

		->where(function($q) use($ids,$name,$username,$email,$group_id,$postulante_telefono,$postulante_ruc,$postulante_razonsocial,$evaluador_nombres,$evaluador_email,$evaluador_profesion,$evaluador_empresa,$evaluador_celular,$evaluador_apellidos,$evaluador_direccion){
			if(count($ids)>0){
				$q=$q->whereIn('users.id',$ids);
			}
			if($name!='')
				$q=$q->where('first_name','like','%'.$name.'%')->orWhere('last_name','like','%'.$name.'%');

			if($email!='')
				$q=$q->where('email','like',"%$email%");

			if($group_id!='')
				$q=$q->where('group_id','=',$group_id);

			if($username!='')
				$q=$q->where('username','like','%'.$username.'%');

			if($postulante_telefono!='')
				$q=$q->where('postulante.telefono','like','%'.$postulante_telefono.'%');

			if($postulante_ruc!='')
				$q=$q->where('postulante.ruc','like','%'.$postulante_ruc.'%');

			if($postulante_razonsocial!='')
				$q=$q->where('postulante.razonsocial','like','%'.$postulante_razonsocial.'%');

			if($evaluador_nombres!='')
				$q=$q->where('evaluador.nombres','like','%'.$evaluador_nombres.'%');

			if($evaluador_apellidos!='')
				$q=$q->where('evaluador.apellidos','like','%'.$evaluador_apellidos.'%');

			/*if($evaluador_email!='')
				$q=$q->where('evaluador.emailemp','like','%'.$evaluador_emailemp.'%');*/

			if($evaluador_email!='')
				$q=$q->where(function($query) use($evaluador_email){
					return $query->orWhere('evaluador.emailemp','like','%'.$evaluador_email.'%')
						->orWhere('evaluador.email1','like','%'.$evaluador_email.'%')
						->orWhere('evaluador.email2','like','%'.$evaluador_email.'%')
						->orWhere('email','like','%'.$evaluador_email.'%');
				});

			if($evaluador_empresa!='')
				$q=$q->where('evaluador.empresa','like','%'.$evaluador_empresa.'%');

			if($evaluador_profesion!='')
				$q=$q->where('evaluador.profesion','like','%'.$evaluador_profesion.'%');

			if($evaluador_celular!='')
				$q=$q->where('evaluador.celular','like','%'.$evaluador_celular.'%');

			if($evaluador_direccion!='')
				$q=$q->where('evaluador.direccion','like','%'.$evaluador_direccion.'%');

			return $q;
		})->paginate($request->query('count'));

		return Response()->json($users,200);
    }

    public function OnlyActive()
	{
		$entities=InscripcionEvaluador::select("evaluador.*")
            ->join('evaluador','evaluador.id','=','inscripcionevaluador.evaluador_id')
            ->join('concurso','concurso.id','=','inscripcionevaluador.concurso_id')
            ->where('concurso.estado','=','1')
            ->distinct()
            ->get();
        $ids=array();
        foreach ($entities as $entity) {
        	 $ids[]= $entity->usuario_id;
        }
        //var_dump($ids);die();
        return $ids;

	}
    public function show($id)
	{
		$user = User::with('perfil')
		->with(array('postulante'=>function($q){
			return $q->with('categorias')
				->with(array('contactos'=>function($q){
					return $q->with('TipoContacto');
				}));
		}))
		->with('evaluador')
		->findOrFail($id);

		return Response()->json($user,200);
    }

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
    public function update(Request $request, $id)
	{
		$message='';
		$success=false;
        $user = User::where('id','=',$id)->first();
        if($user){
            $validator = Validator::make($data = $request->all(), User::$update_rules);

            if (!$validator->fails())
            {

                $user->email=$data['email'];
                $user->first_name=$data['first_name'];
                $user->last_name=$data['last_name'];
                $user->nrodocumento=$data['nrodocumento'];
                $user->tipodocumento_id=$data['tipodocumento_id'];
                $user->username=$data['username'];

                if(array_key_exists('password', $data) && $data['password']!=''){
                    $data['password'] = crypt($data['password'], $this->salt);
                    $user->password= $data['password'];
                }
                $user->update();

                $success=true;
                $usuario=$user->toArray();

            }else{
                $message = $validator->messages();
            }

            return Response()->json(array('success' => $success, 'entity'=>$usuario,'message'=>$message), 201);
        }else{
            abort(404);
        }
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

    public function ResetPassword(Request $request){
		$email=$request->query('email');
		$message='';
		$success=false;

        $user=User::where('email','=',$email)->first();
        if($user){
            $user->reset_password_code =  uniqid('pwd_');
            $user->save();
            $success=true;
            Mail::to($email)->send(new MessageResetPassword($user));

        }else{
            $message="El correo ingresado no ha sido encontrado";
        }

		return Response()->json(array('success'=>$success,'message'=>$message, 'user'=>$email), 200);
    }


    public function findUserByPasswordCode(Request $request){
		$usuario=null;
		$message='';
		$success=false;
        $resetcode=$request->query('reset_password_code','');
        $user=User::where('reset_password_code','=',$resetcode)->first();
        if($user){
            $success=true;
        }else{
            $message='Usuario no encontrado.';
        }

		return Response()->json(array('success'=>$success,'message'=>$message,'entity'=>$user), 200);
	}

	public function ConfirmedResetPassword(Request $request){
		$message='';
		$success=false;
		$resetcode=$request->query('resetcode');
        $password=$request->query('password');
        $user=User::where('reset_password_code','=',$resetcode)->first();
        if($user){
            $user->password = crypt($password, $this->salt);
            $user->reset_password_code = null;
            $success=true;
            $user->save();
        }else{
            $message='Código de reseteo es invalido o a expirado';
        }

		return Response()->json(array('success'=>$success,'message'=>$message), 200);

    }

    public function ChangeUserPassword(Request $request){
		$username=$request->query('username');
		$current_password=$request->query('current_password');
		$password=$request->query('password');
		$confirm_password=$request->query('confirm_password');
		$success=false;

        if($password==$confirm_password){
            $user = User::where('username','=',$username)->where('password','=', crypt($current_password, $this->salt));
            if($user){
                $user->password=crypt($password, $this->salt);
                $message="Cambio de contraseña satisfactorio";
                $success=true;
            }else{
                $message='Usuario no encontrado.';
            }


        }else{
            $message="Contraseñas no son identicas";
        }

		return Response()->json(array('success'=>$success,'message'=>$message), 200);
    }

    public function logout(){
		$success=false;
		$message="";
		if (true)
		{
			$success=true;
		}else{
			$message="No existe un usuario logueado en el sistema";
		}

		return Response()->json(array('success' => $success, 'message'=>$message), 200);
    }

    public function destroy($id)
	{
		$msg='';
		$success=false;
		$user = User::with('perfil')
		->with('postulante')
		->with('evaluador')
		->find($id);


		if($user){
			$inscripciones=array();
			$inscripcionesEvaluador=array();

			if($user->group_id==3){
				$inscripciones=Inscripcion::where('postulante_id','=',$user->postulante->id)->get();

			}
			if($user->group_id==2){

				$inscripcionesEvaluador=InscripcionEvaluador::where('evaluador_id','=',$user->evaluador->id)->get();
			}

			if(count($inscripciones)>0 || count($inscripcionesEvaluador)>0){
				$msg="Usuario se encuentra vinculado a uno o mas concursos";
			}else{

				Postulante::where('usuario_id','=',$id)->delete();
				Evaluador::where('usuario_id','=',$id)->delete();
				User::destroy($id);
				$success=true;
			}
		}else{
			$msg="Usuario no existe";
		}




		return Response()->json(array('success' => $success,'message'=>$msg), 201);
    }

    public function activated(Request $request){
		$id=$request->query('id');
		$activated=($request->query('activated')=='true')?true:false;
		$success=false;
		$message='';
		$sendemail=false;

			$user = User::find($id);

			if($activated){
				$message='activated:true';
				switch($user->group_id){
					case 2:
						$evaluador=Evaluador::where('usuario_id','=',$id)->first();
						if(!$evaluador){
							$evaluador=new Evaluador();
							$evaluador->usuario_id=$id;
							$evaluador->nombres=$user->first_name;
							$evaluador->apellidos=$user->last_name;
							$evaluador->save();

							$user->activated=true;
							$user->activated_at=new Datetime();
							$sendemail=true;


						}
						break;
					case 3:
						$message='postulante';
						$postulante=Postulante::where('usuario_id','=',$id)->first();
						if(!$postulante){
							$postulante=new Postulante();
							$postulante->usuario_id=$id;
							$postulante->save();

							$user->activated=true;
							$user->activated_at=new Datetime();
							$sendemail=true;
						}

						break;
				}
				//$this->SendEmail();
				$user->activated=true;
				$user->activated_at=new Datetime();
				$success=true;

				if($sendemail){
                    Mail::to($email)->send(new MessageUserActivated($user));
				}
			}else{
				$success=true;
				$user->activated=false;
			}
			$user->save();


		return Response()->json(array('success' => $success, 'message'=>$message), 200);
	}


}
