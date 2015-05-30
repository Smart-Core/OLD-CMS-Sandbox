<?php

namespace SmartCore\Bundle\CMSBundle\Controller;

use Knp\RadBundle\Controller\Controller;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\BufferedOutput;
use Symfony\Component\Finder\Finder;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Kernel;

class AdminController extends Controller
{
    /**
     * @return RedirectResponse
     */
    public function indexAction()
    {
        if (!$this->get('security.context')->isGranted('ROLE_ADMIN')) {
            return $this->render('CMSBundle:User:login.html.twig');
        }

        $dashboard = [];

        foreach ($this->get('cms.module')->all() as $module) {
            $data = $module->getDashboard();

            if (!empty($data['items'])) {
                $dashboard[$module->getShortName()] = $data;
            }
        }

        return $this->render('CMSBundle:Admin:index.html.twig', [
            'dashboard' => $dashboard,
        ]);
    }

    /**
     * @return Response
     */
    public function notFoundAction()
    {
        return $this->render('CMSBundle:Admin:not_found.html.twig', []);
    }

    /**
     * @return Response
     */
    public function reportsAction()
    {
        $system = [
            'php' => $this->getPhpSettings(),
            'platform' => $this->getPlatformInfo(),
        ];

        return $this->render('CMSBundle:Admin:reports.html.twig', [
            'system' => $system,
        ]);
    }

    /**
     * Получить информацию о платформе.
     *
     * @return array
     */
    protected function getPlatformInfo()
    {
        $db = $this->get('database_connection');

        $data = [];
        // Smart Core Version
        $data[] = [
            'title' => 'Smart Core CMS version',
            'value' => 'v0.5',
            'required' => '',
            'recomended' => '',
            'hint' => '',
            'warning' => 0,
        ];
        $data[] = [
            'title' => 'Symfony2 Framework version',
            'value' => Kernel::VERSION,
            'required' => '',
            'recomended' => '',
            'hint' => '',
            'warning' => 0,
        ];
        $data[] = [
            'title' => 'Database server',
            'value' => $db->query('SHOW VARIABLES LIKE "%version_comment%"')->fetchObject()->Value.' version '.$db->query('SELECT version() AS version')->fetchObject()->version,
            'required' => 'MySQL 5.1',
            'recomended' => 'MySQL 5.5+ or PostgreSQL 9.3+',
            'hint' => '',
            'warning' => 0,
        ];
        $data[] = [
            'title' => 'Web Server',
            'value' => $_SERVER['SERVER_SOFTWARE'].' ('.php_sapi_name().')',
            'required' => '',
            'recomended' => '',
            'hint' => '',
            'warning' => 0,
        ];
        return $data;
    }

    /**
     * Получить настрйоки PHP.
     *
     * @return array
     */
    protected function getPhpSettings()
    {
        $data = [];
        $data[] = [
            'title' => 'PHP Version',
            'value' => phpversion().' ('.php_uname('m').')',
            'required' => '5.4.1',
            'recomended' => '5.5.9+',
            'hint' => '',
            'warning' => 0,
        ];
        $data[] = [
            'title' => 'Memory Limit',
            'value' => ini_get('memory_limit'),
            'required' => '128M',
            'recomended' => '256M',
            'hint' => '',
            'warning' => 0,
        ];
        // Safe Mode
        if (ini_get('safe_mode')) {
            $value = 'On';
        } else {
            $value = 'Off';
        }
        $data[] = [
            'title' => 'Safe Mode',
            'value' => $value,
            'required' => 'Off',
            'recomended' => '',
            'hint' => '',
            'warning' => 0,
        ];
        // Display Errors
        if (ini_get('display_errors')) {
            $value = 'On';
        } else {
            $value = 'Off';
        }
        $data[] = [
            'title' => 'Display Errors',
            'value' => $value,
            'required' => 'Off',
            'recomended' => '',
            'hint' => '',
            'warning' => 0,
        ];
        $data[] = [
            'title' => 'Magic Quotes',
            'value' => ini_get('magic_quotes_gpc') ? 'On' : 'Off',
            'required' => 'Off',
            'recomended' => '',
            'hint' => '',
            'warning' => 0,
        ];
        $data[] = [
            'title' => 'Register Globals',
            'value' => ini_get('register_globals') ? 'On' : 'Off',
            'required' => 'Off',
            'recomended' => '',
            'hint' => '',
            'warning' => 0,
        ];
        $data[] = [
            'title' => 'Output Buffering',
            'value' => ((bool)ini_get('output_buffering')) ? 'On' : 'Off',
            'required' => 'On',
            'recomended' => '',
            'hint' => '',
            'warning' => 0,
        ];
        $data[] = [
            'title' => 'Mbstring Enabled',
            'value' => extension_loaded('mbstring') ? 'Yes' : 'No',
            'required' => 'Yes',
            'recomended' => '',
            'hint' => '',
            'warning' => 0,
        ];
        // Upload_max_filesize
        $data[] = [
            'title' => 'Upload max filesize',
            'value' => ini_get('upload_max_filesize'),
            'required' => '4M',
            'recomended' => '20M',
            'hint' => '',
            'warning' => 0,
        ];
        return $data;
    }
    
    /**
     * Renders Elfinder.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function elfinderAction()
    {
        return $this->render('CMSBundle:Admin:elfinder.html.twig', [
            'fullscreen' => true,
            'includeAssets' => $this->container->getParameter('fm_elfinder')['instances']['default']['include_assets'],
        ]);
    }

    /**
     * Отображение списка модулей.
     *
     * @return Response
     */
    public function moduleAction()
    {
        return $this->render('CMSBundle:Admin:module.html.twig', [
            'modules' => $this->get('cms.module')->all(),
        ]);
    }

    /**
     * @param string $filename
     */
    public function moduleInstallAction($filename = null)
    {
        $finder = new Finder();

        if (is_dir($this->get('kernel')->getRootDir().'/../dist')) {
            $finder
                ->ignoreDotFiles(false)
                ->ignoreVCS(true)
                ->name('*.zip')
                ->in($this->get('kernel')->getRootDir().'/../dist');
        } else {
            $finder = [];
        }

        // @todo убрать в сервис.
        if (!empty($filename)) {
            $this->get('cms.module')->install($filename);
        }

        return $this->render('CMSBundle:Admin:module_install.html.twig', [
            'modules'  => $finder,
            'filename' => $filename,
        ]);
    }

    /**
     * AJAX обновление БД.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function moduleInstallUpdateDbAction(Request $request)
    {
        if ($request->isXmlHttpRequest()) {
            $application = new Application($this->get('kernel'));
            $application->setAutoExit(false);
            $input = new ArrayInput(['command' => 'doctrine:schema:update', '--force' => true]);
            $output = new BufferedOutput();

            $retval = $application->run($input, $output);

            return new Response('БД успешно обновлена.<p>'.$output->fetch().'</p>');
        } else {
            return new Response('Обновление БД возможно только через AJAX.');
        }
    }
}
