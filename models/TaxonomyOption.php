<?php namespace Dibrovnik\Partnerprofile\Models;

use Dibrovnik\Partnerprofile\Models\Taxonomy;
use Model;
use ValidationException;

/**
 * TaxonomyOption Model - модель для работы с опциями таксономий
 */
class TaxonomyOption extends Model
{
    use \October\Rain\Database\Traits\Validation;

    /**
     * @var string Имя таблицы в базе данных
     */
    public $table = 'dibrovnik_partnerprofile_taxonomy_options';

    /**
     * Связь "многие к одному" с таксономией
     */
    public $belongsTo = [
        'taxonomy' => [Taxonomy::class, 'key' => 'taxonomy_id']
    ];

    /**
     * Связь "многие ко многим" с партнёрами через промежуточную таблицу
     */
    public $belongsToMany = [
        'partners' => [
            'Dibrovnik\PartnerProfile\Models\Partner',
            'table' => 'dibrovnik_partnerprofile_partner_taxonomy',
            'key' => 'taxonomy_option_id',
            'otherKey' => 'partner_id',
        ]
    ];

    protected $fillable = ['name', 'slug', 'taxonomy_id'];

    /**
     * Правила валидации
     */
    public $rules = [
        'name' => 'required',
    ];

    /**
     * Метод для проверки уникальности имени опции в рамках одной таксономии
     */
    public function beforeValidate()
    {
        if ($this->taxonomy_id) {
            $existingOption = self::where('taxonomy_id', $this->taxonomy_id)
                ->where('name', $this->name)
                ->where('id', '<>', $this->id) // Исключаем текущую запись, если редактируем
                ->first();

            if ($existingOption) {
                throw new ValidationException([
                    'name' => 'Имя опции должно быть уникальным для данной таксономии.'
                ]);
            }
        }
    }

    /**
     * Генерация слага на основе названия таксономии и опции
     * 
     * @return string|null - Слаг, сгенерированный на основе названий таксономии и опции
     */
    protected function generateSlug()
    {
        if ($this->taxonomy) {
            $transliteration = [
                'А' => 'A', 'Б' => 'B', 'В' => 'V', 'Г' => 'G', 'Д' => 'D',
                'Е' => 'E', 'Ё' => 'Yo', 'Ж' => 'Zh', 'З' => 'Z', 'И' => 'I',
                'Й' => 'Y', 'К' => 'K', 'Л' => 'L', 'М' => 'M', 'Н' => 'N',
                'О' => 'O', 'П' => 'P', 'Р' => 'R', 'С' => 'S', 'Т' => 'T',
                'У' => 'U', 'Ф' => 'F', 'Х' => 'Kh', 'Ц' => 'Ts', 'Ч' => 'Ch',
                'Ш' => 'Sh', 'Щ' => 'Shch', 'Ъ' => '', 'Ы' => 'Y', 'Ь' => '',
                'Э' => 'E', 'Ю' => 'Yu', 'Я' => 'Ya',
                'а' => 'a', 'б' => 'b', 'в' => 'v', 'г' => 'g', 'д' => 'd',
                'е' => 'e', 'ё' => 'yo', 'ж' => 'zh', 'з' => 'z', 'и' => 'i',
                'й' => 'y', 'к' => 'k', 'л' => 'l', 'м' => 'm', 'н' => 'n',
                'о' => 'o', 'п' => 'p', 'р' => 'r', 'с' => 's', 'т' => 't',
                'у' => 'u', 'ф' => 'f', 'х' => 'kh', 'ц' => 'ts', 'ч' => 'ch',
                'ш' => 'sh', 'щ' => 'shch', 'ъ' => '', 'ы' => 'y', 'ь' => '',
                'э' => 'e', 'ю' => 'yu', 'я' => 'ya'
            ];

            $taxonomySlug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', strtr($this->taxonomy->name, $transliteration)), '-'));
            $optionSlug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', strtr($this->name, $transliteration)), '-'));

            return "{$taxonomySlug}/{$optionSlug}";
        }

        return null;
    }

    /**
     * Сообщения валидации
     * 
     * @return array - Массив сообщений об ошибках
     */
    public function validationMessages()
    {
        return [
            'name.required' => 'Название таксономии обязательно для заполнения.',
            'name.unique' => 'Таксономия с таким названием уже существует.',
            'slug.required' => 'Слаг обязателен.',
            'slug.unique' => 'Слаг уже используется.',
        ];
    }

    /**
     * Событие перед сохранением модели
     */
    public function beforeSave()
    {
        if (empty($this->slug)) {
            $this->slug = $this->generateSlug();
        }
    }

    /**
     * Получает опции таксономии по ID таксономии
     * 
     * @param int $taxonomyId - ID таксономии
     * @return Collection - Коллекция опций таксономии
     */
    public static function getOptionsByTaxonomyId($taxonomyId)
    {
        return self::where('taxonomy_id', $taxonomyId)->get();
    }

    /**
     * Получает партнеров по slug таксономии и опции
     * 
     * @param string $parentSlug - Слаг таксономии
     * @param string $childSlug - Слаг опции
     * @return Collection - Коллекция партнеров, связанных с опцией
     */
    public static function getPartnersBySlugs($parentSlug, $childSlug)
    {
        $taxonomy = \Dibrovnik\PartnerProfile\Models\Taxonomy::where('slug', $parentSlug)->first();

        if (!$taxonomy) {
            return collect();
        }

        $option = self::where('slug', $childSlug)
                    ->where('taxonomy_id', $taxonomy->id)
                    ->first();

        return $option ? $option->partners : collect();
    }
}
