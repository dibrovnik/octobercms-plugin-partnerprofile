<?php

namespace Dibrovnik\PartnerProfile\Components;

use Cms\Classes\ComponentBase;
use Dibrovnik\PartnerProfile\Models\Field;  // Модель динамических полей
use Dibrovnik\PartnerProfile\Models\FieldValue;  // Модель значений полей
use Dibrovnik\PartnerProfile\Models\Partner;  // Модель значений полей
use Dibrovnik\PartnerProfile\Models\Price;  // Модель значений полей
use Dibrovnik\PartnerProfile\Models\Checkbox;
use Dibrovnik\PartnerProfile\Models\Dashboard;
use Dibrovnik\PartnerProfile\Models\Taxonomy;
use Dibrovnik\PartnerProfile\Models\TaxonomyOption;
use Auth;
use ApplicationException;
use File;
use Input;
use Log;
use Validator; // Импорт для класса Validator
use ValidationException; // Импорт для класса ValidationException

class Taxonomies extends ComponentBase
{
    public function componentDetails()
    {
        return [
            'name' => 'Taxonomies',
            'description' => 'Форма для заполнения профиля партнёра'
        ];
    }

    public function onRun()
    {
        $prefixSlug = 'taxonomy/';
        
        $parentSlug = $this->param('parentSlug');
        $childSlug = $this->param('childSlug');
        $fullSlug = $parentSlug . '/' . $childSlug;
        $taxonomy = Taxonomy::where('slug', $parentSlug)->first();

        $this->page['taxonomies'] = Taxonomy::getTaxonomiesAndOptions();
        $this->page['taxonomy'] = $taxonomy ? $taxonomy->name : 'Таксономия не найдена';

        if (!$taxonomy) {
            return;
        }

        if (!empty($childSlug)) {
            $option = $taxonomy->options()->where('slug', $fullSlug)->first();
            $partners = $option ? $option->partners : collect();
            
            $this->page['offers'] = $this->loadOffers($partners);
            $this->page['partners'] = $partners;
            $this->page['option'] = $option ? $option->name : 'Опция не найдена';

        } else {
            $options = TaxonomyOption::getOptionsByTaxonomyId($taxonomy->id);
            $offersGroupedByOptions = [];
            foreach ($options as $option) {
                $partnersForOption = $option->partners;
                $optionUrl = url("{$prefixSlug}{$option->slug}");

                $offersGroupedByOptions[] = [
                    'name' => $option->name,
                    'offers' => $this->loadOffers($partnersForOption),
                    'url' => $optionUrl,
                ];
            }

            $this->page['offersGroupedByOptions'] = $offersGroupedByOptions;
        }
    }

    // Метод для получения предложений
    protected function loadOffers($partners)
    {
        
        $offers = []; 
        foreach ($partners as $partner) {
            $verticalPhotos = Partner::getVerticalPortfolioPhotos($partner);
            $city=json_decode( Partner::getPartnerFieldValue( $partner, 'city'));
            $prices =  Partner::getPartnerPrices($partner, $json = false);
            $partnerUrl = Partner::getPartnerUrl($partner);
            $offers[] = [
                'title' =>  Partner::getPartnerFieldValue( $partner, 'title'), 
                'city' =>  $city[1], 
                'prices' => $prices,
                'url' => $partnerUrl,
                'photos' => $verticalPhotos->map(function ($photo) {
                    return [
                        'id' => $photo->id,
                        'url' => $photo->path, // URL фото
                        'title' => $photo->title ?? 'Без названия', // Название фото, если есть
                        'description' => $photo->description ?? 'Описание отсутствует', // Описание фото, если есть
                    ];
                })->toArray(), 
            ];
        }

        return $offers; 
    }

}
