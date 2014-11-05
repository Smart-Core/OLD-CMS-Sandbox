<?php

namespace SmartCore\Bundle\CMSBundle\Engine;

use Symfony\Component\DependencyInjection\ContainerAware;

class EngineToolbar extends ContainerAware
{
    /**
     * @return array
     */
    public function getArray()
    {
        $current_folder_id = $this->container->get('cms.context')->getCurrentFolderId();

        $router = $this->container->get('router');
        $t  = $this->container->get('translator');

        // @todo кеширование по языку и юзеру.
        return [
            'left' => [
                'setings' => [
                    'title' => '',
                    'descr' => $t->trans('Settings'),
                    'icon' => 'wrench',
                    'items' => [
                        'modules' => [
                            'title' => $t->trans('Modules'),
                            'icon'  => 'cog',
                            'uri'   => $router->generate('cms_admin_module'),
                        ],
                        'files' => [
                            'title' => $t->trans('Files'),
                            'icon'  => 'file',
                            'uri'   => $router->generate('cms_admin_files'),
                        ],
                        'regions' => [
                            'title' => $t->trans('Regions'),
                            'icon'  => 'th',
                            'uri'   => $router->generate('cms_admin_structure_region'),
                        ],
                        'users' => [
                            'title' => $t->trans('Users'),
                            'icon'  => 'user',
                            'uri'   => $router->generate('cms_admin_user'),
                        ],
                        'config' => [
                            'title' => $t->trans('Configuration'),
                            'icon'  => 'tasks',
                            'uri'   => $router->generate('cms_admin_config'),
                        ],
                        'appearance' => [
                            'title' => $t->trans('Appearance'),
                            'icon'  => 'picture',
                            'uri'   => $router->generate('cms_admin_appearance'),
                        ],
                        /*
                        'reports' => [
                            'title' => $t->trans('Reports'),
                            'icon' => 'warning-sign',
                            'uri' => $router->generate('cms_admin_reports'),
                        ],
                        'help' => [
                            'title' => $t->trans('Help'),
                            'icon' => 'question-sign',
                            'uri' => $router->generate('cms_admin_help'),
                        ],
                        */
                    ],
                ],
                'structure' => [
                    'title' => $t->trans('Structure'),
                    'descr' => '',
                    'icon'  => 'folder-open',
                    'items' => [
                        'folder_edit' => [
                            'title' => $t->trans('Edit folder'),
                            'icon'  => 'edit',
                            'uri'   => $router->generate('cms_admin_structure_folder', ['id' => $current_folder_id]),
                        ],
                        'folder_new' => [
                            'title' => $t->trans('Create folder'),
                            'icon'  => 'plus',
                            'uri'   => $router->generate('cms_admin_structure_folder_create_in_folder', ['folder_pid' => $current_folder_id]),
                        ],
                        'folder_all' => [
                            'title' => $t->trans('All structure'),
                            'icon'  => 'book',
                            'uri'   => $router->generate('cms_admin_structure'),
                        ],
                        'diviver_1' => 'diviver',
                        'node_new' => [
                            'title' => $t->trans('Add module'),
                            'icon'  => 'plus',
                            'uri'   => $router->generate('cms_admin_structure_node_create_in_folder', ['folder_pid' => $current_folder_id]),
                        ],
                        /*
                        'node_all' => [
                            'title' => $t->trans('Add modules on page'), // @todo
                            'icon' => 'list-alt',
                            'uri' => $router->generate('cms_admin_structure') . '/node/in_folder/2/', // @todo
                        ],
                        */
                    ],
                ],
            ],
            'right' => [
                'eip_toggle' => ["Режим правки: ОТКЛ.", "Режим правки: ВКЛ."], // @todo перевод // [$t->trans('Viewing'), $t->trans('Edit')],
                'user' => [
                    'title' => $this->container->get('security.context')->getToken()->getUser()->getUserName(),
                    'icon' => 'user',
                    'items' => [
                        'admin' => [
                            'title' => $t->trans('Control panel'),
                            'uri'   => $router->generate('cms_admin'),
                            'icon'  => 'cog',
                            'overalay' => false,
                        ],
                        'profile' => [
                            'title' => $t->trans('My profile'),
                            'uri'   => $router->generate('cms_admin_user_edit', ['id' => $this->container->get('security.context')->getToken()->getUser()->getId()]),
                            'icon'  => 'user',
                            'overalay' => false,
                        ],
                        'diviver_1' => 'diviver',
                        'logout' => [
                            'title' => $t->trans('Logout'),
                            'uri'   => $router->generate('cms_admin_logout'),
                            'icon'  => 'off',
                            'overalay' => false,
                        ],
                    ],
                ],
            ],
        ];
    }

    /**
     * @param array $nodes_front_controls
     */
    public function prepare(array $nodes_front_controls = null)
    {
        if ($this->get('security.context')->isGranted('ROLE_ADMIN')) {
            $cms_front_controls = [
                'toolbar' => $this->getArray(),
                'nodes'   => $nodes_front_controls,
            ];

            $this->get('smart.felib')->call('bootstrap');
            $this->get('smart.felib')->call('jquery-cookie');
            $this->get('html')
                ->css($this->get('templating.helper.assets')->getUrl('bundles/cms/css/frontend.css'))
                ->js($this->get('templating.helper.assets')->getUrl('bundles/cms/js/frontend.js'))
                ->appendToHead('<script type="text/javascript">var cms_front_controls = ' . json_encode($cms_front_controls, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT) . ';</script>');
            ;
        }
    }

    /**
     * Gets a service by id.
     *
     * @param string $id The service id
     *
     * @return object The service
     */
    protected function get($id)
    {
        return $this->container->get($id);
    }
}
