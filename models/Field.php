<?php
namespace Dibrovnik\Partnerprofile\Models;
use System\Models\File;
use Dibrovnik\PartnerProfile\Models\Category;
use Dibrovnik\PartnerProfile\Models\Taxonomy;
use Model;
use Log;

/**
 * Model
 */
class Field extends Model
{
    use \October\Rain\Database\Traits\Validation;
    public $belongsTo = [
        'category' => 'Dibrovnik\PartnerProfile\Models\Category'
    ];
    protected $jsonable = ['options']; // Автоматически обрабатывает JSON для поля options

    /**
     * @var string table in the database used by the model.
     */
    public $table = 'dibrovnik_partnerprofile_fields';

    public $attachOne = [
        'uploaded_file' => 'System\Models\File',
    ];
    /**
     * @var array rules for validation.
     */

    public $rules = [
        'name' => 'required|unique:dibrovnik_partnerprofile_fields'
    ];
    public $customMessages = [
        'name.unique' => 'The name field must be unique. Enter a different name.',
    ];

    // Метод для получения категории поля
    public function getCategoryIdOptions()
    {
        return Category::pluck('name', 'id')->all();
    }

    // Метод для получения таксономий для реализации типа поля taxonomy
    public function getTaxonomiesIdOptions()
    {
        return Taxonomy::pluck('name', 'id')->all();
    }

}