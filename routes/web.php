<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::resource('catalogo','CatalogosController');
Route::resource('group','GroupsController');

Route::resource('user','UsersController');
Route::resource('evaluador','EvaluadorsController');
Route::resource('concurso','ConcursosController');

Route::resource('grupoevaluacion','GrupoEvaluacionsController');
Route::resource('grupoevaluacionpostulante','GrupoEvaluacionPostulantesController');
Route::resource('grupoevaluacionevaluador','GrupoEvaluacionEvaluadorsController');

Route::resource('etapa','EtapasController');
Route::resource('etapaconcurso','EtapaConcursosController');
Route::resource('evaluacion','EvaluacionsController');
Route::resource('aspectoclave','AspectoClavesController');
Route::resource('respuesta','RespuestasController');
Route::resource('puntaje','PuntajesController');
Route::resource('criteriovisita','CriterioVisitasController');
Route::resource('criterioaspectoclave','CriterioAspectoClavesController');
Route::resource('evaluadorhistorial','EvaluadorHistorialsController');

Route::resource('encuestas', 'EncuestasController');
Route::resource('encuestapreguntas', 'EncuestaPreguntasController');
Route::resource('encuestapreguntaopciones', 'EncuestaPreguntaOpcionsController');
Route::resource('encuestaevaluaciones', 'EncuestaEvaluacionsController');
Route::resource('encuestaevaluacionrespuestas', 'EncuestaEvaluacionRespuestasController');
Route::resource('postulante','PostulantesController');
Route::resource('inscripcion','InscripcionsController');
Route::resource('menu','MenusController');
Route::resource('grupoevaluacionevaluadorpermiso','GrupoEvaluacionEvaluadorPermisosController');
Route::resource('concursocriterio','ConcursoCriteriosController');
Route::resource('postulantecategoria','PostulanteCategoriasController');
Route::resource('postulantecontacto','PostulanteContactosController');
Route::resource('conflictointeresevaluador','ConflictoInteresEvaluadorsController');
Route::resource('inscripcionevaluador','InscripcionEvaluadorsController');

//user custom endpoints
Route::get('/user/resetpassword/request', 'UsersController@ResetPassword');
Route::get('/user/resetpassword/confirmed', 'UsersController@ConfirmedResetPassword');
Route::get('/user/resetpassword/find', 'UsersController@findUserByPasswordCode');
Route::get('/user/login/signin', 'UsersController@signin');
Route::get('/user/validate/unique/{attribute}', 'UsersController@ValidateUnique');
Route::post('/user/access/activation', 'UsersController@activatedUser');
Route::get('/user/change/password', 'UsersController@ChangeUserPassword');
Route::get('/user/login/logout', 'UsersController@logout');
Route::get('/user/login/activated', 'UsersController@activated');

//concurso custom endpoints

Route::get('concurso/postulante/inscripcion','ConcursosController@concursosPostulante');
Route::get('concurso/evaluador/inscripcion','ConcursosController@concursosEvaluador');
Route::post('concurso/copy/criterios','ConcursosController@CopyCriterios');
Route::post('concurso/cierre/{id}','ConcursosController@CierreConcurso');




// grupoevaluación custom endpoints
Route::get('grupoevaluacionpostulante/disponible/{grupoevaluacion_id}','GrupoEvaluacionPostulantesController@PostulantesDisponibles');
Route::get('grupoevaluacionevaluador/disponible/{grupoevaluacion_id}','GrupoEvaluacionEvaluadorsController@EvaluadoresDisponibles');
Route::get('grupoevaluacionevaluador/grupos/{evaluador_id}','GrupoEvaluacionEvaluadorsController@GruposEvaluador');
Route::get('grupoevaluacionevaluador/grupo/lider','GrupoEvaluacionEvaluadorsController@Lider');

//concurso criterio custom endpoints
Route::get('concursocriterio/method/tree','ConcursoCriteriosController@Tree');
Route::get('concursocriterio/report/excel','ConcursoCriteriosController@reportExcel');

//criterioaspectosclave custom endpoints
Route::get('criterioaspectoclave/list/disponibles','CriterioAspectoClavesController@disponibles');

//evaluador custom endpoints
Route::get('evaluador/method/history','EvaluadorsController@History');

//reportes custom endpoints
Route::get('reportes/evaluacion-individual-cuaderno','ReportesController@getEvaluacionIndividualCuaderno');
Route::get('reportes/evaluacion-individual-factores-clave','ReportesController@getEvaluacionIndividualFactoresClave');
Route::get('reportes/evaluacion-individual-resumen','ReportesController@getEvaluacionIndividualResumen');
Route::get('reportes/evaluacion-individual-por-equipo','ReportesController@getEvaluacionIndividualPorEquipo');
Route::get('reportes/resumen-aprobacion-informe-ejecutivo','ReportesController@getResumenAprobacionInformeEjecutivo');
Route::get('reportes/resumen-aprobacion-concenso','ReportesController@getResumenAprobacionConcenso');
Route::post('reportes/export-excel','ReportesController@postExportExcel');
Route::post('reportes/export-word','ReportesController@postExportWord');
Route::get('reportes/evaluacion-factores-clave','ReportesController@getEvaluacionFactoresClave');
Route::get('reportes/seguimiento-evaluadores','ReportesController@getSeguimientoEvaluadores');
Route::get('reportes/evaluacion-consenso-por-equipo','ReportesController@getEvaluacionConsensoPorEquipo');
Route::get('reportes/evaluacion-temas-visita','ReportesController@getEvaluacionTemasVisita');
Route::get('reportes/evaluacion-por-equipo','ReportesController@getEvaluacionPorEquipo');
Route::get('reportes/informe-retroalimentacion','ReportesController@getInformeRetroalimentacion');
Route::get('reportes/seguimiento-encuesta','ReportesController@getSeguimientoEncuesta');

//impactoproyecto
Route::post('/impactoproyecto/methods/multiple-save', 'ImpactoproyectosController@multipleSave');

//upload
Route::post('upload/evaluador-curriculum','UploadController@postEvaluadorCurriculum');
Route::post('upload/inscripcion-completo','UploadController@postInscripcionCompleto');
Route::post('upload/inscripcion-basico','UploadController@postInscripcionBasico');
Route::post('upload/user-photo','UploadController@postUserPhoto');

//evaluación
Route::post('evaluacion/method/cierre','EvaluacionsController@CierreEvaluacion');
Route::post('evaluacion/method/abrir','EvaluacionsController@AbrirEvaluacion');
Route::post('evaluacion/method/import','EvaluacionsController@ImportDataEtapaAnterior');

//evaluador
Route::get('evaluador/method/history','EvaluadorsController@History');
