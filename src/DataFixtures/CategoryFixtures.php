<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class CategoryFixtures extends Fixture
{

    public function __construct(private readonly UserPasswordHasherInterface $userPasswordHasher)
    {
    }
    public function load(ObjectManager $manager): void
    {
        $categoryes = [
            "Formes et symboles",
            "Objets du quotidien",
            "Corps humain et vêtements",
            "Nature & transport",
        ];



foreach ($categoryes as $category) {
            $categoryEntity = new Category();
            $categoryEntity->setName($category);
            $manager->persist($categoryEntity);
}

        $user = new User();
        $user->setEmail("admin@gmail.com");
        $user->setRoles(["ROLE_ADMIN"]);
        $user->setFirstName("admin");
        $user->setLastName("admin");
        $user->setPassword($this->userPasswordHasher->hashPassword($user, "admin"));
        $manager->persist($user);

        $user2 = new User();
        $user2->setEmail("user@gmail.com");
        $user2->setRoles(["ROLE_USER"]);
        $user2->setFirstName("user");
        $user2->setLastName("user");
        $user2->setPassword($this->userPasswordHasher->hashPassword($user, "user"));
        $manager->persist($user2);
        $manager->flush();
    }
}