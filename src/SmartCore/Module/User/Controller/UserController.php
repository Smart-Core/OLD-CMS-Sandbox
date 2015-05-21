<?php

namespace SmartCore\Module\User\Controller;

use SmartCore\Bundle\CMSBundle\Module\NodeTrait;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;

class UserController extends Controller
{
    use NodeTrait;

    public function indexAction()
    {
        if ($this->get('security.context')->isGranted('IS_AUTHENTICATED_FULLY') or $this->get('security.context')->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
            return $this->forward('FOSUserBundle:Profile:show');
        }

        return $this->forward('CMSBundle:Security:login', [
            'data' => [
                'allow_password_resetting' => $this->node->getParam('allow_password_resetting'),
                'allow_registration'       => $this->node->getParam('allow_registration'),
            ],
            'node_id' => $this->node->getId(),
        ]);
    }

    public function postAction($slug)
    {
        return $this->forward('CMSBundle:Engine:run', ['slug' => $slug]);
    }

    public function editAction()
    {
        $this->get('cms.breadcrumbs')->add('edit', 'Редактирование');

        return $this->forward('FOSUserBundle:Profile:edit');
    }

    public function changePasswordAction()
    {
        $this->get('cms.breadcrumbs')->add('change-password', 'Смена пароля');

        return $this->forward('FOSUserBundle:ChangePassword:changePassword');
    }

    public function resettingRequestAction()
    {
        if (!$this->node->getParam('allow_password_resetting')) {
            return new RedirectResponse($this->generateUrl('fos_user_profile_show'));
        }

        $this->get('cms.breadcrumbs')->add('resetting', 'Восстановление пароля');

        return $this->forward('FOSUserBundle:Resetting:request');
    }

    public function registerAction()
    {
        if (!$this->node->getParam('allow_registration')) {
            return new RedirectResponse($this->generateUrl('fos_user_profile_show'));
        }

        $this->get('cms.breadcrumbs')->add('register', 'Регистрация');

        if ($this->get('security.context')->isGranted('IS_AUTHENTICATED_FULLY') or
            $this->get('security.context')->isGranted('IS_AUTHENTICATED_REMEMBERED')
        ) {
            return new RedirectResponse($this->generateUrl('fos_user_profile_show'));
        }

        return $this->forward('FOSUserBundle:Registration:register');
    }

    public function registerCheckEmailAction()
    {
        if ($this->get('session')->has('fos_user_send_confirmation_email/email')) {
            return $this->forward('FOSUserBundle:Registration:checkEmail');
        }

        return new RedirectResponse($this->generateUrl('fos_user_profile_show'));
    }
}
