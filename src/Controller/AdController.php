<?php

namespace App\Controller;

use App\Entity\Ad;
use App\Entity\Image;
use App\Form\AnnonceType;
use App\Repository\AdRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

class AdController extends AbstractController
{
    /**
     * @Route("/ads", name="ads_index")
     */
    public function index(AdRepository $repo)
    {
        //$repo = $this->getDoctrine()->getRepository(Ad::class);
        //plus besoin car on a mis public function index(AdRepository $repo)   (injection de dépendance)

        $ads = $repo->findAll();

        return $this->render('ad/index.html.twig', [
            'ads' => $ads
        ]);
    }

    /**
     * Permet de créer une annonce
     *
     * @Route("/ads/new", name="ads_create")
     * @return Response
     */
    public function create(Request $request, ObjectManager $manager)
    {
        // request correspond à la requête http, y compris dans le POST
        $ad = new Ad;

        $form = $this->createForm(AnnonceType::class, $ad);

        $form->handleRequest($request);
        // formulaire va analyser la requête et retrouver tous les champs, va les relier à notre variable ad et les places dans l'entité ad

        if ($form->isSubmitted() && $form->isValid()) {

            foreach ($ad->getImages() as $image) {
                $image->setAd($ad);
                $manager->persist($image);
            }
            $manager->persist($ad);
            $manager->flush();

            $this->addFlash(
                'success', // correspons à la classe verte de bootstrap
                "L'annonce <strong>{$ad->getTitle()}</strong> a bien été créée."
            );


            return $this->redirectToRoute('ads_show', [
                'slug' => $ad->getSlug()
            ]);
        }


        return $this->render('ad/new.html.twig', [
            'form' => $form->createView()  // va passer le formulaire dans twig
        ]);

    }

    /**
     * permet d'éditer une annonce
     *
     * @Route("/ads/{slug}/edit", name="ads_edit")
     * @return Response
     *
     */
    public function edit(Ad $ad, Request $request, ObjectManager $manager)
    {
        $form = $this->createForm(AnnonceType::class, $ad);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            foreach ($ad->getImages() as $image) {
                $image->setAd($ad);
                $manager->persist($image);
            }
            $manager->persist($ad);
            $manager->flush();

            $this->addFlash(
                'success', // correspons à la classe verte de bootstrap
                "Les modification de l'annonce <strong>{$ad->getTitle()}</strong> ont bien été enregistrées."
            );


            return $this->redirectToRoute('ads_show', [
                'slug' => $ad->getSlug()
            ]);

        }

        return $this->render('ad/edit.html.twig', [
            'form' => $form->createView(),
            'ad' => $ad
        ]);
    }

    /**
     * permet d'afficher une seule annonce
     *
     * @Route("/ads/{slug}", name="ads_show")
     * @return Response
     *
     */
    public function show(Ad $ad)
    {
        //$ad = $repo->findOneBySlug($slug);
        // on a retiré la ligne ci-dessus et ajouté Ad $ad dans les params (paramconverter) (et retiré le $slug)
        // avec findBy, on peut mettre le nom de n'importe quel champ de l'entité
        // findBy renvoie un tableau. findOneBy en renvoie un seul.

        return $this->render('ad/show.html.twig', [
            'ad' => $ad
        ]);
    }


}
