<?php

namespace App\Http\Controllers;

use App\Models\Quyen;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Auth;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function checkRule_get($id)
    {
        $admin       = Auth::guard('admin')->user();
        if ($admin->is_master) {
            return true;
        }
        $groupAdmin  = Quyen::find($admin->id_quyen);
        if ($groupAdmin) {
            $listRule    = explode(',', $groupAdmin->list_rule);
            return in_array($id, $listRule);
        } else {
            return false;
        }
    }

    public function checkRule_post($id)
    {
        $admin       = Auth::guard('admin')->user();
        if ($admin->is_master) {
            return true;
        }
        $groupAdmin  = Quyen::find($admin->id_quyen);
        if ($groupAdmin) {
            $listRule    = explode(',', $groupAdmin->list_rule);
            return in_array($id, $listRule);
        } else {
            return false;
        }
    }
}
