<?php

namespace Modules\Team\Models;

use App\Helpers\AdminHelper;
use App\Helpers\CacheKeysHelper;
use App\Helpers\FileDimensionHelper;
use App\Helpers\SeoHelper;
use App\Interfaces\Models\CommonModelInterface;
use App\Interfaces\Models\ImageModelInterface;
use App\Models\Seo;
use App\Traits\CommonActions;
use App\Traits\HasGallery;
use App\Traits\HasModelRatios;
use App\Traits\Scopes;
use App\Traits\StorageActions;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class Team extends Model implements TranslatableContract, CommonModelInterface, ImageModelInterface
{
    use Translatable, Scopes, StorageActions, CommonActions, HasGallery, HasModelRatios;

    public const FILES_PATH = "images/team";

    public static string $TEAM_SYSTEM_IMAGE = "team_1_image.png";

    public array $translatedAttributes = ['title', 'url', 'announce', 'description', 'visible'];
    protected    $table                = "team";
    protected    $fillable             = ['email', 'phone', 'filename', 'position', 'active', 'division_id'];

    public static function cacheUpdate(): void
    {
        cache()->forget(CacheKeysHelper::$TEAM_ADMIN);
        cache()->forget(CacheKeysHelper::$TEAM_FRONT);
        cache()->rememberForever(CacheKeysHelper::$TEAM_ADMIN, function () {
            return self::with('translations')->orderBy('position')->get();
        });
        cache()->rememberForever(CacheKeysHelper::$TEAM_FRONT, function () {
            return self::active(true)->with('translations')->orderBy('position')->get();
        });
    }

    public static function getRequestData($request)
    {
        if ($request->has('email')) {
            $data['email'] = $request->email;
        }

        if ($request->has('phone')) {
            $data['phone'] = $request->phone;
        }

        if ($request->has('division_id')) {
            $data['division_id'] = $request->division_id;
        }

        $data['active'] = false;
        if ($request->has('active')) {
            $data['active'] = filter_var($request->active, FILTER_VALIDATE_BOOLEAN);
        }

        if ($request->has('filename')) {
            $data['filename'] = $request->filename;
        }

        if ($request->hasFile('image')) {
            $data['filename'] = pathinfo(CommonActions::getValidFilenameStatic($request->image->getClientOriginalName()), PATHINFO_FILENAME) . '.' . $request->image->getClientOriginalExtension();
        }

        return $data;
    }

    public static function getLangArraysOnStore($data, $request, $languages, $modelId, $isUpdate)
    {
        foreach ($languages as $language) {
            $data[$language->code] = TeamTranslation::getLanguageArray($language, $request, $modelId, $isUpdate);
        }

        return $data;
    }

    public static function getFileRules(): string
    {
        return FileDimensionHelper::getRules('Team', 1);
    }

    public static function getUserInfoMessage(): string
    {
        return FileDimensionHelper::getUserInfoMessage('Team', 1);
    }

    public static function allocateModule($viewArray)
    {
        switch (class_basename($viewArray['currentModel']->parent)) {
            case 'Team':
                return view('team::front.show', ['viewArray' => $viewArray]);
            case 'TeamDivision':
                return view('team::front.list_team', ['viewArray' => $viewArray]);
            default:
                abort(404);
        }
    }

    public static function getTeamSpecialPage($viewArray)
    {
        return view('team::front.team_special_page', [
            'viewArray' => $viewArray,
            'divisions' => TeamDivision::where('active', true)->orderBy('position', 'asc')->with('members')->get()
        ]);
    }

    public function setKeys($array): array
    {
        $array[1]['sys_image_name'] = trans('team::admin.team.index');
        $array[1]['sys_image']      = self::$TEAM_SYSTEM_IMAGE;
        $array[1]['sys_image_path'] = AdminHelper::getSystemImage(self::$TEAM_SYSTEM_IMAGE);
        $array[1]['field_name']     = 'team';
        $array[1]['ratio']          = self::getModelRatio('team');
        $array[1]['mimes']          = self::getModelMime('team');
        $array[1]['max_file_size']  = self::getModelMaxFileSize('team');
        $array[1]['file_rules']     = 'mimes:' . self::getModelMime('team') . '|size:' . self::getModelMaxFileSize('team') . '|dimensions:ratio=' . self::getModelRatio('team');

        $array[2]['sys_image_name'] = trans('team::admin.team_division.index');
        $array[2]['sys_image']      = TeamDivision::$TEAM_DIVISION_SYSTEM_IMAGE;
        $array[2]['sys_image_path'] = AdminHelper::getSystemImage(TeamDivision::$TEAM_DIVISION_SYSTEM_IMAGE);
        $array[2]['field_name']     = 'team_division';
        $array[2]['ratio']          = self::getModelRatio('team_division');
        $array[2]['mimes']          = self::getModelMime('team_division');
        $array[2]['max_file_size']  = self::getModelMaxFileSize('team_division');
        $array[2]['file_rules']     = 'mimes:' . self::getModelMime('team_division') . '|size:' . self::getModelMaxFileSize('team_division') . '|dimensions:ratio=' . self::getModelRatio('team_division');

        return $array;
    }

    public function getSystemImage(): string
    {
        return AdminHelper::getSystemImage(self::$TEAM_SYSTEM_IMAGE);
    }

    public function getFilepath($filename): string
    {
        return $this->getFilesPath() . $filename;
    }

    public function getFilesPath(): string
    {
        return self::FILES_PATH . '/' . $this->id . '/';
    }

    public function getAnnounce(): string
    {
        return Str::limit($this->announce, 255, ' ...');
    }

    public function getEncryptedPath($moduleName): string
    {
        return encrypt($moduleName . '-' . get_class($this) . '-' . $this->id);
    }

    public function headerGallery()
    {
        return $this->getHeaderGalleryRelation(get_class($this));
    }

    public function mainGallery()
    {
        return $this->getMainGalleryRelation(get_class($this));
    }

    public function additionalGalleryOne()
    {
        return $this->getAdditionalGalleryOneRelation(get_class($this));
    }

    public function additionalGalleryTwo()
    {
        return $this->getAdditionalGalleryTwoRelation(get_class($this));
    }

    public function additionalGalleryThree()
    {
        return $this->getAdditionalGalleryThreeRelation(get_class($this));
    }

    public function additionalGalleryFour()
    {
        return $this->getAdditionalGalleryFourRelation(get_class($this));
    }

    public function additionalGalleryFive()
    {
        return $this->getAdditionalGalleryFiveRelation(get_class($this));
    }

    public function additionalGallerySix()
    {
        return $this->getAdditionalGallerySixRelation(get_class($this));
    }

    public function seoFields()
    {
        return $this->hasOne(Seo::class, 'model_id')->where('model', get_class($this));
    }

    public function seo($languageSlug)
    {
        $seo = $this->seoFields;
        if (is_null($seo)) {
            return null;
        }
        SeoHelper::setSeoFields($this, $seo->translate($languageSlug));
    }

    public function division(): BelongsTo
    {
        return $this->belongsTo(TeamDivision::class, 'division_id', 'id');
    }

    public function getUrl($languageSlug)
    {
        return url($languageSlug . '/' . $this->url);
    }

    public function getPreviousAndNext($languageSlug)
    {
        $previous = $this->where('id', '<', $this->id)
            ->where('division_id', $this->division_id)
            ->where('active', true)
            ->with('translations')
            ->orderBy('position', 'asc')
            ->first();
        if (!is_null($previous)) {
            $previous = $previous->getUrl($languageSlug);
        }

        $next = $this->where('id', '>', $this->id)
            ->where('division_id', $this->division_id)
            ->where('active', true)
            ->with('translations')
            ->orderBy('position', 'asc')
            ->first();
        if (!is_null($next)) {
            $next = $next->getUrl($languageSlug);
        }

        return [
            'previous' => $previous,
            'next'     => $next,
        ];
    }
}
