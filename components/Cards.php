<?php

namespace Dibrovnik\PartnerProfile\Components;

use Dibrovnik\PartnerProfile\Models\Field;  // Модель динамических полей
use Dibrovnik\PartnerProfile\Models\FieldValue;  // Модель значений полей
use Dibrovnik\PartnerProfile\Models\Partner;  // Модель значений полей
use Dibrovnik\PartnerProfile\Models\Price;  // Модель значений полей
use Dibrovnik\PartnerProfile\Models\Checkbox;
use Dibrovnik\PartnerProfile\Models\Dashboard;
use Auth;
use ApplicationException;
use File;
use Input;
use Log;
use Validator; // Импорт для класса Validator
use ValidationException; // Импорт для класса ValidationException
use Cms\Classes\ComponentBase;

class Cards extends ComponentBase
{
    public function componentDetails()
    {
        return [
            'name'        => 'Offer cards',
            'description' => 'Displays a list of offers as cards'
        ];
    }

    // Метод, вызываемый при загрузке страницы
    public function onRun()
    {
        // Получение данных предложений и передача их в шаблон
        $this->page['offers'] = $this->loadOffers();
    }

    // Метод для получения предложений (например, из базы данных)
    protected function loadOffers()
    {
        // Проверяем, существуют ли партнёры перед вызовом метода
        $partners = Partner::getAllPartners() ?? [];

        $offers = []; // Массив для хранения данных о партнерах и их фотографиях

        foreach ($partners as $partner) {
            // Получаем фотографии, если они существуют
            $verticalPhotos = Partner::getVerticalPortfolioPhotos($partner) ?? collect([]);

            // Декодируем JSON, проверяем его наличие перед декодированием
            $cityJson = Partner::getPartnerFieldValue($partner, 'city');
            $city = $cityJson ? json_decode($cityJson) : null;

            // Получаем цены, если они существуют
            $prices = Partner::getPartnerPrices($partner, $json = false) ?? [];

            // Получаем URL партнёра, если он существует
            $partnerUrl = Partner::getPartnerUrl($partner) ?? '#';

            $offers[] = [
                'title' => Partner::getPartnerFieldValue($partner, 'title') ?? 'No title', 
                'city' => $city[1] ?? 'Unknown city',
                'prices' => $prices,
                'url' => $partnerUrl,
                'photos' => $verticalPhotos->map(function ($photo) {
                    return [
                        'id' => $photo->id ?? null,
                        'url' => $photo->path ?? '#', // URL фото
                        'title' => $photo->title ?? 'Без названия', // Название фото, если есть
                        'description' => $photo->description ?? 'Описание отсутствует', // Описание фото, если есть
                    ];
                })->toArray(), // Преобразуем коллекцию в массив
            ];
        }

        return $offers; // Возвращаем готовый массив с данными
    }
}
