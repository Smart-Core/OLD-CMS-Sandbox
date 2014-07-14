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
        if ($request->isMethod('POST')) {
            $this->addFlash('error', '<b>@todo</b> пока не рабоатет сохранение');
            $this->redirectToRoute('cms_admin_appearance_template', ['name' => $name]);
        }

        return $this->render('CMSBundle:AdminAppearance:template_edit.html.twig', [
            'name'          => $name,
            'templates'     => $this->getTemplates(),
            'template_code' => file_get_contents($this->container->getParameter('kernel.root_dir') . '/Resources/views/' . $name . '.html.twig'),
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
}
