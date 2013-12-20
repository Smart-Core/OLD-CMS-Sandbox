<?php

namespace SmartCore\Bundle\EngineBundle\Engine;

use Symfony\Component\DependencyInjection\ContainerAware;

class EngineToolbar extends ContainerAware
{
    public function getArray()
    {
        $current_folder_id = $this->container->get('engine.context')->getCurrentFolderId();

        $router = $this->container->get('router');

        return [
            'left' => [
                'setings' => [
                    'title' => '',
                    'descr' => 'Настройки',
                    'icon' => 'wrench',
                    'items' => [
                        'files' => [
                            'title' => 'Файлы',
                            'icon' => 'cog',
                            'uri' => $router->generate('cmf_admin_files'),
                        ],
                        'blocks' => [
                            'title' => 'Блоки',
                            'icon' => 'th',
                            'uri' => $router->generate('cmf_admin_structure_block'),
                        ],
                        'modules' => [
                            'title' => 'Модули',
                            'icon' => 'cog',
                            'uri' => $router->generate('cmf_admin_module'),
                        ],
                        'appearance' => [
                            'title' => 'Оформление',
                            'icon' => 'picture',
                            'uri' => $router->generate('cmf_admin_appearance'),
                        ],
                        'users' => [
                            'title' => 'Пользователи',
                            'icon' => 'user',
                            'uri' => $router->generate('cmf_admin_users'),
                        ],
                        'config' => [
                            'title' => 'Конфигруация',
                            'icon' => 'tasks',
                            'uri' => $router->generate('cmf_admin_config'),
                        ],
                        'reports' => [
                            'title' => 'Отчеты',
                            'icon' => 'warning-sign',
                            'uri' => $router->generate('cmf_admin_reports'),
                        ],
                        'help' => [
                            'title' => 'Справка',
                            'icon' => 'question-sign',
                            'uri' => $router->generate('cmf_admin_help'),
                        ],
                    ],
                ],
                'structure' => [
                    'title' => 'Структура',
                    'descr' => '',
                    'icon' => 'folder-open',
                    'items' => [
                        'folder_edit' => [
                            'title' => 'Редактировать раздел',
                            'icon' => 'edit',
                            'uri' => $router->generate('cmf_admin_structure_folder', ['id' => $current_folder_id]),
                        ],
                        'folder_new' => [
                            'title' => 'Добавить раздел',
                            'icon' => 'plus',
                            'uri' => $router->generate('cmf_admin_structure_folder_create_in_folder', ['folder_pid' => $current_folder_id]),
                        ],
                        'folder_all' => [
                            'title' => 'Вся структура',
                            'icon' => 'book',
                            'uri' => $router->generate('cmf_admin_structure'),
                        ],
                        'diviver_1' => 'diviver',
                        'node_new' => [
                            'title' => 'Добавить модуль',
                            'icon' => 'plus',
                            'uri' => $router->generate('cmf_admin_structure_node_create_in_folder', ['folder_pid' => $current_folder_id]),
                        ],
                        'node_all' => [
                            'title' => 'Все модули на странице @todo',
                            'icon' => 'list-alt',
                            'uri' => $router->generate('cmf_admin_structure') . '/node/in_folder/2/', // @todo
                        ],
                    ],
                ],
            ],
            'right' => [
                'eip_toggle' => ["Просмотр", "Редактирование"],
                'user' => [
                    'title' => $this->container->get('security.context')->getToken()->getUser()->getUserName(),
                    'icon' => 'user',
                    'items' => [
                        'admin' => [
                            'title' => 'Admin',
                            'uri' => $router->generate('cmf_admin_structure'),
                            'icon' => 'cog',
                            'overalay' => false,
                        ],
                        'profile' => [
                            'title' => 'Мой профиль',
                            'uri' => $router->generate('fos_user_profile_show'),
                            'icon' => 'user',
                            'overalay' => false,
                        ],
                        'diviver_1' => 'diviver',
                        'logout' => [
                            'title' => "Выход",
                            'uri' => $router->generate('fos_user_security_logout'),
                            'icon' => "off",
                            'overalay' => false,
                        ],
                    ],
                ],
            ],
        ];
    }
}
