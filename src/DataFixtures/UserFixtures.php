<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class UserFixtures extends Fixture
{

    public function load(ObjectManager $manager)
    {
        for ($i = 1; $i <= 20; $i++) {
            $user = new User();
            $user->setEmail("user$i@example.com");
            $user->setUsername("username$i");
            $user->setPassword('$argon2id$v=19$m=65536,t=4,p=1$dUdVR3lPTFZxNHNTL3hDeA$YT9+E6h1YoYckaoMu8b30FVEH4s86P/Q4zNXcNolKwg');
            $manager->persist($user);
        }
        $admin = new User();
        $admin->setEmail('admin@example.com');
        $admin->setUsername('admin');
        $admin->setPassword('$argon2id$v=19$m=65536,t=4,p=1$dUdVR3lPTFZxNHNTL3hDeA$YT9+E6h1YoYckaoMu8b30FVEH4s86P/Q4zNXcNolKwg');
        $admin->setRoles(['ROLE_ADMIN']);
        $manager->persist($admin);

        $manager->flush();
    }
}
