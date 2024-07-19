<?php

    namespace Modules\Team\Models;

    use App\Helpers\AdminHelper;
    use App\Helpers\CacheKeysHelper;
    use App\Helpers\FileDimensionHelper;
    use App\Helpers\SeoHelper;
    use App\Interfaces\Models\CommonModelInterface;
    use App\Models\Seo;
    use App\Traits\CommonActions;
    use App\Traits\HasGallery;
    use App\Traits\Scopes;
    use App\Traits\StorageActions;
    use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
    use Astrotomic\Translatable\Translatable;
    use Illuminate\Database\Eloquent\Model;
    use Illuminate\Database\Eloquent\Relations\HasMany;
    use Illuminate\Support\Str;

    class TeamDivision extends Model implements TranslatableContract, CommonModelInterface
    {
        use Translatable, Scopes, StorageActions, CommonActions, HasGallery;

        public const FILES_PATH = "images/team/division";

        public static string $TEAM_DIVISION_SYSTEM_IMAGE  = "team_2_image.png";
        public static string $TEAM_DIVISION_RATIO         = '1/1';
        public static string $TEAM_DIVISION_MIMES         = 'jpg,jpeg,png,gif,webp';
        public static string $TEAM_DIVISION_MAX_FILE_SIZE = '3000';

        public array $translatedAttributes = ['title', 'url', 'announce', 'description', 'visible'];
        protected    $table                = "team_division";
        protected    $fillable             = ['email', 'phone', 'filename', 'position', 'active'];

        public static function cacheUpdate(): void
        {
            cache()->forget(CacheKeysHelper::$TEAM_DIVISION_ADMIN);
            cache()->forget(CacheKeysHelper::$TEAM_DIVISION_FRONT);
            cache()->rememberForever(CacheKeysHelper::$TEAM_DIVISION_ADMIN, function () {
                return self::with('translations')->withTranslation()->orderBy('position')->get();
            });
            cache()->rememberForever(CacheKeysHelper::$TEAM_DIVISION_FRONT, function () {
                return self::active(true)->with('translations')->withTranslation()->orderBy('position')->get();
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
                $data[$language->code] = TeamDivisionTranslation::getLanguageArray($language, $request, $modelId, $isUpdate);
            }

            return $data;
        }

        public static function getFileRules(): string
        {
            return FileDimensionHelper::getRules('Team', 2);
        }

        public static function getUserInfoMessage(): string
        {
            return FileDimensionHelper::getUserInfoMessage('Team', 2);
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

        public function getSystemImage(): string
        {
            return AdminHelper::getSystemImage(self::$TEAM_DIVISION_SYSTEM_IMAGE);
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

        public function members(): HasMany
        {
            return $this->hasMany(Team::class, 'division_id', 'id')->where('active', true)->orderBy('position');
        }
    }
