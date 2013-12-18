<?php

namespace SmartCore\Bundle\FOSUserBundle\Controller;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Core\Exception\AccountStatusException;
use FOS\UserBundle\Model\UserInterface;
use FOS\UserBundle\Controller\ResettingController as BaseResettingController;

/**
 * Controller managing the resetting of the password
 *
 * @author Thibault Duplessis <thibault.duplessis@gmail.com>
 * @author Christophe Coevoet <stof@notk.org>
 */
class ResettingController extends BaseResettingController
{
    /**
     * Reset user password
     */
    public function resetAction(Request $request, $token)
    {
        $user = $this->container->get('fos_user.user_manager')->findUserByConfirmationToken($token);

        if (null === $user) {
            //throw new NotFoundHttpException(sprintf('The user with "confirmation token" does not exist for value "%s"', $token));
            return new RedirectResponse($this->container->get('router')->generate('fos_user_profile_show'));
        }

        if (!$user->isPasswordRequestNonExpired($this->container->getParameter('fos_user.resetting.token_ttl'))) {
            return new RedirectResponse($this->container->get('router')->generate('fos_user_resetting_request'));
        }

        $form = $this->container->get('fos_user.resetting.form');

        if ($this->container->get('fos_user.resetting.form.handler')->process($user)) {
            $this->authenticateUser($user);

            $this->setFlash('fos_user_success', 'resetting.flash.success');

            return new RedirectResponse($this->getRedirectionUrl($user));
        }

        return $this->container->get('templating')->renderResponse('FOSUserBundle:Resetting:reset.html.'.$this->getEngine(), [
            'token' => $token,
            'form'  => $form->createView(),
            'theme' => $this->container->getParameter('fos_user.template.theme'),
        ]);
    }
}
