<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin\MenuUrl;

class MenuController extends Controller
{
    public function getMenu(){
        $menu = MenuUrl::getMenu();
        return response()->json($menu);
    }

}
