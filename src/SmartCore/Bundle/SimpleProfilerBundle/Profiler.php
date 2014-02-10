<?php
/**
 * https://github.com/jimrubenstein/php-profiler
 */
class Profiler
{
    /**
     * Used to insure that the {@link init} method is only called once.
     *
     * @see Profiler::init()
     * @var bool
     */
    protected static $init = false;

    /**
     * Used to identify when the profiler has been enabled.
     *
     * If <em>false</em> no profiling data is stored, in order to reduce the overhead of running the profiler
     *
     * @var bool
     */
    protected static $enabled = false;

    /**
     * Time the profiler was included
     *
     * This is used to calculate time-from-start values for all methods
     * as well as total running time.
     */
    protected static $globalStartTime = 0;
    protected static $globalStartMemory = 0;

    /**
     * Time the profiler 'ends'
     *
     * This is populated just before rendering output (see {@link Profiler::render()})
     */
    protected static $globalEndTime = 0;
    protected static $globalEndMemory = 0;

    /**
     * Total time script took to run
     */
    protected static $globalDuration = 0;

    /**
     * Used to identify when some methods are accessed internally
     * versus when they're used externally (as an api or so)
     *
     * @var string
     */
    protected static $profilerKey = null;

    /** @var array */
    protected static $p = array();

    /**
     * @var \Doctrine\DBAL\Logging\SQLLogger
     */
    protected static $dbLogger = null;

    /**
     * @var \Symfony\Component\HttpKernel\KernelInterface
     */
    protected static $kernel = null;

    /**
     * Create a constructor that basically says "don't construct me!"
     */
    public function __construct()
    {
        throw new \Exception('The Profiler class is a static class. Do not instantiate it, access all member methods statically.');
    }

    /**
     * Initialize the profiler
     */
    public static function init()
    {
        if (self::$init) {
            return;
        }

        self::$globalStartTime   = defined('START_TIME')   ? START_TIME   : microtime(true);
        self::$globalStartMemory = defined('START_MEMORY') ? START_MEMORY : memory_get_usage();
        self::$profilerKey       = md5(rand(1, 1000) . time());
        self::$init              = true;
    }

    /**
     * Check to see if the profiler is enabled
     *
     * @see profiler::enabled
     *
     * @return bool true if profiler is enabled, false if disabled
     */
    public static function isEnabled()
    {
        return self::$enabled;
    }

    /**
     * Enable the profiler
     *
     * @see profiler::enabled
     */
    public static function enable()
    {
        self::$enabled = true;
    }

    /**
     * Disable the profiler
     *
     * @see profiler::enabled
     */
    public static function disable()
    {
        self::$enabled = false;
    }

    /**
     * @param \Symfony\Component\HttpKernel\KernelInterface $kernel
     */
    public static function setKernel($kernel)
    {
        self::$kernel = $kernel;
        // @todo имя sql логгера.
        if ($kernel->getEnvironment() === 'prod' and $kernel->getContainer()->has('smart_simple_profiler.db.logger')) {
            self::$dbLogger = $kernel->getContainer()->get('smart_simple_profiler.db.logger');
        }
    }

    /**
     * Get the global memory usage in KB
     *
     * @param string unit a metric prefix to force the unit of bytes used (B, K, M, G)
     */
    public static function getMemUsage($unit = '')
    {
        $usage = memory_get_usage();

        if ($usage < 1e3 || $unit == 'B') {
            $unit = '';
        } elseif ($usage < 9e5 || $unit == 'K') {
            $usage = round($usage / 1e3, 2);
            $unit = 'K';
        } elseif ($usage < 9e8 || $unit == 'M') {
            $usage = round($usage / 1e6, 2);
            $unit = 'M';
        } elseif ($usage < 9e11 || $unit = 'G') {
            $usage = round($usage / 1e9, 2);
            $unit = 'G';
        } else {
            $usage = round($usage / 1e12, 2);
            $unit = 'T';
        }

        return array(
            'num' => $usage,
            'unit' => $unit,
        );
    }

    /**
     * Start a new step
     *
     * This is the most-called method of the profiler.  It initializes and returns a new step node.
     *
     * @param string $name name/identifier for your step. is used later in the output to identify this step
     */
    public static function start($name, $tag = 'all')
    {
        if (!self::isEnabled()) {
            return;
        }

        self::$p[$tag][$name]['start_memory'] = memory_get_usage();
        self::$p[$tag][$name]['start_time'] = microtime(true);

        if (!is_null(self::$dbLogger) and is_object(self::$dbLogger)) {
            self::$p[$tag][$name]['start_db_count'] = self::$dbLogger->currentQuery;
        }
    }

    /**
     * End a step
     *
     * End a step by name, or end all steps in the current tree.
     *
     * @param string $nodeName ends the first-found step with this name. (Note: a warning is generated if it's not the current step, because this is probably unintentional!)
     */
    public static function end($name, $tag = 'all')
    {
        if (!self::isEnabled()) return;

        if (isset(self::$p[$tag][$name]['start_memory'])) {
            self::$p[$tag][$name]['memory'] = memory_get_usage() - self::$p[$tag][$name]['start_memory'];
            self::$p[$tag][$name]['time'] = round(microtime(true) - self::$p[$tag][$name]['start_time'], 3) * 1000;
            unset(
                self::$p[$tag][$name]['start_memory'],
                self::$p[$tag][$name]['start_time']
            );
        }

        if (!is_null(self::$dbLogger) and is_object(self::$dbLogger) and isset(self::$p[$tag][$name]['start_db_count'])) {
            self::$p[$tag][$name]['db_query_count'] = self::$dbLogger->currentQuery - self::$p[$tag][$name]['start_db_count'];
            unset(
                self::$p[$tag][$name]['start_db_count']
                // @todo логгирование запросов.
                //self::$p[$tag][$name]['start_db_query_log']
            );
        }
    }

    /**
     * Render the profiler output.
     *
     * @param bool $isDetailed
     */
    public static function render($isDetailed = false)
    {
        if (!self::isEnabled()) {
            return null;
        }

        $precision = 3;

        $exec_time = microtime(true) - self::$globalStartTime;

        echo 'Time: <b>', round($exec_time, $precision) * 1000 , '</b> ms',
        '. Memory <b>', round((memory_get_usage() - self::$globalStartMemory) / 1024 / 1024, 2), '</b> MB (<b>',
        round((memory_get_peak_usage(true) - self::$globalStartMemory) / 1024 / 1024, 2), '</b> peak).';

        //echo ' Files: <b>' . count(get_included_files()) . "</b>.\n";

        if (!is_null(self::$dbLogger) and is_object(self::$dbLogger)) {
            echo ' DB: <b>' . self::$dbLogger->currentQuery . '</b>';

            $queries_time = 0;
            foreach (self::$dbLogger->queries as $value) {
                $queries_time += $value['executionMS'];
            }

            $delta = round($queries_time * 100 / $exec_time, 2);
            echo ' (<b>' . round($queries_time, $precision) * 1000 . "</b> ms, $delta %)." . "\n";
        }

        if (!empty(self::$p) and $isDetailed) {
            $summary_time = 0;
            $summary_memory = 0;
            $summary_db = 0;
            foreach (self::$p['all'] as $tag => $points) {
                $summary_time += (int) $points['time'];
                $summary_memory += (int) $points['memory'];
                $summary_db += (int) $points['db_query_count'];
            }

            echo "<br />Checkpoints summary time: <b>$summary_time</b>, memory: <b>"
                . round((memory_get_usage() - $summary_memory) / 1024 / 1024, 2)
                . " MB.</b>, DB: <b>$summary_db</b>. \n";

            self::dump(self::$p);
        }

        if (!empty(self::$kernel)
            and self::$kernel->getContainer()->has('ladybug')
            and self::$kernel->getContainer()->get('ladybug')->getVars() !== null
        ) {
            foreach (self::$kernel->getContainer()->get('ladybug')->getVars() as $key => $value) {
                echo '<br />' . $value['file'] . ' : <b>' . $value['line'] . '</b>' . $value['content'] . "<hr />\n\n";
            }
        }
    }

    /**
     * Простой дамп на базе print_r().
     *
     * @param $input
     * @param string|array $remove - Удаление из результатов каких-то данных.
     * @param string|array $highlight - Подсветить данные.
     */
    public static function dump($input, $remove = null, $highlight = null)
    {
        ob_start();
        echo "\n<pre>";

        if ($input === false) {
            echo "установлен в <b>false</b>";
        } elseif ($input === null) {
            echo "установлен в <b>null</b>";
        } else {
            print_r($input);
        }

        echo "</pre>\n";
        $output = ob_get_clean();
        $output = str_ireplace('    ', '  ', $output);

        if (!empty($remove)) {
            $output = str_ireplace($remove, '', $output);
        }

        if (!empty($highlight)) {
            $output = str_ireplace($highlight, '<b>' . $highlight . '</b>', $output);
        }

        echo($output);
    }
}

Profiler::init();
