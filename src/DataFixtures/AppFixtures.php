<?php

namespace App\DataFixtures;

use App\Entity\Articles;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // $product = new Product();
        // $manager->persist($product);

        $this->loadArticles($manager);
        $manager->flush();
    }

    private function loadArticles(ObjectManager $manager): void
    {
        $createdAt = \DateTimeImmutable::createFromFormat('Y-m-d\TH:i:s.v\Z', '2016-02-18T03:22:56.637Z');
        $updatedAt = \DateTimeImmutable::createFromFormat('Y-m-d\TH:i:s.v\Z', '2016-02-18T03:48:35.824Z');

        $article = new Articles();
        $article->setSlug('how-to-train-your-dragonsss');
        $article->setTitle('How to train your dragon');
        $article->setDescription('Ever wonder how?');
        $article->setBody('It takes a Jacobian');
        $article->setTags(['dragons', 'training']);
        $article->setFavoritesCount(0);
        $article->setAuthorID('jake@jake.jake');
        $article->setCreatedAt($createdAt);
        $article->setUpdatedAt($updatedAt);
        $manager->persist($article);

//        unset($article); // clear/reset variable
//        $article = new Articles();
//        $article->setAuthorID();
//        $this->add($article);
    }
}
