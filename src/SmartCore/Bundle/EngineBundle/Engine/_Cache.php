<?php


class Cache extends Controller
{
    protected $dir_cache_pages;
    protected $dir_cache_nodes;
    
    /**
     * Constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->dir_cache_pages = DIR_CACHE . 'pages/';
        $this->dir_cache_nodes = DIR_CACHE . 'nodes/';
        // @todo сделать настройку сборщика мусора, пока вызывается всегда.
        $this->garbageCollector(0);    
    }
    
    /**
     * NewFunction
     *
     * @param 
     * @return bool
     */
    public function create($id, $element, $valid_to_timestamp)
    {
        $sql = "
            INSERT INTO {$this->DB->prefix()}cache
                (site_id, id, element, valid_to_timestamp)
            VALUES
                ('{$this->engine('env')->site_id}', '{$id}', '$element', '$valid_to_timestamp') ";
        $this->DB->query($sql, false);
        
        return $this->DB->errorCode() != 0 ? false : true;
    }
    
    /**
     * Создать связи элементов с объектамм.
     *
     * @param string $id - обычно уже хешированный ид кеша.
     * @param string $element
     * @param string $object - имя объект к которому выполняется привязка.
     * @param string $object_id - ИД объектов к которым выполняется привязка.
     * 
     * @todo оптимизировать.
     */
    public function createRelation($id, $element, $object, $object_id)
    {
        $values = '';
        if (is_array($object_id)) {
            foreach ($object_id as $key => $dummy) {
                $values .= "\n\t('{$this->engine('env')->site_id}', '{$id}', '$element', '$object', '$key' ),";
            }
            $values = substr($values, 0, strlen($values)-1);
        } else {
            $values .= "\n\t('{$this->engine('env')->site_id}', '{$id}', '$element', '$object', '$object_id' )";
        }        

        $sql = "INSERT INTO {$this->DB->prefix()}cache_relations (site_id, id, element, object, object_id ) VALUES $values ";
        $this->DB->query($sql, false);
    }
    
    /**
     * Вызвать событие кешера.
     *
     * @param int $folder_id
     * @return
     */
    public function update($object, $object_id)
    {
        $sql = "SELECT id, element
            FROM {$this->DB->prefix()}cache_relations
            WHERE site_id = '{$this->engine('env')->site_id}'
            AND object = '$object'
            AND object_id = '$object_id' ";
        $result = $this->DB->query($sql);
        while ($row = $result->fetchObject()) {
            $this->remove($row->id, $row->element);
        }
    }
    
    /**
     * Вызвать событие кешера, по изменению папки.
     *
     * @param int $folder_id
     * @return
     */
    public function updateFolder($folder_id)
    {
        $this->update('folder', $folder_id);
    }
    
    /**
     * Вызвать событие кешера, по изменению ноды.
     *
     * @param int $folder_id
     * @return
     */
    public function updateNode($node_id)
    {
        $this->update('node', $node_id);
    }
    
    /**
     * Базовое удаление.
     *
     * @param int $id
     * @param string $id
     */
    public function commonDelete($id, $element)
    {
        $this->DB->exec("DELETE FROM {$this->DB->prefix()}cache 
            WHERE site_id = '{$this->engine('env')->site_id}' 
            AND id = '$id' AND element = '$element' ");
        $this->DB->exec("DELETE FROM {$this->DB->prefix()}cache_relations 
            WHERE site_id = '{$this->engine('env')->site_id}' 
            AND id = '$id'  AND element = '$element' ");
    }

    /**
     * Удаление.
     *
     * @param hash string $id
     * @param string $element
     */
    public function remove($id, $element)
    {
        $this->commonDelete($id, $element);
        switch ($element) {
            case 'page':
                Cache_Page::delete($id);
                break;
            case 'node_html':
                Cache_Node::delete($id);
                break;
            default;
        }            
    }
    
    /**
     * Проверить на валидность.
     * 
     * @param string $id
     * @param string $element
     * @return bool
     * 
     * @todo проверять наличие и валидность в БД надо, но этот запрос весьма медленный... по этому лучше придумать что-то другое... может хранить инфу в мемкеше.
     */
    public function check($id, $element)
    {
        $sql = "SELECT id
            FROM {$this->DB->prefix()}cache
            WHERE site_id = '{$this->engine('env')->site_id}'
            AND valid_to_timestamp > '" . time() . "'
            AND id = '$id'
            AND element = '$element'";
        $result = $this->DB->query($sql);
        
        return $result->rowCount() == 1 ? true : false;
    }
    
    /**
     * Уборщик мусора.
     *
     * @param int $probability - Вероятность срабатывания. 0 - всегда (по умолчанию 1 из 100).
     * @param int $max_items - Максимальное кол-во записей, которое будет удалено.
     */
    public function garbageCollector($probability = 100, $max_items = 10)
    {
        if (rand(0 , $probability) > 0) {
            return false;
        }
        
        $sql = "SELECT id, element
            FROM {$this->DB->prefix()}cache
            WHERE site_id = '{$this->engine('env')->site_id}'
            AND valid_to_timestamp < '" . time() . "'
            LIMIT 0, $max_items ";
        $result = $this->DB->query($sql);

        while ($row = $result->fetchObject()) {
            $this->commonDelete($row->id, $row->element);
            switch ($row->element) {
                case 'page':
                    Cache_Page::delete($row->id);
                    break;
                case 'node_html':
                    Cache_Node::delete($row->id);
                    break;
                default;
            }
        }
    }
}
