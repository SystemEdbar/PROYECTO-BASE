<?php

class general
{
    public function setActive($url): string
    {
        if (request()->is($url)||request()->is($url. '/*')){
            return 'active';
        } else {
            return '';
        }
    }
    public function checkPermission($permission, $children): bool
    {   $check = false;
        foreach ($children as $child){
            if ($permission->pluck('name')->contains('show '.$child['link'])){
                $check = true;
                break;
            }
        }
        return $check;
    }
}



