# PartnerProfile Plugin for October CMS

The **PartnerProfile** plugin provides robust functionality for managing partner profiles in October CMS. This plugin is designed to handle various partner-related data, such as dynamic fields, taxonomies, pricing, and portfolio images, making it ideal for websites that require flexible partner or service provider profiles.

## Features

- **Partner Profile Management**: Create and manage detailed profiles for partners or service providers.
- **Dynamic Fields**: Customize partner profiles with various dynamic fields and store personalized data.
- **Taxonomies**: Organize and categorize partners using taxonomy options, with easy-to-use filtering.
- **Portfolio Management**: Add and manage partner portfolios with both vertical and horizontal image support.
- **Pricing Options**: Set up and display multiple pricing options for each partner.
- **Authentication Components**: Seamlessly integrate authentication components with October CMS’s `RainLab.User` plugin.

## Installation

To install this plugin in your October CMS project, you can either install via the console or manually clone it:

### Install via October CMS Console

Run the following command to install the plugin through October CMS’s artisan command:

```bash
php artisan plugin:install YourVendor.PartnerProfile
```

### Manual Installation via GitHub

Clone this repository directly into the `plugins` directory of your October CMS project:

```bash
cd plugins
git clone https://github.com/YOUR_GITHUB_USERNAME/octobercms-plugin-partnerprofile.git yourvendor/partnerprofile
```

Then, run the following command to apply the plugin’s database migrations:

```bash
php artisan october:up
```

## Usage

1. **Add Components**: Use the available components (`AuthComponent`, `Cards`, `DetailPage`, `PartnerProfileForm`, `Taxonomies`) in your pages to manage partner data and display profile details.
2. **Template Integration**: Call specific methods in your templates to access dynamic field values or display partner data. Example:

   ```html
   <p>{{ partnerProfileForm.fieldValue('tel') }}</p>
   ```
3. **Customize Partner Profiles**: Access the backend to create, edit, and categorize partner profiles with taxonomies, field values, and pricing.

## Contributing

Contributions are welcome! Please fork this repository, make your changes, and submit a pull request. For major changes, please open an issue first to discuss what you would like to change.


### Документация к плагину Partner Profile для October CMS

Плагин **Partner Profile** создан для управления профилями партнеров, представлением информации о них и взаимодействием с таксономиями, предложениями и другими данными. Позволяет добавлять в админке поля для заполнения пользователями (партнерами)

#### Модели

##### Taxonomy

Модель **Taxonomy** представляет таксономии, которые могут содержать различные опции, связанные с партнерами.

- **Свойства**:

  - `table`: `dibrovnik_partnerprofile_taxonomies` - имя таблицы.
  - `hasMany`: `options` - связь один ко многим с моделью `TaxonomyOption`.
  - `belongsToMany`: `partners` - связь многие ко многим с моделью `Partner` через промежуточную таблицу.
- **Методы**:

  - `beforeDelete()`: удаляет связанные опции перед удалением таксономии.
  - `generateSlug()`: генерирует слаг на основе названия с транслитерацией.
  - `getTaxonomiesAndOptions()`: получает таксономии с их опциями и возвращает их сгенерированные ссылки.
  - `getTaxonomiesAndOptionsForPartner()`: возвращает опции, связанные с партнером, фильтруя по `partner_id`.

##### TaxonomyOption

Модель **TaxonomyOption** представляет опции таксономий.

- **Свойства**:

  - `table`: `dibrovnik_partnerprofile_taxonomy_options`.
  - `belongsTo`: `taxonomy` - связь один ко многим с моделью `Taxonomy`.
  - `belongsToMany`: `partners` - связь многие ко многим с моделью `Partner`.
- **Методы**:

  - `beforeValidate()`: проверяет уникальность имени опции внутри одной таксономии.
  - `generateSlug()`: создает слаг для опции на основе названий таксономии и опции.
  - `getOptionsByTaxonomyId($taxonomyId)`: получает опции для конкретной таксономии.
  - `getPartnersBySlugs($parentSlug, $childSlug)`: возвращает партнеров, связанных с определенной таксономией и опцией по slug.

##### Partner

Модель **Partner** хранит данные о партнерах.

- **Свойства**:

  - `table`: `dibrovnik_partnerprofile_partner`.
  - `belongsTo`: `user` - связь один ко многим с пользователями.
  - `belongsToMany`: `taxonomyOptions` - связь многие ко многим с `TaxonomyOption`.
  - `attachOne`: `avatarPhoto` - фотография-аватар.
  - `attachMany`: `portfolioPhotos` - портфолио.
- **Методы**:

  - `getPartnerData($partner)`: возвращает данные партнера для представления.
  - `getPartnerPrices($partner, $json)`: получает данные о ценах партнера.
  - `getPartnerUrl($partner)`: создает URL партнера.
  - `getPartnerFieldValue($partner, $fieldName)`: возвращает значение поля партнера.
  - `getVerticalPortfolioPhotos($partner)`, `getGorizontalPortfolioPhotos($partner)`: фильтруют фотографии партнера по ориентации.
  - `setPartnerTaxonomies($partner, $taxonomy_options)`: устанавливает таксономии для партнера.

##### Price

Модель **Price** представляет данные о ценах.

- **Свойства**:

  - `table`: `dibrovnik_partnerprofile_price_settings`.
- **Методы**:

  - `getPriceSettings()`: возвращает настройки цен с массивами валют и значений, разложенных из JSON.

##### Field и FieldValue

Эти модели представляют динамические поля и их значения, заполняемые партнерами.

#### Компоненты

##### AuthComponent

Компонент **AuthComponent** управляет модальными окнами регистрации и входа для авторизации пользователей.

- **Методы**:
  - `onLoadRegisterModalContent()`: загружает контент модального окна регистрации.
  - `onLoadLoginModalContent()`: загружает контент модального окна логина.

##### Cards

Компонент **Cards** отвечает за отображение списка предложений.

- **Методы**:
  - `onRun()`: вызывается при загрузке страницы и передает список предложений в шаблон.
  - `loadOffers()`: загружает данные о партнерах и форматирует их в список предложений для представления на фронтенде.

##### DetailPage

Компонент **DetailPage** выводит детальную информацию о предложении.

- **Методы**:
  - `onRun()`: загружает информацию о партнере по `partnerId`, передает данные на страницу или вызывает 404, если партнер не найден.

##### PartnerProfileForm

Компонент **PartnerProfileForm** управляет формой профиля партнера.

- **Методы**:
  - `onRun()`: проверяет авторизацию, загружает данные партнера или вызывает ошибку, если партнер не найден.
  - `fieldValue($fieldName)`: получает значение поля для отображения в шаблоне.
  - `onSave()`: сохраняет данные профиля и таксономий в базе данных.
  - `onUploadAvatar()`: загружает фотографию-аватар партнера.
  - `onUploadPortfolioPhotos()`: загружает фотографии портфолио партнера.
  - `onDeletePortfolioPhoto()`: удаляет выбранную фотографию портфолио.
  - `onSavePrices()`: сохраняет цены и дополнительные данные о ценах.

##### Taxonomies

Компонент **Taxonomies** управляет отображением таксономий и их опций.

- **Методы**:
  - `onRun()`: загружает данные о таксономиях и опциях по `parentSlug` и `childSlug`.
  - `loadOffers()`: загружает предложения партнеров, связанных с конкретной опцией.

#### Метод для вывода полей в шаблоне

Метод `fieldValue($fieldName)` в компоненте **PartnerProfileForm** используется для получения значения конкретного поля, связанного с партнером, чтобы отобразить его в шаблоне. Этот метод можно вызвать прямо из шаблона для получения и вывода данных профиля партнера.

**Назначение**:
Метод принимает имя поля в виде строки, ищет это поле в базе данных для текущего авторизованного пользователя и возвращает его значение. Это позволяет динамически отображать значения полей, введенные партнером.

**Пример использования**:

Чтобы вывести значение поля (например, телефон), можно использовать следующий синтаксис в шаблоне:

```html
{{ partnerProfileForm.fieldValue('tel') }}
```

**Параметры**:

- `$fieldName` (строка): Имя поля, значение которого нужно получить.

**Логика работы**:

1. Метод получает текущего авторизованного пользователя.
2. Ищет запись в базе данных, соответствующую имени поля `$fieldName`.
3. Если поле существует, ищет его значение для конкретного пользователя.
4. Возвращает значение, если оно найдено; в противном случае — `null`.

**Примечание**:
Этот метод удобен для шаблонов, где значения могут меняться в зависимости от данных пользователя, например, для отображения контактной информации, описания и других данных, введенных партнером.
