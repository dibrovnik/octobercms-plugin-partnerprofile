<?php namespace Dibrovnik\Partnerprofile\Models;

use Model;

/**
 * Model
 */
class Category extends Model
{
    use \October\Rain\Database\Traits\Validation;


    /**
     * @var string table in the database used by the model.
     */
    public $table = 'dibrovnik_partnerprofile_categories';

    /**
     * @var array rules for validation.
     */
    public $rules = [
    ];

}
