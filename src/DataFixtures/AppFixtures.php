<?php

namespace App\DataFixtures;

use App\Entity\Booking;
use App\Entity\Image;
use App\Entity\Role;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use App\Entity\Ad;
use Faker\Factory;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{
    private $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    public function load(ObjectManager $manager)
    {
        // composer require fzaninotto/faker
        $faker = Factory::create('FR-fr');

        // on crée le rôle d'administrateur
        $adminRole = New Role();
        $adminRole->setTitle('ROLE_ADMIN');
        $manager->persist($adminRole);

        //on crée un utilisateur avec le role admin
        $adminUser = new User();
        $adminUser->setFirstName('Claire')
                ->setLastName('Mercier')
                ->setEmail('claire.mercier@gmail.com')
                ->setHash($this->encoder->encodePassword($adminUser, 'password'))
                ->setPicture('http://www.clairezanuso.com/uploads/4/9/3/2/49320807/claire-zanuso-023_orig.jpg')
                ->setIntroduction($faker->sentence)
                ->setDescription('<p>' . join('</p><p>', $faker->paragraphs(3)) . '</p>')
                ->addUserRole($adminRole);
        $manager->persist($adminUser);

        // Nous gérons les utilisateurs.
        $users = [];
        $genres = ['male', 'female'];

        $genre = $faker->randomElement($genres);

        for($i = 1; $i < 10; $i++) {
            $user = new User();

            $picture = 'https://randomuser.me/api/portraits/';
            $pictureId = $faker->numberBetween(1,99) . '.jpg';

            $picture .= ($genre == 'male'? 'men/' : 'women/') . $pictureId;

            $hash = $this->encoder->encodePassword($user, 'password');

            $user->setFirstName($faker->firstName($genre))
                ->setLastName($faker->lastName)
                ->setEmail($faker->email)
                ->setIntroduction($faker->sentence())
                ->setDescription('<p>' . join('</p><p>', $faker->paragraphs(3)) . '</p>')
                ->setHash($hash)
                ->setPicture($picture);

            $manager->persist($user);
            $users[] = $user;
        }
        // Nous gérons les annonces.
        for($i = 1; $i < 30; $i++) {
            //on va créer 30 annonces
            $ad = new Ad();

            $title = $faker->sentence();
            // lorem au hasard


            $coverImage = $faker->imageUrl(1000, 350);

            $introduction = $faker->paragraph(2);

            $content = '<p>' . join('</p><p>', $faker->paragraphs(5)) . '</p>';
            $user = $users[mt_rand(0, count($users) - 1)];

            $ad->setTitle($title)
                ->setCoverImage($coverImage)
                ->setIntroduction($introduction)
                ->setContent($content)
                ->setPrice(mt_rand(40, 200))
                ->setRooms(mt_rand(1, 5))
                ->setAuthor($user);

            for($j=1; $j <= mt_rand(2, 5); $j++) {
                $image = new Image();

                $image->setUrl($faker->imageUrl())
                    ->setCaption($faker->sentence())
                    ->setAd($ad);

                $manager->persist($image);
            }

            // gestion des réservations
            for($j = 1; $j <= mt_rand(0, 10); $j++) {
                $booking = new Booking();
                $createdAt = $faker->dateTimeBetween('-6months');
                // au plus tôt, il y a 6 mois
                $startDate = $faker->dateTimeBetween('-3 months');

                //gestion de la date de fin
                $duration = mt_rand(3,10);

                $endDate = (clone $startDate)->modify("+$duration days");
                // on clone la startdate pour pouvoir l'utiliser sans la modifier

                $amount = $ad->getPrice() * $duration;

                $booker = $users[mt_rand(0, count($users) - 1)];

                $comment = $faker->paragraph();

                $booking->setBooker($booker)
                        ->setAd($ad)
                        ->setStartDate($startDate)
                        ->setEndDate($endDate)
                        ->setCreatedAt($createdAt)
                        ->setAmount($amount)
                        ->setComment($comment);

                $manager->persist($booking);

            }

            $manager->persist($ad);
        }
    // on ne met pas le flush dans la boucle
        $manager->flush();
    }
}
