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

class PartnerProfileForm extends ComponentBase
{
    public function componentDetails()
    {
        return [
            'name' => 'Partner Profile Form',
            'description' => 'Форма для заполнения профиля партнёра'
        ];
    }
    protected function returnError($message, $code = 400)
    {
        return [
            'status' => 'error',
            'message' => $message,
            'code' => $code
        ];
    }


    // Этот метод будет доступен для вызова в шаблонах Twig, например fieldValue('email')
    public function fieldValue($fieldName)
    {
        $user = Auth::getUser(); // Получаем текущего пользователя

        // Находим поле по имени
        $field = Field::where('name', $fieldName)->first();

        if (!$field) {
            return null; // Если поле не найдено, возвращаем null
        }

        // Находим значение поля для текущего пользователя
        $fieldValue = FieldValue::where('user_id', $user->id)
            ->where('field_id', $field->id)
            ->first();

        // Возвращаем значение, если оно найдено, или null, если нет значения
        return $fieldValue ? $fieldValue->value : null;
    }
    
    protected function getAuthenticatedPartner()
    {
        $user = Auth::getUser();
        return Partner::where('user_id', $user->id)->first();
    }


   public function onRun()
    {
        if (!Auth::check()) {
            return redirect()->to('/account/login');
        }

        $partner = $this->getAuthenticatedPartner();
        if (!$partner) {
            return $this->returnError('Partner not found', 404);
        }

        $this->loadPartnerData($partner);
    }

    protected function loadPartnerData($partner)
    {
        $this->page['avatarUrl'] = $partner->avatar ?: null;
        $this->page['fields'] = FieldValue::getFields(Auth::getUser());
        $this->page['portfolioPhotos'] = $partner->portfolioPhotos;
        $this->page['checkboxItems'] = Checkbox::getCheckboxItems(Auth::getUser());
        $this->page['priceSettings'] = Price::getPriceSettings();
        $this->page['pricesValues'] = Partner::getPartnerPrices($partner, true);
        $this->page['dashboardItems'] = Dashboard::getDashBoardItems(Auth::getUser());
    }


    public function onSave()
    {
        $user = Auth::getUser();  // Получаем текущего пользователя
        $fields = post('fields', []); // Получаем данные формы
        $partner = Partner::getPartnerByUser($user);

        // Проверка наличия партнера перед обработкой
        if (!$partner) {
            return [
                'status' => 'error',
                'message' => 'Partner not found',
                'code' => 404
            ];
        }

        $allFields = Field::all();
        $taxonomyData = []; // Массив для хранения всех значений таксономий

        // 1. Сначала собираем значения таксономий в массив
        foreach ($allFields as $field) {
            if ($field->type == 'taxonomy' && isset($fields[$field->id])) {
                $value = $fields[$field->id];

                // Добавляем значение таксономии для поля в общий массив
                $taxonomyData = array_merge($taxonomyData, (array)$value);

                // Преобразуем значение таксономии в json и сохраняем в fieldvalue
                $taxonomyValue = json_encode($value);
                FieldValue::updateOrCreate(
                    ['user_id' => $user->id, 'field_id' => $field->id],
                    ['value' => $taxonomyValue]
                );
            }
        }

        // 2. Записываем все собранные значения таксономий в базу данных за один раз
        Partner::setPartnerTaxonomies($partner, $taxonomyData);

        // 3. Обработка и сохранение остальных полей
        foreach ($fields as $fieldId => $value) {
            $field = Field::find($fieldId);

            // Пропускаем таксономии, так как они уже обработаны
            if ($field && $field->type != 'taxonomy') {
                FieldValue::updateOrCreate(
                    ['user_id' => $user->id, 'field_id' => $fieldId],
                    ['value' => $value]
                );
            }
        }

        return [
            '#enteredData' => $this->renderPartial('@fields_data', ['fields' => FieldValue::getFields($user)]),
            'status' => 'success',
            'message' => 'Data saved',
            'code' => 200
        ];
    }

    public function onUploadAvatar()
    {
        $user = Auth::getUser();
        $partner = Partner::where('user_id', $user->id)->first();

        if (!$partner) {
            return ['error' => 'Partner not found'];
        }

        $file = Input::file('avatar');

        if ($file) {
            // Прикрепляем файл как аттачмент к модели
            $partner->avatarPhoto()->create(['data' => $file]);
            $avatarPath = $partner->avatarPhoto->getUrl();
            $partner->avatar = $avatarPath;
            $partner->save();
            return ['message' => 'Avatar successfully downloaded', 'avatarPath' => $avatarPath];
        } else {
            return ['error' => 'Avatar not found'];
        }
    }

    public function onUploadPortfolioPhotos()
    {
        $user = Auth::getUser();
        $partner = Partner::where('user_id', $user->id)->first();

        if (!$partner) {
            return ['error' => 'Partner not found'];
        }
        
        $files = Input::file('portfolio_photos');
        $uploadedFiles = [];

        if ($files && is_array($files)) {
            foreach ($files as $file) {
                if ($file->isValid()) { // Проверка на валидность файла
                    try {
                        $newFile = $partner->portfolioPhotos()->create(['data' => $file]);
                        $uploadedFiles[] = $newFile->getUrl();
                    } catch (\Exception $e) {
                        return ['error' => 'Error uploading file: ' . $file->getClientOriginalName()];
                    }
                } else {
                    return ['error' => 'Invalid file: ' . $file->getClientOriginalName()];
                }
            }

            return [
                '#portfolio-gallery' => $this->renderPartial('portfolio_gallery', [
                    'portfolioPhotos' => $partner->portfolioPhotos
                ]),
                'uploadedFiles' => $uploadedFiles
            ];
        } else {
            return ['error' => 'No portfolio photos found or invalid input format'];
        }
    }

    public function onDeletePortfolioPhoto()
    {
        // Получаем ID изображения из запроса
        $photoId = post('id');

        // Найдем фото по его ID
        $photo = \System\Models\File::find($photoId);

        // Если фото найдено, удаляем его
        if ($photo) {
            $photo->delete();
            return ['success' => true];
        }

        return ['success' => false, 'error' => 'Image not found'];
    }

    public function onSavePrices()
    {
        $user = Auth::getUser();  
        $fields = post('price'); 
        $partner = Partner::where('user_id', $user->id)->first();

        if ($partner) {
            $dataToSave = [
                'base_price' => [
                    'type' => $fields['type'],
                    'currency' => $fields['currency'],
                    'value' => $fields['value'],
                    'price' => $fields['base-price'],
                    'comment' => $fields['base-price-comm'] // Добавляем комментарий к базовой цене
                ],
                'additional_prices' => []
            ];

            if (isset($fields['add-price-descr'])) {
                $additionalPriceCount = count($fields['add-price-descr']);
                for ($index = 0; $index < $additionalPriceCount; $index++) {
                    $description = $fields['add-price-descr'][$index] ?? ''; 
                    $value = $fields['add-price-value'][$index] ?? ''; 
                    $price = $fields['add-price'][$index] ?? '';

                    $dataToSave['additional_prices'][] = [
                        'description' => $description,
                        'value' => $value,
                        'price' => $price
                    ];
                }
            }
            $jsonData = json_encode($dataToSave);

            $partner->prices = $jsonData;
            $partner->save();

            return $jsonData;
        }
    }    
}
