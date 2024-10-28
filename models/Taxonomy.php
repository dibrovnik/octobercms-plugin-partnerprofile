<?php namespace Dibrovnik\Partnerprofile\Models;

use Model;
use Dibrovnik\Partnerprofile\Models\TaxonomyOption;

/**
 * Модель Taxonomy - для работы с таксономиями и их опциями
 */
class Taxonomy extends Model
{
    use \October\Rain\Database\Traits\Validation;
    
    /**
     * @var string Имя таблицы в базе данных
     */
    public $table = 'dibrovnik_partnerprofile_taxonomies';

    /**
     * Связь "один ко многим" с таблицей опций таксономии
     */
    public $hasMany = [
        'options' => [TaxonomyOption::class, 'key' => 'taxonomy_id']
    ];

    /**
     * Связь "многие ко многим" с партнёрами через промежуточную таблицу
     */
    public $belongsToMany = [
        'partners' => [
            'Dibrovnik\PartnerProfile\Models\Partner',
            'table' => 'dibrovnik_partnerprofile_partner_taxonomy',
        ]
    ];

    /**
     * Поля, доступные для массового заполнения
     */
    protected $fillable = ['name', 'slug'];

    /**
     * Правила валидации для модели
     */
    public $rules = [
        'name' => 'required|unique:dibrovnik_partnerprofile_taxonomies,name',
        'slug' => 'unique:dibrovnik_partnerprofile_taxonomies',
    ];

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
     * Метод для удаления связанных опций при удалении таксономии
     */
    public function beforeDelete()
    {
        $this->options()->delete();
    }

    /**
     * Генерация слага на основе названия
     * 
     * @param string $name - Название таксономии
     * @return string - Слаг, сгенерированный на основе названия
     */
    protected function generateSlug($name)
    {
        // Транслитерация русского текста
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

        // Преобразование текста
        $slug = strtr($name, $transliteration);
        $slug = preg_replace('/[^A-Za-z0-9-]+/', '-', $slug);
        return strtolower(trim($slug, '-'));
    }

    /**
     * Событие перед сохранением модели
     */
    public function beforeSave()
    {
        if (empty($this->slug)) {
            $this->slug = $this->generateSlug($this->name);
        }
    }

    /**
     * Получает все таксономии с их опциями и формирует ссылки
     * 
     * @return array - Массив с таксономиями и их опциями
     */
    public static function getTaxonomiesAndOptions()
    {
        $taxonomies = self::with('options')->get();
        $prefixSlug = 'taxonomy/';
        $result = [];

        foreach ($taxonomies as $taxonomy) {
            $options = [];
            foreach ($taxonomy->options as $option) {
                $options[] = [
                    'name' => $option->name,
                    'url' => url("{$prefixSlug}{$option->slug}"),
                ];
            }
            $result[] = [
                'taxonomy_name' => $taxonomy->name,
                'options' => $options,
            ];
        }

        return $result;
    }

    /**
     * Получает таксономии с опциями для конкретного партнера
     * 
     * @param int $partnerId - ID партнера
     * @return array - Массив таксономий и их опций, связанных с партнером
     */
    public static function getTaxonomiesAndOptionsForPartner($partnerId)
    {
        $taxonomies = self::with(['options' => function($query) use ($partnerId) {
            $query->whereHas('partners', function($query) use ($partnerId) {
                $query->where('id', $partnerId);
            });
        }])->get();

        $prefixSlug = 'taxonomy/';
        $result = [];

        foreach ($taxonomies as $taxonomy) {
            $options = [];
            foreach ($taxonomy->options as $option) {
                $options[] = [
                    'name' => $option->name,
                    'url' => url("{$prefixSlug}{$option->slug}"),
                ];
            }
            if (!empty($options)) {
                $result[] = [
                    'taxonomy_name' => $taxonomy->name,
                    'options' => $options,
                ];
            }
        }

        return $result;
    }
}
