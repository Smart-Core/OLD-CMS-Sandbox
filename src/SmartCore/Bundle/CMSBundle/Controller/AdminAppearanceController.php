<?php

namespace SmartCore\Bundle\CMSBundle\Controller;

use Knp\RadBundle\Controller\Controller;
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
            'styles'    => $this->getStyles(),
            'templates' => $this->getTemplates(),
        ]);
    }

    /**
     * @param Request $request
     * @param string $name
     * @return Response|RedirectResponse
     */
    public function templateEditAction(Request $request, $name)
    {
        $template_file_path = $this->container->getParameter('kernel.root_dir') . '/Resources/views/' . $name . '.html.twig';

        if (!file_exists($template_file_path)) {
            return $this->redirectToRoute('cms_admin_appearance');
        }

        $template_code = file_get_contents($template_file_path);

        if ($request->isMethod('POST')) {
            $twig = $this->get('twig');

            $template_code = $request->request->get('template_code');

            try {
                $twig->parse($twig->tokenize($template_code));

                $this->addFlash('success', 'Twig шаблон валиден.');
            } catch (\Twig_Error_Syntax $e) {
                $this->addFlash('error', $e->getMessage());
            }

            $this->addFlash('error', '<b>@todo</b> пока не рабоатет сохранение');
            //return $this->redirectToRoute('cms_admin_appearance_template', ['name' => $name]);
        }

        return $this->render('CMSBundle:AdminAppearance:template_edit.html.twig', [
            'name'          => $name,
            'templates'     => $this->getTemplates(),
            'template_code' => $template_code,
        ]);
    }

    /**
     * @param Request $request
     * @param string $name
     * @return Response|RedirectResponse
     */
    public function styleEditAction(Request $request, $name)
    {
        $style_file_path = $this->container->getParameter('kernel.root_dir') . '/../web' . $this->get('cms.config')->get('cms', 'appearance_styles_path') . $name;

        if (!file_exists($style_file_path)) {
            return $this->redirectToRoute('cms_admin_appearance');
        }

        $style_code = file_get_contents($style_file_path);

        if ($request->isMethod('POST')) {
            $style_code = $request->request->get('style_code');

            $this->addFlash('error', '<b>@todo</b> пока не рабоатет сохранение');
            //return $this->redirectToRoute('cms_admin_appearance_template', ['name' => $name]);
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
        $finder->files()->sortByName()->name('*.html.twig')->in($this->container->getParameter('kernel.root_dir') . '/Resources/views');

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
        $finder->files()->sortByName()->name('*.css')->name('*.less')->in($this->container->getParameter('kernel.root_dir') . '/../web' . $this->get('cms.config')->get('cms', 'appearance_styles_path'));

        $styles = [];
        /** @var \Symfony\Component\Finder\SplFileInfo $file */
        foreach ($finder as $file) {
            $styles[] = $file->getFilename();
        }

        return $styles;
    }
}
