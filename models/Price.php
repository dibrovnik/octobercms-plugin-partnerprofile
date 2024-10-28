<?php namespace Dibrovnik\Partnerprofile\Models;

use Model;

/**
 * Price Model - модель для настройки валютных и ценовых опций партнера
 */
class Price extends Model
{
    use \October\Rain\Database\Traits\Validation;

    /**
     * @var string Имя таблицы в базе данных, используемой моделью
     */
    public $table = 'dibrovnik_partnerprofile_price_settings';

    /**
     * @var array Правила валидации полей
     */
    public $rules = [
        'currency_options' => 'nullable|string',
        'value_options' => 'nullable|string',
    ];

    /**
     * Получает настройки цен и валют для партнера
     * 
     * @return array Массив с параметрами валюты и значений, извлечёнными из базы данных
     */
    public static function getPriceSettings()
    {
        $priceSettings = self::first();

        if ($priceSettings) {
            return [
                'currency_options' => explode(',', $priceSettings->currency_options),
                'value_options' => explode(',', $priceSettings->value_options),
            ];
        }
        return [
            'currency_options' => [],
            'value_options' => [],
        ];
    }
}
