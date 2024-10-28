<?php

namespace Dibrovnik\Partnerprofile;

use System\Classes\PluginBase;
use Backend;
use Db;
use Event;

/**
 * Plugin class
 */
class Plugin extends PluginBase
{
    /**
     * register method, called when the plugin is first registered.
     */
    public function register() {}

    /**
     * boot method, called right before the request route.
     */
    public function boot() {
        Event::listen('rainlab.user.register', function ($component, $user) {
            $input = post();

            // Проверяем, выбран ли тип учетной записи "Партнер"
            if (array_key_exists('user_type', $input) && $input['user_type'] === 'partner') {
                Db::table('dibrovnik_partnerprofile_partner')->insert([
                    'user_id' => $user->id,
                    'tariff' => 0,
                    'avatar_verificated' => 0,
                    'account_verificated' => 0,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        });
    }

    /**
     * registerComponents used by the frontend.
     */
    public function registerComponents()
    {
        return [
            'Dibrovnik\PartnerProfile\Components\PartnerProfileForm' => 'partnerProfileForm',
            'Dibrovnik\PartnerProfile\Components\Cards' => 'offerCards',
            'Dibrovnik\PartnerProfile\Components\DetailPage' => 'detailPage',
            'Dibrovnik\PartnerProfile\Components\Taxonomies' => 'taxonomies',
            'Dibrovnik\PartnerProfile\Components\AuthComponent' => 'authComponent',
        ];
    }
    // plugins/dibrovnik/partnerprofile/components/PartnerProfileForm.php
    /**
     * registerSettings used by the backend.
     */
    public function registerSettings() {}

    public function registerNavigation()
    {
        return [
            'partnerprofile' => [
                'label' => 'Партнёрский профиль',
                'url' => Backend::url('dibrovnik/partnerprofile/fields'),
                'icon' => 'icon-folder',
                'permissions' => ['dibrovnik.partnerprofile.manage_categories'],
                'order' => 500,
                'sideMenu' => [
                    'verify' => [
                        'label' => 'Управление партнерами',
                        'url' => Backend::url('dibrovnik/partnerprofile/partnerscontroller'),
                        'icon' => 'icon-user',
                        'permissions' => ['dibrovnik.partnerprofile.manage_categories'],
                    ],
                    'fields' => [
                        'label' => 'Поля профиля',
                        'url' => Backend::url('dibrovnik/partnerprofile/fields'),
                        'icon' => 'icon-pencil',
                        'permissions' => ['dibrovnik.partnerprofile.manage_categories'],
                    ],
                    'categories' => [
                        'label' => 'Категории',
                        'url' => Backend::url('dibrovnik/partnerprofile/category'),
                        'icon' => 'icon-list-ul',
                        'permissions' => ['dibrovnik.partnerprofile.manage_categories'],
                    ],
                    'checkbox-setting' => [
                        'label' => 'Настройки чекбокса',
                        'url' => Backend::url('dibrovnik/partnerprofile/checkbox'),
                        'icon' => 'icon-list-ul',
                        'permissions' => ['dibrovnik.partnerprofile.manage_categories'],
                    ],
                    'prices-setting' => [
                        'label' => 'Настройки цен',
                        'url' => Backend::url('dibrovnik/partnerprofile/prices'),
                        'icon' => 'icon-list-ul',
                        'permissions' => ['dibrovnik.partnerprofile.manage_categories'],
                    ],
                    'dashboard-setting' => [
                        'label' => 'Настройка дэшборда',
                        'url' => Backend::url('dibrovnik/partnerprofile/dashboard'),
                        'icon' => 'icon-list-ul',
                        'permissions' => ['dibrovnik.partnerprofile.manage_categories'],
                    ],
                    'dashboard-setting' => [
                        'label' => 'Настройка таксономий',
                        'url' => Backend::url('dibrovnik/partnerprofile/taxonomy'),
                        'icon' => 'icon-list-ul',
                        'permissions' => ['dibrovnik.partnerprofile.manage_categories'],
                    ],
                    
                ],
            ],
        ];
    }
    public function registerPermissions()
    {
        return [
            'dibrovnik.partnerprofile.manage_categories' => [
                'label' => 'Управление категориями',
                'tab' => 'Партнёрский профиль'
            ],
        ];
    }
}
