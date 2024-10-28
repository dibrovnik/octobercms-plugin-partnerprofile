<?php

namespace Dibrovnik\PartnerProfile\Models;

use Model;

class FieldValue extends Model
{
    protected $table = 'dibrovnik_partnerprofile_field_values';

    public $timestamps = true;

    protected $fillable = ['user_id', 'field_id', 'value'];


    // Метод для получения значений всех полей
    public static function getFields($user)
    {
        // Проверка наличия пользователя
        if (!$user) {
            return collect();
        }

        // Загрузка всех полей и значений полей для данного пользователя
        $fields = Field::orderBy('sort_order')->get();
        $fieldValues = self::where('user_id', $user->id)
            ->whereIn('field_id', $fields->pluck('id'))
            ->get()
            ->keyBy('field_id');

        // Загрузка всех опций таксономий для всех полей с типом 'taxonomy'
        $taxonomyOptions = TaxonomyOption::whereIn('taxonomy_id', $fields->where('type', 'taxonomy')->pluck('taxonomy_id'))
            ->get()
            ->groupBy('taxonomy_id');

        foreach ($fields as $field) {
            // Присваиваем значение поля или пустую строку
            $field->value = $fieldValues->get($field->id)->value ?? '';

            // Если тип поля "taxonomy", присваиваем опции таксономии
            if ($field->type == 'taxonomy') {
                $field->taxonomy_options = $taxonomyOptions->get($field->taxonomy_id) ?? collect();
            }
        }

        return $fields;
    }
}
