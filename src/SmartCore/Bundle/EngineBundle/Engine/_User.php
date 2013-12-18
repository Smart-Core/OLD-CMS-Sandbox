<?php
namespace SmartCore\Bundle\EngineBundle\Engine;

use Symfony\Component\DependencyInjection\ContainerInterface;

class User
{
    protected $roles = [];

    public function __construct(ContainerInterface $container)
    {
        if ($container->has('security.context') and $container->get('security.context')->getToken() !== null) {
            foreach ($container->get('security.context')->getToken()->getRoles() as $Role) {
                $this->roles[] = $Role->getRole();
            }
        }

        if (empty($this->roles)) {
            $this->roles[] = 'ROLE_GUEST';
        } else {
            $this->updateRoles($container->getParameter('security.role_hierarchy.roles'));
        }
    }

    /**
     * Обновление групп юзеров.
     * 
     * @todo чтение дополнительных иерархий ролей из БД (engine_roles*)
     */
    protected function updateRoles($system_roles)
    {
        $add_roles = [];

        foreach ($this->roles as $role) {
            $add_roles[$role] = 1;
            foreach ($system_roles as $system_role => $system_inherit_roles) {
                if ($system_role === $role) {
                    foreach ($system_inherit_roles as $irole) {
                        $add_roles[$irole] = 1;
                    }
                }
                
            }
        }

        $this->roles = array_keys($add_roles);
    }

    /**
     * Получить список групп текущего юзера.
     * 
     * @return array
     */
    public function getRoles()
    {
        return $this->roles;
    }

    /**
     * Проверка, обладает ли юзер указанной ролью.
     * 
     * @param string $role
     * @return bool
     */
    public function hasRole($role)
    {
        foreach ($this->roles as $has_role) {
            if ($has_role === $role) {
                return true;
            }
        }
        return false;
    }
}
