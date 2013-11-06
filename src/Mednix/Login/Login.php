<?php
namespace Mednix\Login;

class Login {
    protected $layout;
    function __construct($layout)
    {
        $this->setLayout($layout);
    }

    /**
     * @param mixed $layout
     */
    public function setLayout($layout)
    {
        $this->layout = $layout;
    }

    /**
     * @return mixed
     */
    public function getLayout()
    {
        return $this->layout;
    }



}