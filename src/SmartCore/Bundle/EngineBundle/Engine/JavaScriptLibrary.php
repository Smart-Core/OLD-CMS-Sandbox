<?php 

namespace SmartCore\Bundle\EngineBundle\Engine;

use Symfony\Component\DependencyInjection\ContainerAware;

/**
 * @todo переделать! выделить в отдельный бандл.
 */
class JavaScriptLibrary extends ContainerAware
{
    /**
     * Список всех прописаных скриптов.
     *
     * @var array
     */
    protected $scripts;

    /**
     * Список запрошенных библиотек.
     */
    protected $called_libs = [];
    
    /**
     * @var \Doctrine\DBAL\Connection
     */
    protected $db;
    
    /**
     * Constructor.
     */
    public function __construct(\Doctrine\DBAL\Connection $db)
    {
        $this->db      = $db;
        $this->scripts = [];

        $sql = "SELECT script_id, name, related_by, current_version, files
            FROM javascript_library
            ORDER BY pos DESC ";
        $result = $this->db->query($sql);
        while($row = $result->fetchObject()) {
            $this->scripts[$row->name] = [
                'script_id' => $row->script_id,
                'related_by' => $row->related_by,
                'current_version' => $row->current_version,
                'files' => $row->files,
                // не обязательные свойства.
                //'title' => $row->title,
                //'homepage' => $row->homepage,
                //'descr' => $row->descr,
            ];
        }
    }
    
    /**
     * Запросить библиотеку.
     *
     * @param string $name
     * @param string $version
     */
    public function call($name, $version = false)
    {
        $this->called_libs[$name] = $version;
    }

    /**
     * Получить список запрошенных либ.
     *
     * @return array
     */
    public function all()
    {
        $output = [];
        
        // Т.к. запрашивается в произвольном порядке - сначала надо сформировать массив с ключами в правильном порядке.
        foreach ($this->scripts as $key => $value) {
            $output[$key] = false;
        }

        // Затем вычисляются зависимости.
        $flag = 1;
        while ($flag == 1) {
            $flag = 0;
            foreach ($this->called_libs as $name => $value) {
                // @todo пока можно обработать зависимость только от одной либы, далее надо сделать списки, например "prototype, scriptaculous".
                if (!empty($this->scripts[$name]['related_by']) and !isset($this->called_libs[$this->scripts[$name]['related_by']])) {
                    $this->called_libs[$this->scripts[$name]['related_by']] = false;
                    $flag = 1;
                }
            }
        }

        // @todo сделать возможность конфигурирования из файлов.
        foreach ($this->called_libs as $name => $version) {
            $sql_version = empty($version) ? " AND version = '{$this->scripts[$name]['current_version']}' " : " AND version = '$version' ";
            
            $sql = "SELECT path
                FROM javascript_library_paths
                WHERE script_id = '" . $this->scripts[$name]['script_id'] . "'
                $sql_version ";                
            $result = $this->db->query($sql);
            if ($result->rowCount() == 1) {
                $row = $result->fetchObject();
                $path = strpos($row->path, 'http://') === 0 ? $row->path : $this->container->get('engine.context')->getGlobalAssets() . $row->path; // @todo https://  и просто //
            } else {
                $sql = "SELECT path 
                    FROM javascript_library_paths
                    WHERE script_id = '" . $this->scripts[$name]['script_id'] . "'
                    $sql_version ";
                $path = $this->container->get('engine.context')->getGlobalAssets() . $this->db->fetchAssoc($sql)['path'];
            }
            
            foreach (explode(',', $this->scripts[$name]['files']) as $file) {
                if (substr($file, strrpos($file, '.') + 1) === 'css') {
                    $output[$name]['css'][] = $path . $file;
                }

                if (substr($file, strrpos($file, '.') + 1) === 'js') {
                    $output[$name]['js'][] = $path . $file;
                }
            }
        }
        
        // Удаляются пустые ключи
        foreach ($output as $key => $value) {
            if ($output[$key] === false) {
                unset($output[$key]);
            }
        }
        
        return $output;
    }
    
    /**
     * Получить весь список доступных скриптов.
     *
     * @return array
     */
    public function getAll()
    {
        return $this->scripts;
    }
}
