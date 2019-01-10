<?php

/*
 * This file is part of eReolen widget.
 *
 * (c) 2018 ITK Development
 *
 * This source file is subject to the MIT license.
 */

namespace App\Controller;

use AlterPHP\EasyAdminExtensionBundle\Controller\EasyAdminController as BaseAdminController;
use App\Entity\User;
use FOS\UserBundle\Model\UserManagerInterface;

class AdminController extends BaseAdminController
{
    /** @var \FOS\UserBundle\Model\UserManagerInterface */
    private $userManager;

    public function __construct(UserManagerInterface $userManager)
    {
        $this->userManager = $userManager;
    }

    public function createNewUserEntity()
    {
        return $this->userManager->createUser();
    }

    public function persistUserEntity(User $user)
    {
        // @TODO: Move this to custom UserManager.
        $user->setPlainPassword(uniqid('', true));
        $user->setUsername($user->getEmail());
        $this->userManager->updateUser($user, false);
        parent::persistEntity($user);
    }

    public function updateUserEntity(User $user)
    {
        // @TODO: Move this to custom UserManager.
        $user->setUsername($user->getEmail());
        $this->userManager->updateUser($user, false);
        parent::updateEntity($user);
    }
}
