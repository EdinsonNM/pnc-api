<?php
namespace App\Http\Controllers;

use Validator;
use App\models\Catalogo;
use App\models\Inscripcion;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class UploadController extends Controller {
	protected $urlEvaluador="upload/evaluador/";
	protected $urlPostulante="upload/postulante/";
	protected $urlUser="upload/user";
	public function postEvaluadorCurriculum(Request $request){
		$success=false;
		$message='';
		set_time_limit (240);
		ini_set('upload_max_filesize', '12M');
		ini_set('memory_limit', '96M');
		ini_set('post_max_size', '64M');
		$file = $request->file('file');
		$evaluador_id=$request->input('evaluador_id');
		$size = $file->getSize();
		$extension =$file->getClientOriginalExtension();

		$extensions = array("doc", "pdf", "docx");
		if (in_array($extension, $extensions)) {
			$destinationPath = $this->urlEvaluador."curriculum";

			$filename = 'curriculum_'.$evaluador_id.'.'.$extension;

			$upload_success = $file->move($destinationPath, $filename);
			if($upload_success){
				$evaluador=Evaluador::find($evaluador_id);
				$evaluador->curriculum=$filename;
				$evaluador->save();
				$success=true;
			}else{
				$message="Ocurrio un error al cargar el documento";
			}
		}else{
			$message="Extensión de archivo no permitida";
		}


		return Response()->json(array('success'=>$success,'message'=>$message,'name'=>$filename), 200);

	}

	public function postInscripcionCompleto(Request $request){
		$success=false;
		$message='';
		set_time_limit (240);
		ini_set('upload_max_filesize', '12M');
		ini_set('memory_limit', '96M');
		ini_set('post_max_size', '64M');

		$file = $request->file('file');
		$inscripcion_id=$request->input('inscripcion_id');
		$size = $file->getSize();
		$extension =$file->getClientOriginalExtension();

		$extensions = array("doc", "pdf", "docx");
		if (in_array($extension, $extensions)) {
			$destinationPath = $this->urlPostulante."inscripcion";

			$filename = 'informe_completo_'.$inscripcion_id.'.'.$extension;

			$upload_success = $file->move($destinationPath, $filename);
			if($upload_success){
				$inscripcion=Inscripcion::find($inscripcion_id);
				$inscripcion->informepostulacionc=$filename;
				$inscripcion->save();
				$success=true;
			}else{
				$message="Ocurrio un error al cargar el documento";
			}
		}else{
			$message="Extensión de archivo no permitida";
		}

		$rpta['success']=$success;
		$rpta['message']=$message;
		$rpta['size']=$size;

		return Response()->json($rpta, 200);

	}

	public function postInscripcionBasico(Request $request){
		$success=false;
		$message='';
		set_time_limit (240);
		ini_set('upload_max_filesize', '12M');
		$file = $request->file('file');
		$inscripcion_id=$request->input('inscripcion_id');
		$size = $file->getSize();
		$extension =$file->getClientOriginalExtension();

		$extensions = array("doc", "pdf", "docx");
		if (in_array($extension, $extensions)) {
			$destinationPath = $this->urlPostulante."inscripcion";

			$filename = 'informe_basico_'.$inscripcion_id.'.'.$extension;

			$upload_success = $file->move($destinationPath, $filename);
			if($upload_success){
				$inscripcion=Inscripcion::find($inscripcion_id);
				$inscripcion->informepostulacionsic=$filename;
				$inscripcion->save();
				$success=true;
			}else{
				$message="Ocurrio un error al cargar el documento";
			}
		}else{
			$message="Extensión de archivo no permitida";
		}


		$rpta['success']=$success;
		$rpta['message']=$message;
		$rpta['size']=$size;

		return Response()->json($rpta, 200);

	}

	public function postUserPhoto(Request $request){
		$success=false;
		$message='';
		set_time_limit (240);
		ini_set('upload_max_filesize', '200M');
		$file = $request->file('file');
		$id=$request->input('id');
		$size = $file->getSize();
		$extension =$file->getClientOriginalExtension();

		$extensions = array("jpg", "jpeg", "png","bmp");
		if (in_array($extension, $extensions)) {
			$destinationPath = $this->urlUser;

			$filename = 'user_'.$id.'.'.$extension;

			$upload_success = $file->move($destinationPath, $filename);
			if($upload_success){
				$user=User::find($id);
				$user->imagen=$filename;
				$user->save();
				$success=true;
			}else{
				$message="Ocurrio un error al cargar el documento";
			}
		}else{
			$message="Extensión de archivo no permitida";
		}


		return Response()->json(array('success' => $success, 'message'=>$message,'name'=>$filename), 200);

	}
}
