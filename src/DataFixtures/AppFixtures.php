<?php

namespace App\DataFixtures;

use App\Entity\Image;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use App\Entity\Ad;
use Faker\Factory;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        // composer require fzaninotto/faker
        $faker = Factory::create('FR-fr');


        for($i = 1; $i < 30; $i++) {
            //on va créer 30 annonces
            $ad = new Ad();

            $title = $faker->sentence();
            // lorem au hasard


            $coverImage = $faker->imageUrl(1000, 350);

            $introduction = $faker->paragraph(2);

            $content = '<p>' . join('</p><p>', $faker->paragraphs(5)) . '</p>';

            $ad->setTitle($title)
                ->setCoverImage($coverImage)
                ->setIntroduction($introduction)
                ->setContent($content)
                ->setPrice(mt_rand(40, 200))
                ->setRooms(mt_rand(1, 5));

            for($j=1; $j <= mt_rand(2, 5); $j++) {
                $image = new Image();

                $image->setUrl($faker->imageUrl())
                    ->setCaption($faker->sentence())
                    ->setAd($ad);

                $manager->persist($image);
            }

            $manager->persist($ad);
        }
    // on ne met pas le flush dans la boucle
        $manager->flush();
    }
}
