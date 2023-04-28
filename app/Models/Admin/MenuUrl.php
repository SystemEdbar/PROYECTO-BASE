<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MenuUrl extends Model
{
    use HasFactory;
    protected $table = "menu_url";
    protected $guarded = ['id'];
    public $timestamps = false;
    public function getHijos($padres, $line)
    {
        $children = [];
        foreach ($padres as $line1) {
            if ($line['id'] == $line1['father']) {
                $children = array_merge($children, [array_merge($line1, ['children' => $this->getHijos($padres, $line1)])]);
            }
        }
        return $children;
    }

    public function getPadres()
    {
        return $this->orderby('father')
            ->orderby('order')
            ->get()
            ->toArray();
    }

    public static function getMenu()
    {
        $menus = new MenuUrl();
        $padres = $menus->getPadres();
        $menuAll = [];
        foreach ($padres as $line) {
            if ($line['father'] != 0)
                break;
            $item = [array_merge($line, ['children' => $menus->getHijos($padres, $line)])];
            $menuAll = array_merge($menuAll, $item);
        }
        return $menuAll;
    }

}
