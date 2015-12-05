<?php
/**
 * Created by PhpStorm.
 * User: Matas
 * Date: 2015.12.05
 * Time: 08:59
 */

namespace NFQ\UserBundle\Controller;

use FOS\UserBundle\Controller\RegistrationController as BaseController;
use Symfony\Component\HttpFoundation\RedirectResponse;


class RegistrationController extends BaseController{

    public function confirmedAction()
    {
        $response = new RedirectResponse($this->container->get('router')->generate('fos_user_profile_edit'));
        return $response;
    }
}