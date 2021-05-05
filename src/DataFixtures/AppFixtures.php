<?php

namespace App\DataFixtures;

use App\Entity\BlogPost;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $blogPost = new BlogPost();
        $blogPost->setTitle('A first post!');
        $blogPost->setPublished(new \DateTime());
        $blogPost->setContent('Post text!');
        $blogPost->setAuthor('Xavier Mod');
        $blogPost->setSlug('a-first-post');

        $manager->persist($blogPost);

        $blogPost = new BlogPost();
        $blogPost->setTitle('A first post!');
        $blogPost->setPublished(new \DateTime());
        $blogPost->setContent('Post text!');
        $blogPost->setAuthor('Xavier Mod');
        $blogPost->setSlug('a-second-post');

        $manager->persist($blogPost);

        $blogPost = new BlogPost();
        $blogPost->setTitle('A first post!');
        $blogPost->setPublished(new \DateTime());
        $blogPost->setContent('Post text!');
        $blogPost->setAuthor('Xavier Mod');
        $blogPost->setSlug('a-third-post');

        $manager->persist($blogPost);

        $manager->flush();
    }
}
