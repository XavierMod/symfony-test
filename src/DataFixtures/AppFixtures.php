<?php

namespace App\DataFixtures;

use App\Entity\BlogPost;
use App\Entity\Comment;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Faker\Factory;

class AppFixtures extends Fixture
{
    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
        $this->faker = Factory::create();
    }


    public function load(ObjectManager $manager)
    {
        $this->loadUsers($manager);
        $this->loadBlogPosts($manager);
        $this->loadComments($manager);
    }

    public function loadBlogPosts(ObjectManager $manager)
    {

        for ($i = 0; $i < 100; $i++) {
            $user = $this->getReference('user_admin');

            $blogPost = new BlogPost();
            $blogPost->setTitle($this->faker->realText(30));
            $blogPost->setPublished($this->faker->dateTimeThisYear());
            $blogPost->setContent($this->faker->realText(100));
            $blogPost->setAuthor($user);
            $blogPost->setSlug($this->faker->slug());

            $this->setReference("blog_post_$i", $blogPost);
    
            $manager->persist($blogPost);
        }

        $manager->flush();
    }

    public function loadComments(ObjectManager $manager)
    {
        for ($i = 0; $i < 100; $i++) {
            for ($j = 0; $j < rand(1, 10); $j++) {
                $comment = new Comment();
                $comment->setContent($this->faker->realText());
                $comment->setAuthor($this->getReference('user_admin'));
                $comment->setPublished($this->faker->dateTimeThisYear());
                $comment->setBlogPost($this->getReference("blog_post_$i"));
                
                $manager->persist($comment);
            }
        }

        $manager->flush();
    }

    public function loadUsers(ObjectManager $manager)
    {
        $user = new User();
        $user->setUserName('admin');
        $user->setEmail($this->faker->email());
        $user->setName($this->faker->name());
        $user->setUserName('admin');
        $user->setPassword($this->passwordEncoder->encodePassword(
            $user,
            'secret123!'
        ));

        $this->addReference('user_admin', $user);

        $manager->persist($user);
        $manager->flush();
    }
}
