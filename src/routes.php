<?php
$routes=\Config::get('login::routes');
$login=\Config::get('login::login');
$show=$login;
$process=$login;
//$access=$login.'/access';
$logout=\Config::get('login::logout').'';
\Route::get($show,array('as'=>'login.show','uses'=>'Mednix\\Login\\Controllers\\LoginController@showForm'));
\Route::post($process,array('as'=>'login.process','uses'=>'Mednix\\Login\\Controllers\\LoginController@processForm'));
//\Route::any($access,array('as'=>'login.access','uses'=>'Mednix\\Login\\Controllers\\LoginController@accessControl'));
\Route::any($logout,array('as'=>'login.access','uses'=>'Mednix\\Login\\Controllers\\LoginController@logout'));


\Route::when($routes,'loginFilter1');
\Route::when($login,'loginFilter2');
//\Route::any(\Config::get('login::routes'),array('before'=>'loginFilter','uses'=>'Mednix\\Login\\Controllers\\LoginController@processForm'));