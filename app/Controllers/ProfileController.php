<?php

namespace App\Controllers;

class ProfileController
{
    public static function indexAction(){
       echo render_template('profile');
    }

}