<?php

    namespace Modules\Team\Database\Seeders;

    use App\Actions\CommonControllerAction;
    use App\Helpers\LanguageHelper;
    use App\Helpers\UrlHelper;
    use App\Models\SpecialPage\SpecialPage;
    use App\Models\SpecialPage\SpecialPageTranslation;
    use Illuminate\Database\Seeder;
    use Illuminate\Http\Request;

    class TeamSpecialPageTableSeeder extends Seeder
    {
        public function run(CommonControllerAction $action)
        {

            $activeLanguages = LanguageHelper::getActiveLanguages();

            $teamSpecialPage             = SpecialPage::create(['type' => SpecialPage::TYPE_TEAM_PAGE, 'active' => true]);
            $homePageRequest             = new Request();
            $homePageRequest['module']   = 'SpecialPage';
            $homePageRequest['model']    = get_class($teamSpecialPage);
            $homePageRequest['model_id'] = $teamSpecialPage->id;

            foreach ($activeLanguages as $language) {
                SpecialPageTranslation::create([
                                                   'special_page_id' => $teamSpecialPage->id,
                                                   'locale'          => $language->code,
                                                   'title'           => 'Екип',
                                                   'url'             => UrlHelper::generate('Екип', SpecialPageTranslation::class, $teamSpecialPage->id, false),
                                                   'announce'        => '',
                                                   'description'     => '',
                                                   'visible'         => true,
                                               ]);
                $homePageRequest['seo_title_' . $language->code] = 'Екип';
            }

            $action->storeSeo($homePageRequest, $teamSpecialPage, 'SpecialPage');
        }
    }
