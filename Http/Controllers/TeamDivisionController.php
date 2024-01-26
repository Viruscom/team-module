<?php

    namespace Modules\Team\Http\Controllers;

    use App\Actions\CommonControllerAction;
    use App\Helpers\CacheKeysHelper;
    use App\Helpers\FileDimensionHelper;
    use App\Helpers\LanguageHelper;
    use App\Helpers\MainHelper;
    use Illuminate\Http\RedirectResponse;
    use Illuminate\Http\Request;
    use Illuminate\Routing\Controller;
    use Illuminate\Support\Facades\Cache;
    use Modules\Team\Http\Requests\TeamDivisionStoreRequest;
    use Modules\Team\Http\Requests\TeamDivisionUpdateRequest;
    use Modules\Team\Models\TeamDivision;
    use Modules\Team\Models\TeamDivisionTranslation;

    class TeamDivisionController extends Controller
    {
        public function index()
        {
            if (is_null(Cache::get(CacheKeysHelper::$TEAM_DIVISION_ADMIN))) {
                TeamDivision::cacheUpdate();
            }

            return view('team::admin.division.index', ['teamMembers' => Cache::get(CacheKeysHelper::$TEAM_DIVISION_ADMIN)]);
        }
        public function create()
        {
            return view('team::admin.division.create', [
                'languages'     => LanguageHelper::getActiveLanguages(),
                'fileRulesInfo' => TeamDivision::getUserInfoMessage()
            ]);
        }
        public function store(TeamDivisionStoreRequest $request, CommonControllerAction $action): RedirectResponse
        {
            if ($request->has('image')) {
                $request->validate(['image' => FileDimensionHelper::getRules('Team', 2)], FileDimensionHelper::messages('Team', 2));
            }
            $team = $action->doSimpleCreate(TeamDivision::class, $request);
            $action->updateUrlCache($team, TeamDivisionTranslation::class);
            $action->storeSeo($request, $team, 'Team');
            TeamDivision::cacheUpdate();

            if ($request->has('submitaddnew')) {
                return redirect()->back()->with('success-message', 'admin.common.successful_create');
            }

            return redirect()->route('admin.team.division.index')->with('success-message', trans('admin.common.successful_create'));
        }
        public function edit($id)
        {
            $teamMember = TeamDivision::whereId($id)->with('translations')->first();
            MainHelper::goBackIfNull($teamMember);

            return view('team::admin.division.edit', [
                'teamMember'    => $teamMember,
                'languages'     => LanguageHelper::getActiveLanguages(),
                'fileRulesInfo' => TeamDivision::getUserInfoMessage()
            ]);
        }
        public function deleteMultiple(Request $request, CommonControllerAction $action): RedirectResponse
        {
            if (!is_null($request->ids[0])) {
                $action->deleteMultiple($request, TeamDivision::class);

                return redirect()->back()->with('success-message', 'admin.common.successful_delete');
            }

            return redirect()->back()->withErrors(['admin.common.no_checked_checkboxes']);
        }
        public function activeMultiple($active, Request $request, CommonControllerAction $action): RedirectResponse
        {
            $action->activeMultiple(TeamDivision::class, $request, $active);
            TeamDivision::cacheUpdate();

            return redirect()->back()->with('success-message', 'admin.common.successful_edit');
        }
        public function active($id, $active): RedirectResponse
        {
            $teamMember = TeamDivision::find($id);
            MainHelper::goBackIfNull($teamMember);

            $teamMember->update(['active' => $active]);
            TeamDivision::cacheUpdate();

            return redirect()->back()->with('success-message', 'admin.common.successful_edit');
        }
        public function update($id, TeamDivisionUpdateRequest $request, CommonControllerAction $action): RedirectResponse
        {
            $teamMember = TeamDivision::whereId($id)->with('translations')->first();
            MainHelper::goBackIfNull($teamMember);

            $action->validateImage($request, 'Team', 2);
            $action->doSimpleUpdate(TeamDivision::class, TeamDivisionTranslation::class, $teamMember, $request);
            $action->updateUrlCache($teamMember, TeamDivisionTranslation::class);
            $action->updateSeo($request, $teamMember, 'Team');

            if ($request->has('image')) {
                $teamMember->saveFile($request->image);
            }

            TeamDivision::cacheUpdate();

            return redirect()->route('admin.team.division.index')->with('success-message', 'admin.common.successful_edit');
        }
        public function delete($id, CommonControllerAction $action): RedirectResponse
        {
            $teamMember = TeamDivision::where('id', $id)->first();
            MainHelper::goBackIfNull($teamMember);

            $action->deleteFromUrlCache($teamMember);
            $action->delete(TeamDivision::class, $teamMember);

            return redirect()->back()->with('success-message', 'admin.common.successful_delete');
        }
        public function positionUp($id, CommonControllerAction $action): RedirectResponse
        {
            $teamMember = TeamDivision::whereId($id)->with('translations')->first();
            MainHelper::goBackIfNull($teamMember);

            $action->positionUp(TeamDivision::class, $teamMember);
            TeamDivision::cacheUpdate();

            return redirect()->back()->with('success-message', 'admin.common.successful_edit');
        }
        public function positionDown($id, CommonControllerAction $action): RedirectResponse
        {
            $teamMember = TeamDivision::whereId($id)->with('translations')->first();
            MainHelper::goBackIfNull($teamMember);

            $action->positionDown(TeamDivision::class, $teamMember);
            TeamDivision::cacheUpdate();

            return redirect()->back()->with('success-message', 'admin.common.successful_edit');
        }
        public function deleteImage($id, CommonControllerAction $action): RedirectResponse
        {
            $teamMember = TeamDivision::find($id);
            MainHelper::goBackIfNull($teamMember);

            if ($action->imageDelete($teamMember, TeamDivision::class)) {
                return redirect()->back()->with('success-message', 'admin.common.successful_delete');
            }

            return redirect()->back()->withErrors(['admin.image_not_found']);
        }
    }
