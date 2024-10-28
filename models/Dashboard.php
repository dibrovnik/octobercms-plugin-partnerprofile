<?php

namespace Dibrovnik\Partnerprofile\Models;
use Dibrovnik\PartnerProfile\Models\Partner;

use Cms\Classes\Page as CmsPage;
use RainLab\Pages\Classes\Page as StaticPage;
use Cms\Classes\Theme;  // Добавляем импорт класса Theme
use Model;

/**
 * Model
 */
class Dashboard extends Model
{
    use \October\Rain\Database\Traits\Validation;
    public $attachOne = [
        'icon' => 'System\Models\File'
    ];

    /**
     * @var bool timestamps are disabled.
     * Remove this line if timestamps are defined in the database table.
     */
    public $timestamps = false;

    /**
     * @var string table in the database used by the model.
     */
    public $table = 'dibrovnik_partnerprofile_dashboard';

    /**
     * @var array rules for validation.
     */
    public $rules = [];

    public static function getDashBoardItems($user){
        $partner = Partner::where('user_id', $user->id)->first();
        $allDashboardItems =
            Dashboard::orderBy('sort_order', 'asc')->get();
        return $allDashboardItems;
    }
    
    // Метод для получения всех страниц для вывода их в дропдауне
    public function getPageOptions()
    {
        $options = [];

        // Получаем активную тему
        $theme = Theme::getActiveTheme();

        // Получаем список CMS страниц
        $cmsPages = CmsPage::listInTheme($theme);
        foreach ($cmsPages as $page) {
            $options[CmsPage::url($page->baseFileName)] = 'CMS: ' . $page->title;
        }

        return $options;
    }
}
