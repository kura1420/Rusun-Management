<?php

namespace App;

use JeroenNoten\LaravelAdminLte\Menu\Filters\FilterInterface;

class MenuFilter implements FilterInterface
{
    public function transform($item)
    {
        $user = auth()->user();
        if (isset($item['can']) && ! $user->hasRole($item['can'])) {
            $item['restricted'] = true;
        }

        return $item;
    }
}
