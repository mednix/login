<?php

namespace Mednix\Login\Controllers;


use Illuminate\Support\MessageBag;

class LoginController extends \BaseController {

    protected $route;
    function __construct()
    {
        $layout=\Config::get('login::layout');
        $this->layout=$layout;
    }

    function accessControl(){

            if(\Auth::check()){

            }
            else{
                $this->layout->content= \View::make('login::login');
            }

    }
    function showForm(){
        $this->layout->content=  \View::make('login::login',\Input::old());
    }
    function processForm(){
        $redirect=\Config::get('login::redirect');
        if(\Auth::attempt(array('username'=>\Input::get('username'),'password'=>\Input::get('password')))){
            return \Redirect::to($redirect);
        }
        $errors=new MessageBag(array('msg'=>[
            'Failed to login!'
        ]));

        $this->layout->content=  \View::make('login::login',array('errors'=>$errors));
    }
    function logout(){

        \Auth::logout();
        return \Redirect::to('/');
    }

} 
