<?php

namespace Dibrovnik\PartnerProfile\Components;
// namespace Dibrovnik\PartnerProfile\Components;
use Dibrovnik\PartnerProfile\Models\Field;  // Модель динамических полей
use Dibrovnik\PartnerProfile\Models\FieldValue;  // Модель значений полей
use Dibrovnik\PartnerProfile\Models\Partner;  // Модель значений полей
use Dibrovnik\PartnerProfile\Models\Price;  // Модель значений полей
use Dibrovnik\PartnerProfile\Models\Checkbox;
use Dibrovnik\PartnerProfile\Models\Taxonomy;
use Dibrovnik\PartnerProfile\Models\Dashboard;
use Auth;
use ApplicationException;
use File;
use Input;
use Log;
use Validator; // Импорт для класса Validator
use ValidationException; // Импорт для класса ValidationException
use Cms\Classes\ComponentBase;

class DetailPage extends ComponentBase
{
    public function componentDetails()
    {
        return [
            'name'        => 'Detail page',
            'description' => 'Displays a list of offers on detail page'
        ];
    }

    public function onRun()
    {
        $partnerId = $this->param('id');

        $partner = Partner::find($partnerId);
         if (!$partner) {
            return $this->controller->run('404');
        }
        
        $this->page['partner'] = $partner;
        $this->page['partnerData'] = Partner::getPartnerData($partner);
        $this->page['verticalPhotos'] = Partner::getVerticalPortfolioPhotos($partner);
        $this->page['gorizontalPhotos'] = Partner::getGorizontalPortfolioPhotos($partner);
        $this->page['taxonomies'] = Taxonomy::getTaxonomiesAndOptionsForPartner($partner->id);
    }    
}
