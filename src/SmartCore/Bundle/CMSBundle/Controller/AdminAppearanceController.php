<?php

namespace SmartCore\Bundle\CMSBundle\Controller;

use Knp\RadBundle\Controller\Controller;
use SmartCore\Bundle\CMSBundle\Entity\AppearanceHistory;
use Symfony\Component\Finder\Finder;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;

class AdminAppearanceController extends Controller
{
    /**
     * @return Response
     */
    public function indexAction()
    {
        return $this->render('CMSBundle:AdminAppearance:index.html.twig', [
            'styles'         => $this->getStyles(),
            'styles_path'    => 'web'.$this->get('settings')->get('cms', 'appearance_styles_path'),
            'templates'      => $this->getTemplates(),
            'templates_path' => 'app/Resources/views/',
        ]);
    }

    /**
     * @param Request $request
     * @param string $name
     * @return Response|RedirectResponse
     */
    public function templateEditAction(Request $request, $name)
    {
        $template_file_path = $this->container->getParameter('kernel.root_dir').'/Resources/views/'.$name.'.html.twig';

        if (!file_exists($template_file_path)) {
            return $this->redirectToRoute('cms_admin_appearance');
        }

        $template_code = file_get_contents($template_file_path);

        if ($request->isMethod('POST')) {
            $twig = $this->get('twig');

            $template_code = $request->request->get('template_code');

            try {
                $twig->parse($twig->tokenize($template_code));

                $twig->clearCacheFiles();

                $history = new AppearanceHistory();
                $history
                    ->setPath('/Resources/views/')
                    ->setFilename($name.'.html.twig')
                    ->setCode($template_code)
                    ->setUserId($this->getUser())
                ;

                $this->persist($history, true);

                file_put_contents($template_file_path, $template_code);

                $this->addFlash('success', 'Шаблон обновлён.');

                return $this->redirectToRoute('cms_admin_appearance_template', ['name' => $name]);
            } catch (\Twig_Error_Syntax $e) {
                $this->addFlash('error', $e->getMessage());
            }
        }

        return $this->render('CMSBundle:AdminAppearance:template_edit.html.twig', [
            'name'          => $name,
            'templates'     => $this->getTemplates(),
            'template_code' => $template_code,
        ]);
    }

    /**
     * @param string $name
     * @return Response|RedirectResponse
     */
    public function templateHistoryAction($name)
    {
        /** @var \Doctrine\ORM\EntityManager $em */
        $em = $this->getDoctrine()->getManager();

        $histories = $em->getRepository('CMSBundle:AppearanceHistory')->findBy([
            'path'       => '/Resources/views/',
            'filename'   => $name.'.html.twig',
        ], ['created_at' => 'DESC']);

        return $this->render('CMSBundle:AdminAppearance:template_history.html.twig', [
            'name' => $name,
            'histories' => $histories,
        ]);
    }

    /**
     * @param string $name
     * @return Response|RedirectResponse
     */
    public function styleHistoryAction($name)
    {
        /** @var \Doctrine\ORM\EntityManager $em */
        $em = $this->getDoctrine()->getManager();

        $histories = $em->getRepository('CMSBundle:AppearanceHistory')->findBy([
            'path'       => '/../web'.$this->get('settings')->get('cms', 'appearance_styles_path'),
            'filename'   => $name,
        ], ['created_at' => 'DESC']);

        return $this->render('CMSBundle:AdminAppearance:style_history.html.twig', [
            'name' => $name,
            'histories' => $histories,
        ]);
    }

    /**
     * @param int $id
     * @return Response
     */
    public function historyCodeAction($id)
    {
        /** @var \Doctrine\ORM\EntityManager $em */
        $em = $this->getDoctrine()->getManager();

        $history = $em->getRepository('CMSBundle:AppearanceHistory')->find($id);

        if (empty($history)) {
            throw $this->createNotFoundException();
        }

        return $this->render('CMSBundle:AdminAppearance:history_code.html.twig', [
            'history' => $history,
        ]);
    }

    /**
     * @param int $id
     * @return Response
     */
    public function historyRollbackAction($id)
    {
        /** @var \Doctrine\ORM\EntityManager $em */
        $em = $this->getDoctrine()->getManager();

        $history = $em->getRepository('CMSBundle:AppearanceHistory')->find($id);

        if (empty($history)) {
            throw $this->createNotFoundException();
        }

        $template_file_path = $this->container->getParameter('kernel.root_dir').$history->getPath().$history->getFilename();

        if (file_exists($template_file_path)) {
            file_put_contents($template_file_path, $history->getCode());

            $this->get('twig')->clearCacheFiles();
            $this->addFlash('success', 'Файл <b>'.$history->getPath().$history->getFilename().'</b> восстановлен на дату: '.$history->getCreatedAt()->format('Y-m-d H:i:s'));
        } else {
            $this->addFlash('error', 'Файл <b>'.$history->getPath().$history->getFilename().'</b> не найден.');
        }

        return $this->redirectToRoute('cms_admin_appearance');
    }

    /**
     * @param Request $request
     * @param string $name
     * @return Response|RedirectResponse
     */
    public function styleEditAction(Request $request, $name)
    {
        $style_file_path = $this->container->getParameter('kernel.root_dir').'/../web'.$this->get('settings')->get('cms', 'appearance_styles_path').$name;

        if (!file_exists($style_file_path)) {
            return $this->redirectToRoute('cms_admin_appearance');
        }

        $style_code = file_get_contents($style_file_path);

        if ($request->isMethod('POST')) {
            $style_code = $request->request->get('style_code');

            $history = new AppearanceHistory();
            $history
                ->setPath('/../web'.$this->get('settings')->get('cms', 'appearance_styles_path'))
                ->setFilename($name)
                ->setCode($style_code)
                ->setUserId($this->getUser())
            ;

            $this->persist($history, true);

            file_put_contents($style_file_path, $style_code);

            $this->addFlash('success', 'Стиль обновлён.');

            return $this->redirectToRoute('cms_admin_appearance_style', ['name' => $name]);
        }

        return $this->render('CMSBundle:AdminAppearance:style_edit.html.twig', [
            'name'          => $name,
            'styles'        => $this->getStyles(),
            'style_code'    => $style_code,
        ]);
    }

    /**
     * @return array
     */
    protected function getTemplates()
    {
        $finder = new Finder();
        $finder->files()->sortByName()->name('*.html.twig')->in($this->container->getParameter('kernel.root_dir').'/Resources/views');

        $templates = [];
        /** @var \Symfony\Component\Finder\SplFileInfo $file */
        foreach ($finder as $file) {
            $templates[] = str_replace('.html.twig', '', $file->getFilename());
        }

        return $templates;
    }

    /**
     * @return array
     */
    protected function getStyles()
    {
        $finder = new Finder();
        $finder->files()->sortByName()->name('*.css')->name('*.less')->in($this->container->getParameter('kernel.root_dir').'/../web'.$this->get('settings')->get('cms', 'appearance_styles_path'));

        $styles = [];
        /** @var \Symfony\Component\Finder\SplFileInfo $file */
        foreach ($finder as $file) {
            $styles[] = $file->getFilename();
        }

        return $styles;
    }
}
