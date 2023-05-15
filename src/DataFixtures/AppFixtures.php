<?php

namespace App\DataFixtures;

use App\Entity\Produit;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        for ($i=0;$i<20;$i++) {
            $produit = new Produit();
            $produit->setName('Jouet');
            $produit->setDescription("Un jouet pour jouer");
            $produit->setPrice('2');
            $manager->persist($produit);
        }
        $manager->flush();
    }
}
