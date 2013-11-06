<?php
 \Route::filter('loginFilter1',function(){
    if(\Auth::check()){
        //Allow access
    }
    else{
        return \Redirect::route('login.show');
    }


 });
\Route::filter('loginFilter2',function(){
    $redirect=\Config::get('login::redirect');
    if(\Auth::check()){
        return \Redirect::to($redirect);
    }
    else{
        //Allow to Show login form
    }


});