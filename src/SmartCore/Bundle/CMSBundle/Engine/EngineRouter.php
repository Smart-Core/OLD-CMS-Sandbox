<?php

namespace SmartCore\Bundle\CMSBundle\Engine;

use Symfony\Component\DependencyInjection\ContainerAwareTrait;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\Routing\Exception\MethodNotAllowedException;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;

class EngineRouter
{
    use ContainerAwareTrait;

    /**
     * @var array|null
     */
    protected $router_data = null;

    /**
     * @param  mixed|null $obj
     * @return string
     */
    public function getPath($obj = null)
    {
        return $this->container->get('cms.folder')->getUri($obj);
    }

    /**
     * Tries to match a URL path with a set of routes.
     *
     * If the matcher can not find information, it must throw one of the exceptions documented
     * below.
     *
     * @param  string $slug The path info to be parsed (raw format, i.e. not urldecoded)
     *
     * @return array  Массив следующего формата:
     *      [folders]: array
     *          {folder_id}: SmartCore\Bundle\CMSBundle\Entity\Folder
     *      [meta]: array
     *          [keywords]: string
     *          [description]: string
     *          [robots]: string
     *          [language]: string
     *          [author]: string
     *      [status]: int 200
     *      [template]: string "main"
     *      [node_route]: array
     *          @todo
     *      [current_folder_id]: int
     *      [current_folder_path]: string
     *
     * @throws ResourceNotFoundException If the resource could not be found
     * @throws MethodNotAllowedException If the resource was found but the request method is not allowed
     *
     * @api
     */
    public function match($baseUrl, $slug = null, $type = HttpKernelInterface::MASTER_REQUEST)
    {
        if ($type === HttpKernelInterface::MASTER_REQUEST) {
            if (!empty($this->router_data)) {
                return $this->router_data;
            }
            \Profiler::start('Folder Routing');
        }

        $data = [
            'folders'       => [],
            'meta'          => [],
            'status'        => 200,
            'template'      => 'index',
            'node_routing'  => null,
            'current_folder_id'   => 1,
            'current_folder_path' => $baseUrl . '/',
        ];

        $folder          = null;
        $parent_folder   = null;
        $router_node_id  = null;
        $slug            = '/' . $slug; // @todo сделать проверку на наличие слеша перед путём, чтобы привести к виду, как $this->container->get('request')->getPathInfo()
        $path_parts      = explode('/', $slug);

        foreach ($path_parts as $key => $segment) {
            // Проверка строки запроса на допустимые символы.
            // @todo сделать проверку на разрешение круглых скобок.
            if (!empty($segment) and !preg_match('/^[a-z_@0-9.-]*$/iu', $segment)) {
                $data['status'] = 404;
                break;
            }

            // Закончить работу, если имя папки пустое и папка не является корневой т.е. обрабатывается последняя запись в строке УРИ.
            if (0 != $key and '' == $segment and null == $router_node_id) {
                // @todo здесь надо делать обработчик "файла" т.е. папки с выставленным флагом "is_file".
                break;
            }

            // В данной папке есть нода которой передаётся дальнейший парсинг URI.
            if ($router_node_id !== null) {
                // Выполняется часть URI парсером модуля и возвращается результат работы, в дальнейшем он будет передан самой ноде.
                // @todo запрос ноды только для получения имени модуля не сосвсем красиво...
                //       может быть как-то кешировать это дело, либо хранить имя модуля прямо в таблице папок,
                //       например в виде массива router_node_id и router_node_module.
                try {
                    $node = $this->container->get('cms.node')->get($router_node_id);
                    $data['node_routing'] = [
                        'node_id'    => $router_node_id,
                        'controller' => $this->matchModule(
                            $node->getModule(),
                            str_replace($data['current_folder_path'], '', substr('/' . $baseUrl . '/', 0, -1) . $slug)
                        ),
                    ];
                    break;
                } catch (ResourceNotFoundException $e) {
                    // Роутинг модуля не нашел запрошенного ресурса.
                }
            }

            $folder = $this->container->get('doctrine.orm.entity_manager')->getRepository('CMSBundle:Folder')->findOneBy([
                'is_active'     => true,
                'is_deleted'    => false,
                'uri_part'      => empty($segment) ? null : $segment,
                'parent_folder' => $parent_folder
            ]);

            if ($folder) {
                if (true) { // @todo if ($this->Permissions->isAllowed('folder', 'read', $folder->permissions)) {
                    foreach ($folder->getMeta() as $meta_name => $meta_value) {
                        $data['meta'][$meta_name] = $meta_value;
                    }

                    if ($folder->getUriPart()) {
                        $data['current_folder_path'] .= $folder->getUriPart() . '/';
                    }

                    if ($folder->getTemplateInheritable()) {
                        $data['template'] = $folder->getTemplateInheritable();
                    }

                    $parent_folder = $folder;
                    $router_node_id = $folder->getRouterNodeId();
                    $data['folders'][$folder->getId()] = $folder;
                    $data['current_folder_id'] = $folder->getId();
                } else {
                    $data['status'] = 403;
                }
            } else {
                $data['status'] = 404;
            }
        }

        if ($folder and $folder->getTemplateSelf()) {
            $data['template'] = $folder->getTemplateSelf();
        }

        if ($type === HttpKernelInterface::MASTER_REQUEST) {
            \Profiler::end('Folder Routing');
            $this->router_data = $data;
        }

        return $data;
    }

    /**
     * @param  string $module
     * @param  string $path
     * @return array
     */
    public function matchModule($module, $path)
    {
        if ($this->container->has('cms.router_module.' . $module)) {
            return $this->container->get('cms.router_module.' . $module)->match($path);
        }

        return null;
    }

    /**
     * @param  string $module
     * @param  string $path
     * @return array
     */
    public function matchModuleAdmin($module, $path)
    {
        return $this->container->get('cms.router_module.' . $module . '.admin')->match($path);
    }

    /**
     * @param  string $module
     * @param  string $path
     * @return array
     */
    public function matchModuleApi($module, $path)
    {
        if ($this->container->has('cms.router_module_api.' . $module)) {
            return $this->container->get('cms.router_module_api.' . $module)->match($path);
        }

        return null;
    }

    /**
     * @param  mixed|null $obj
     * @return RedirectResponse
     */
    public function redirect($obj = null)
    {
        return new RedirectResponse($this->getPath($obj));
    }
}
