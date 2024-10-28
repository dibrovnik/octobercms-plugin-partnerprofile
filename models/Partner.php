<?php

namespace Dibrovnik\Partnerprofile\Models;

use Model;
use File;
use Log;

class Partner extends Model
{
    use \October\Rain\Database\Traits\Validation;

    /**
     * @var string table in the database used by the model.
     */
    public $table = 'dibrovnik_partnerprofile_partner';

    /**
     * @var array rules for validation.
     */
    public $rules = [
        'user_id' => 'required|unique:dibrovnik_partnerprofile_partner'
    ];
    public $customMessages = [
        'user_id.unique' => 'Партнер уже существует',
    ];

    // Указываем связь с таблицей пользователей
    public $belongsTo = [
        'user' => ['RainLab\User\Models\User', 'key' => 'user_id'],
    ];

    public $belongsToMany = [
        'taxonomyOptions' => [
            'Dibrovnik\PartnerProfile\Models\TaxonomyOption',
            'table' => 'dibrovnik_partnerprofile_partner_taxonomy',
            'key' => 'partner_id',
            'otherKey' => 'taxonomy_option_id',
        ]
    ];

    public $attachOne = [
        'avatarPhoto' => \System\Models\File::class
    ];

    public $attachMany = [
        'portfolioPhotos' => \System\Models\File::class
    ];

    /**
     * Получает пользователей, которые не являются партнерами.
     * 
     * @return array - Список пользователей, не являющихся партнерами.
     */
    public function getUsersNoPartners()
    {
        $partnerUserIds = \Dibrovnik\Partnerprofile\Models\Partner::pluck('user_id')->toArray();
        $users = \RainLab\User\Models\User::all()->pluck('email', 'id')->toArray();
        return $users;
    }

    /**
     * Получает партнера по ID пользователя или по объекту пользователя.
     * 
     * @param mixed $user - Объект пользователя или ID.
     * @return Partner|null - Объект партнера или null, если не найден.
     */
    public static function getPartnerByUser($user)
    {
        $userId = is_object($user) ? $user->id : $user;
        return self::where('user_id', $userId)->first();
    }

    /**
     * Сбор данных о партнере для отображения.
     * 
     * @param Partner $partner - Объект партнера.
     * @return array|null - Массив данных партнера или null.
     */
    public static function getPartnerData($partner)
    {
        if ($partner) {
            $data = [
                'title' =>  Partner::getPartnerFieldValue($partner, 'title'),
                'city' => json_decode(Partner::getPartnerFieldValue($partner, 'city'), true)[1] ?? '',
                'description' => Partner::getPartnerFieldValue($partner, 'description'),
                'short_description' => 'short description Lorem ipsum...',
                'email' => Partner::getPartnerFieldValue($partner, 'email'), 
                'phone' => Partner::getPartnerFieldValue($partner, 'tel'),
                'portfolioPhotos' => $partner->portfolioPhotos,
                'prices' => Partner::getPartnerPrices($partner, false),
                'is_verificated' => 1,
                'nickname' => 'nickname',
            ];
            return $data;
        }
        return null;
    }

    /**
     * Возвращает цены партнера в формате JSON или массива.
     * 
     * @param Partner $partner - Объект партнера.
     * @param bool $json - Если true, возвращает JSON; иначе массив.
     * @return mixed - Цены партнера или null, если не найдено.
     */
    public static function getPartnerPrices($partner, $json = true)
    {
        if ($partner) {
            return $json ? $partner->prices : json_decode($partner->prices, true);
        }
        return null; 
    }

    /**
     * Возвращает URL партнера на сайте.
     * 
     * @param Partner $partner - Объект партнера.
     * @return string|null - URL профиля партнера или null.
     */
    public static function getPartnerUrl($partner)
    {
        return $partner ? url('/partner/' . $partner->id) : null;
    }

    /**
     * Получает значение конкретного поля партнера.
     * 
     * @param Partner $partner - Объект партнера.
     * @param string $fieldName - Имя поля.
     * @return mixed|null - Значение поля или null, если не найдено.
     */
    public static function getPartnerFieldValue($partner, $fieldName)
    {
        $field = Field::where('name', $fieldName)->first();
        if (!$field) {
            return null;
        }

        $fieldValue = FieldValue::where('user_id', $partner->user_id)
            ->where('field_id', $field->id)
            ->first();

        return $fieldValue ? $fieldValue->value : null;
    }

    /**
     * Получает список всех партнеров.
     * 
     * @return Collection - Коллекция всех партнеров.
     */
    public static function getAllPartners()
    {
        return Partner::all();
    }

    /**
     * Получает вертикальные фотографии из портфолио партнера.
     * 
     * @param Partner $partner - Объект партнера.
     * @return Collection - Коллекция вертикальных фотографий.
     */
    public static function getVerticalPortfolioPhotos($partner)
    {
        return $partner->portfolioPhotos->filter(function ($photo) {
            $imagePath = base_path(trim(parse_url($photo->path, PHP_URL_PATH), '/'));
            if (file_exists($imagePath)) {
                list($width, $height) = getimagesize($imagePath);
                return $height > $width;
            }
            return false;
        });
    }

    /**
     * Получает горизонтальные фотографии из портфолио партнера.
     * 
     * @param Partner $partner - Объект партнера.
     * @return Collection - Коллекция горизонтальных фотографий.
     */
    public static function getGorizontalPortfolioPhotos($partner)
    {
        return $partner->portfolioPhotos->filter(function ($photo) {
            $imagePath = base_path(trim(parse_url($photo->path, PHP_URL_PATH), '/'));
            if (file_exists($imagePath)) {
                list($width, $height) = getimagesize($imagePath);
                return $width > $height;
            }
            return false;
        });
    }

    /**
     * Устанавливает таксономии для партнера, удаляя старые и добавляя новые.
     * 
     * @param Partner $partner - Объект партнера.
     * @param array $taxonomy_options - Массив опций таксономии.
     */
    public static function setPartnerTaxonomies($partner, $taxonomy_options)
    {
        $partner->taxonomyOptions()->detach();
        if (!empty($taxonomy_options)) {
            $partner->taxonomyOptions()->attach($taxonomy_options);
        }
    }
}
