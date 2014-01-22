<?php

namespace SmartCore\Module\UserAccount\Controller;

use SmartCore\Bundle\CMSBundle\Module\NodeTrait;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class UserAccountController extends Controller
{
    use NodeTrait;

    public function indexAction()
    {
        if ($this->get('security.context')->isGranted('IS_AUTHENTICATED_FULLY') or $this->get('security.context')->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
            return $this->forward('FOSUserBundle:Profile:show');
        }

        return $this->forward('FOSUserBundle:Security:login', ['node_id' => $this->node->getId()]);
    }

    public function postAction($slug)
    {
        return $this->forward('CMSBundle:Engine:run', ['slug' => $slug]);
    }

    public function editAction()
    {
        $this->get('cms.breadcrumbs')->add('edit', 'Редактирование');

        if ($this->get('security.context')->isGranted('IS_AUTHENTICATED_FULLY') or $this->get('security.context')->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
            return $this->forward('FOSUserBundle:Profile:edit');
        }

        return $this->forward('FOSUserBundle:Security:login', ['node_id' => $this->node->getId()]);
    }
    
    public function changePasswordAction()
    {
        $this->get('cms.breadcrumbs')->add('change-password', 'Смена пароля');

        return $this->forward('FOSUserBundle:ChangePassword:changePassword');
    }
    
    public function resettingRequestAction()
    {
        $this->get('cms.breadcrumbs')->add('resetting', 'Восстановление пароля');

        return $this->forward('FOSUserBundle:Resetting:request');
    }
}
