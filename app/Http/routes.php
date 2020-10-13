<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

// Route::get('/contrasena',function(){
//     dd(\Hash::make('002'));
// });
Route::get('/', 'Auth\AuthController@getLogin');
Route::get('auth/login', 'Auth\AuthController@getLogin');
Route::post('auth/login', 'Auth\AuthController@postLogin');
Route::get('auth/logout', 'Auth\AuthController@getLogout');
Route::controller('home', 'HomeController');
Route::controller('empresa', 'EmpresaController');
Route::controller('admin', 'AdminController');
Route::controller('adminsil', 'AdminSilController');
Route::controller('estudiante', 'EstudianteController');
Route::controller('jefe', 'JefeController');
Route::controller('tutor', 'TutorController');
Route::controller('novedad', 'NovedadController');
Route::controller('evaluacion', 'EvaluacionController');
Route::controller('juridica', 'JuridicaController');
Route::controller('programa', 'ProgramaController');
Route::controller('ori', 'OriController');
Route::controller('cdn', 'CoordinadorController');
Route::controller('graduado', 'GraduadoController');
Route::controller('sil', 'SilController');

Route::get('prueba', function () {
	return env('DB_PASSWORD');
	return view('prueba.card');
});

Route::get('aaa', function () {
	return Auth::user()->getuser->nombres . ' ' . Auth::user()->getuser->apellidos;
});
