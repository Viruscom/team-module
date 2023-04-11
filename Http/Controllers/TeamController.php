<?php

namespace Modules\Team\Http\Controllers;

use App\Actions\CommonControllerAction;
use App\Helpers\CacheKeysHelper;
use App\Helpers\FileDimensionHelper;
use App\Helpers\LanguageHelper;
use App\Helpers\MainHelper;
use App\Models\CategoryPage\CategoryPage;
use App\Models\CategoryPage\CategoryPageTranslation;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Cache;
use Modules\Team\Models\Team;
use Modules\Team\Models\TeamTranslation;

class TeamController extends Controller
{
    public function index()
    {
        if (is_null(Cache::get(CacheKeysHelper::$TEAM_ADMIN))) {
            Team::cacheUpdate();
        }

        return view('team::admin.index', ['teamMembers' => Cache::get(CacheKeysHelper::$TEAM_ADMIN)]);
    }
    public function create()
    {
        return view('team::admin.create', [
            'languages'     => LanguageHelper::getActiveLanguages(),
            'fileRulesInfo' => Team::getUserInfoMessage()
        ]);
    }
    public function store(Request $request, CommonControllerAction $action): RedirectResponse
    {
        if ($request->has('image')) {
            $request->validate(['image' => FileDimensionHelper::getRules('Team', 1)], FileDimensionHelper::messages('Team', 1));
        }
        $team = $action->doSimpleCreate(Team::class, $request);
        $action->updateUrlCache($team, TeamTranslation::class);
        Team::cacheUpdate();

        $team->storeAndAddNew($request);

        return redirect()->route('admin.team.index')->with('success-message', trans('admin.common.successful_create'));
    }
    public function edit($id)
    {
        $teamMember = Team::whereId($id)->with('translations')->first();
        MainHelper::goBackIfNull($teamMember);

        return view('team::admin.edit', [
            'teamMember'    => $teamMember,
            'languages'     => LanguageHelper::getActiveLanguages(),
            'fileRulesInfo' => Team::getUserInfoMessage()
        ]);
    }
    public function deleteMultiple(Request $request, CommonControllerAction $action): RedirectResponse
    {
        if (!is_null($request->ids[0])) {
            $action->deleteMultiple($request, Team::class);

            return redirect()->back()->with('success-message', 'admin.common.successful_delete');
        }

        return redirect()->back()->withErrors(['admin.common.no_checked_checkboxes']);
    }
    public function activeMultiple($active, Request $request, CommonControllerAction $action): RedirectResponse
    {
        $action->activeMultiple(Team::class, $request, $active);
        Team::cacheUpdate();

        return redirect()->back()->with('success-message', 'admin.common.successful_edit');
    }
    public function update($id, Request $request, CommonControllerAction $action): RedirectResponse
    {
        $teamMember = Team::whereId($id)->with('translations')->first();
        MainHelper::goBackIfNull($teamMember);

        $action->doSimpleUpdate(Team::class, TeamTranslation::class, $teamMember, $request);
        $action->updateUrlCache($teamMember, TeamTranslation::class);

        if ($request->has('image')) {
            $request->validate(['image' => FileDimensionHelper::getRules('Team', 1)], FileDimensionHelper::messages('Team', 1));
            $teamMember->saveFile($request->image);
        }

        Team::cacheUpdate();

        return redirect()->route('admin.team.index')->with('success-message', 'admin.common.successful_edit');
    }
    public function active($id, $active): RedirectResponse
    {
        $teamMember = Team::find($id);
        MainHelper::goBackIfNull($teamMember);

        $teamMember->update(['active' => $active]);
        Team::cacheUpdate();

        return redirect()->back()->with('success-message', 'admin.common.successful_edit');
    }
    public function delete($id, CommonControllerAction $action): RedirectResponse
    {
        $teamMember = Team::where('id', $id)->first();
        MainHelper::goBackIfNull($teamMember);

        $action->deleteFromUrlCache($teamMember);
        $action->delete(Team::class, $teamMember);

        return redirect()->back()->with('success-message', 'admin.common.successful_delete');
    }
    public function positionUp($id, CommonControllerAction $action): RedirectResponse
    {
        $teamMember = Team::whereId($id)->with('translations')->first();
        MainHelper::goBackIfNull($teamMember);

        $action->positionUp(Team::class, $teamMember);
        Team::cacheUpdate();

        return redirect()->back()->with('success-message', 'admin.common.successful_edit');
    }
    public function positionDown($id, CommonControllerAction $action): RedirectResponse
    {
        $teamMember = Team::whereId($id)->with('translations')->first();
        MainHelper::goBackIfNull($teamMember);

        $action->positionDown(Team::class, $teamMember);
        Team::cacheUpdate();

        return redirect()->back()->with('success-message', 'admin.common.successful_edit');
    }
    public function deleteImage($id, CommonControllerAction $action): RedirectResponse
    {
        $teamMember = Team::find($id);
        MainHelper::goBackIfNull($teamMember);

        if ($action->imageDelete($teamMember, Team::class)) {
            return redirect()->back()->with('success-message', 'admin.common.successful_delete');
        }

        return redirect()->back()->withErrors(['admin.image_not_found']);
    }
}
