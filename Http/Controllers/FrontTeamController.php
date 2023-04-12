<?php

namespace Modules\Team\Http\Controllers;

use App\Helpers\CacheKeysHelper;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Cache;
use Modules\Team\Models\Team;

class FrontTeamController extends Controller
{
    public function index()
    {
        if (is_null(Cache::get(CacheKeysHelper::$TEAM_DIVISION_FRONT))) {
            Team::cacheUpdate();
        }

        return view('team::front.list_team', ['teamMembers' => Cache::get(CacheKeysHelper::$TEAM_DIVISION_FRONT)]);
    }
}
