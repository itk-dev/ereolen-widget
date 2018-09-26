<?php

/*
 * This file is part of eReolen widget.
 *
 * (c) 2018 ITK Development
 *
 * This source file is subject to the MIT license.
 */

namespace App\Controller\Admin;

use AlterPHP\EasyAdminExtensionBundle\Controller\AdminController;
use App\Entity\User;
use FOS\UserBundle\Model\UserManagerInterface;

class UserController extends AdminController
{
    /** @var \FOS\UserBundle\Model\UserManagerInterface */
    private $userManager;

    public function __construct(UserManagerInterface $userManager)
    {
        $this->userManager = $userManager;
    }

    public function createNewEntity()
    {
        return $this->userManager->createUser();
    }

    public function persistEntity($user)
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
