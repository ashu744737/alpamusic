<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Auth;

class Setting extends Model
{
    public $table = 'settings';

    public $fillable = ['key', 'value'];

    //
    public static function setup_app_config()
    {
        Config::set('constants.company_name', 'Soft-L Ltd');
        Config::set('constants.app_title', "Uvda");

        // Set Language
        $lang = 'hr';
        if (session()->has('locale')) {
            App::setlocale(session()->get('locale'));
        } else {
            App::setLocale($lang);
            session()->put('locale', $lang);
        }
    }
}
