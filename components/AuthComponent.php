<?php

namespace Dibrovnik\PartnerProfile\Components;

use Cms\Classes\ComponentBase;

use Auth;
use ApplicationException;
use File;
use Input;
use Log;


class AuthComponent extends ComponentBase
{
    public function componentDetails()
    {
        return [
            'name' => 'Auth',
            'description' => 'Компонент для корректной отрисовки компонентов авторизации из плагина Rainlab.User'
        ];
    }  

    // Метод для отрисовки модалки регистрации
    public function onLoadRegisterModalContent() 
    {
        return ['#siteModalContent' => $this->renderPartial('registration-modal')];
    }

    // Метод для отрисовки модалки логина
    public function onLoadLoginModalContent()
    {
        return ['#siteModalContent' => $this->renderPartial('login-modal')];
    }

}
