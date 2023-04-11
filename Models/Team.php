<?php

namespace Modules\Team\Models;

use App\Actions\CommonControllerAction;
use App\Helpers\CacheKeysHelper;
use App\Interfaces\Models\CommonModelInterface;
use App\Interfaces\PositionInterface;
use App\Traits\CommonActions;
use App\Traits\Scopes;
use App\Traits\StorageActions;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Model;

class Team extends Model implements TranslatableContract, CommonModelInterface, PositionInterface
{
    use Translatable, StorageActions, Scopes, CommonActions;

    public const FILES_PATH = "images/team";

    public static string $TEAM_SYSTEM_IMAGE  = "team_1_image.png";
    public static string $TEAM_RATIO         = '3/2';
    public static string $TEAM_MIMES         = 'jpg,jpeg,png,gif';
    public static string $TEAM_MAX_FILE_SIZE = '3000';

    public array         $translatedAttributes        = ['title', 'url', 'announce', 'description', 'visible', 'title_additional_1', 'title_additional_2', 'title_additional_3',
                                                         'title_additional_4', 'title_additional_5', 'title_additional_6', 'text_additional_1', 'text_additional_2',
                                                         'text_additional_3', 'text_additional_4', 'text_additional_5', 'text_additional_6'];
    protected            $table                       = "pages";
    protected            $fillable                    = ['category_page_id', 'from_price', 'price', 'from_date', 'to_date', 'show_in_homepage', 'active', 'position', 'creator_user_id', 'show_in_homepage_type', 'one_day_event', 'one_day_event_date', 'filename', 'filename_box2', 'filename_box3', 'in_ad_box', 'product_id'];

    public static function cacheUpdate(): void
    {
        cache()->forget(CacheKeysHelper::$TEAM_ADMIN);
        cache()->forget(CacheKeysHelper::$TEAM_FRONT);
        cache()->remember(CacheKeysHelper::$TEAM_ADMIN, config('default.app.cache.ttl_seconds'), function () {
            return self::with('translations')->withTranslation()->orderBy('position')->get();
        });
        cache()->rememberForever(CacheKeysHelper::$TEAM_FRONT, function () {
            return self::active(true)->with('translations')->withTranslation()->orderBy('position')->get();
        });

    }
    public static function getRequestData($request)
    {
        // TODO: Implement getRequestData() method.
    }
    public static function generatePosition($request)
    {
        // TODO: Implement generatePosition() method.
    }
    public static function getLangArraysOnStore($data, $request, $languages, $modelId, $isUpdate)
    {
        // TODO: Implement getLangArraysOnStore() method.
    }
    public function positionUp($id, CommonControllerAction $action)
    {
        // TODO: Implement positionUp() method.
    }
    public function positionDown($id, CommonControllerAction $action)
    {
        // TODO: Implement positionDown() method.
    }
}
