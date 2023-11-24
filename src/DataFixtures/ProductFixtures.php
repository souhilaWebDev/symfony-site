<?php

namespace App\DataFixtures;

use App\Entity\Product;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class ProductFixtures extends Fixture
{
    private const  NB_PRODUCT = 20;
    public function load(ObjectManager $manager): void
    {
        for ($i=1; $i < self::NB_PRODUCT ; $i++) { 
            $entity = new Product();
            $entity
            ->setDesignation("Product $i ")
            ->setPrice(mt_rand(1,999))
            ->setDescription("Description product $i")
            ->setQuantity(mt_rand(0,10))
            ->setImage("image.png");

            $manager->persist($entity);
        }
        // $product = new Product();
        // persist = insert
        $manager->flush();

    }
}
