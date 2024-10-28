<?php
namespace Dibrovnik\Partnerprofile\Models;
use Dibrovnik\PartnerProfile\Models\Field;  // Модель динамических полей
use Dibrovnik\PartnerProfile\Models\FieldValue;  // Модель значений полей
use Dibrovnik\PartnerProfile\Models\Checkbox;
use Model;

/**
 * Model
 */
class Checkbox extends Model
{
    use \October\Rain\Database\Traits\Validation;


    /**
     * @var string table in the database used by the model.
     */
    public $table = 'dibrovnik_partnerprofile_checkbox_items';

    /**
     * @var array rules for validation.
     */
    public $rules = [
    ];

    public function getFieldsOptions()
    {
       
        $fields = \Dibrovnik\PartnerProfile\Models\Field::all()->pluck('label', 'id')->toArray();
        return $fields;
    }

    public static function getCheckboxItems($user){
        
        $checkboxItems = Checkbox::orderBy('sort_order')->get();
        $fields = FieldValue::getFields($user);
        $result = [];
        foreach ($checkboxItems as $item) {
            $field = collect($fields)->firstWhere('id', $item->field_id);

            if ($field) {
                $result[] = [
                    'checkbox_item_text' => $item->checkbox_item_text,
                    'is_filled' => !empty($field['value']) ? 1 : 0,  // Если значение не пустое, ставим 1, иначе 0
                ];
            }
        }
        return $result;
    }

}