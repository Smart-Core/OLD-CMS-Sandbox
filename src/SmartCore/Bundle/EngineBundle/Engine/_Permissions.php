<?php
namespace SmartCore\Bundle\EngineBundle\Engine;

use Symfony\Component\DependencyInjection\ContainerInterface;

class Permissions
{
    private $db;
    private $permissions = [];
    private $user_id = 0;
    private $user_roles = [];

    // @todo remove!
    private $user_groups;

    /**
     * Constructor.
     */
    public function __construct(ContainerInterface $container)
    {
        $this->db = $container->get('database_connection');
        $this->user_id = $container->get('engine.user')->getId();
        $this->user_roles = $container->get('engine.user')->getRoles();
        $this->init();
    }

    /**
     * - Заполняется массив со значениями прав доступа по умолчанию для всех объектов
     *
     * @todo сейчас для инициализации массива выполняется 2 запроса в БД, в будущем надо предусмотреть возможность кеширования.
     * т.е. кешироваться будут массивы для каждой группы пользователей, а сброс кеша надо будет производить при каждом изменении,
     * связанными с группами пользователей (добавление, удаление, изменении связей) а также с правами (добавление, удаление
     * действий, изменения значений по умолчанию, )
     */
    protected  function init()
    {
        // @todo вычисление какие группы прав присущи заданному сайту.
        $sql = "SELECT group_id, name, default_access FROM engine_permissions";
        $result = $this->db->query($sql);
        while ($row = $result->fetchObject()) {
            $this->permissions[$row->group_id][$row->name] = $row->default_access;
        }

        $begin = true;
        $where = 'WHERE ';
        foreach ($this->user_roles as $role) {
            if ($begin) {
                $begin = false;
            } else {
                $where .= ' OR';
            }
            $where .= " role_id = '$role'";
        }

        if ($begin === false) {
            $sql = "SELECT permission, max(access) AS access
                FROM engine_permissions_defaults
                $where
                GROUP BY permission";
            $result = $this->db->query($sql);
            while ($row = $result->fetchObject()) {
                $temp = explode(':', $row->permission);
                $this->permissions[$temp[0]][$temp[1]] = $row->access;
            }
        }
    }

    /**
     * Перестройка значенией.
     */
    public function rebuild()
    {
        $this->init();
    }

    /**
     * Является ли админом.
     *
     * @return bool
     */
    public function isAdmin()
    {
        return true; // @todo заглушка.
        //return isset($this->user_groups[$this->Settings->getParam('admin_group_id')]) ? true : false;
    }

    /**
     * Является ли суперадмином.
     *
     * @return bool
     */
    public function isRoot()
    {
        return true; // @todo заглушка.
        //return isset($this->user_groups[$this->Settings->getParam('root_group_id')]) ? true : false;
    }

    /**
     * Метод получения разрешения на доступ
     * 
     * @param $object - объект (ресурс в ZF) к которому запрашивается право на дейсвие. по этому
     *        параметру берется значения по умолчанию. [folder / node / news / comments ... ]
     *        @todo скорее всего будет только 3 типа объектов, это [folder / node / module ]
     * 
     * @param $action - дейсвие (привелегия в ZF). [read / write / view / edit_forighn_news ... ]
     * 
     * @param $permissions - строка с правами доступа, по которой надо принять решение, позволено 
     *        ли применять действие $action, к объекту $object.
     * 
     * @param $user_id = null, по умолчанию рассматривается активный юзер @todo сделать для разных юзеров.
     *
     * @return bool
     *
     * @todo переименовать в isGranted()?
     */
    public function isAllowed($object, $action, $permissions, $user_id = null) 
    {
        return true; // @todo заглушка.

        $user_permissions = [];
        // echo "\$object = <b>$object</b>, \$action = <b>$action</b>, \$permissions = <b>$permissions</b> <hr />";

        // Получаем значение по умолчанию.
        if (isset($this->default_permissions[$object][$action])) {
            $result = $this->default_permissions[$object][$action];
        } else {
            die ("incorrect permission role. <b>$action</b> in <b>$object</b>");
        }

        // Если пользователь обладает входит в группу root, то ему дозволено всё, по этому проверка дальше не производится, а просто возвращается положительное значение.
        if (isset($this->user_groups[$this->Settings->getParam('root_group_id')])) {
            return 1;
        }

        /**
         * Права установлены (т.е. из БД пришел не NULL и строка имеет длину)
         * 
         * Генерируем массив вида:
         * [group_id_1]
         *     [action1] = 1|0
         *     [action2] = 1|0
         *     [action3] = 1|0
         * [group_id_2]
         */
        if (isset($permissions) and strlen($permissions) > 0) {
            // Парсим строку и генерим массив. Перезаписываем его обратно в $permissions.
            $temp = explode(';', $permissions);
            $permissions = [];
            foreach ($temp as $key => $value) {
                $t2 = explode('|', $value);
                if (is_numeric($t2[0])) { // обрабатываются права на группы
                    $t3 = explode(',', $t2[1]);
                    foreach ($t3 as $key => $value) {
                        $t4 = explode(':', $value);
                        $permissions[$t2[0]][$t4[0]] = $t4[1];
                    }
                } elseif (substr($t2[0], 0, 1) == 'u' and substr($t2[0], 1) == $this->user_id) { // отдельные права для юзера
                    $t3 = explode(',', $t2[1]);
                    foreach ($t3 as $key => $value) {
                        $t4 = explode(':', $value);
                        if ($t4[0] == $action) { // указанно действие для юзера, по этому игнорируем права групп и возвращаем значение.
                            return $t4[1];
                        }
                        
                    }
                }
            }
            // sc_dump($permissions, 'массив permissions после разбора строки');

            foreach ($this->user_groups as $key => $value) {
                if (isset($permissions[$key][$action])) { // Значение для группы есть,
                    $result = $permissions[$key][$action]; // по этому переписываем право на дейсвие.
                    if ($result == 1) { // если переписанное значение разрешающее, то прекращаем перебор массивов и возвращаем положительное значение.
                        break;
                    }
                }
            }
        }
        return $result;
    }
}
