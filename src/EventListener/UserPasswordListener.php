<?php

namespace App\EventListener;

use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Event\BeforeEntityPersistedEvent;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

#[AsEventListener]
readonly class UserPasswordListener
{
    public function __construct(
        private UserPasswordHasherInterface $passwordHasher
    ) {}

    public function __invoke(BeforeEntityPersistedEvent $event): void
    {
        $entity = $event->getEntityInstance();

        if (!$entity instanceof User) {
            return;
        }

        $randomPassword = bin2hex(random_bytes(5));
        $entity->setPassword($this->passwordHasher->hashPassword($entity, $randomPassword));

    }

}