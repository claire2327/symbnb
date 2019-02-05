<?php

namespace App\Controller;

use App\Entity\Ad;
use App\Form\AnnonceType;
use App\Repository\AdRepository;
use App\Service\PaginationService;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminAdController extends AbstractController
{
    /**
     * @Route("/admin/ads/{page<\d+>?1}", name="admin_ads_index")
     */
    public function index(PaginationService $pagination, $page)
    {
        // page 1 par défaut. On ajoute des requirements pour éviter qu'un petit malin mette autre chose qu'un numéro dans la barre d'adresse pour le numéro de page. \d+ signifie digit mais pas forcément un seul.
        // on peut aussi mettre les requirements directement dans la route comme ceci :
        // {page<\d+>?1} Le ? précise que le nb est optionnel. Attention, bien indiquer la valeur par défaut après le ? car dans ce cas, il ne la prend plus en compte dans la fonction
        // $ads = $repo->findBy([], [], 5, 0);
        // va aller chercher les 5 premières annonces, sans filtre ou classement particulier

        $pagination->setEntityClass(Ad::class)
                    //->setRoute('admin_ads_index') Plus besoin car on va chercher la route dans le service avec RequestStack
                    ->setPage($page);


        return $this->render('admin/ad/index.html.twig', [
            'pagination' => $pagination
            ]);
        // on renvoie ads qui correspond à ce que va renvoyer le repo quand on lui demande findAll
    }

    /**
     *Permet d'afficher le formulaire d'édition
     *
     * @Route("/admin/ads/{id}/edit", name="admin_ads_edit")
     *
     * @param Ad $ad
     * @return Response
     */
    public function edit(Ad $ad, Request $request, ObjectManager $manager)
    {
        $form = $this->createForm(AnnonceType::class, $ad);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $manager->persist($ad);
            $manager->flush();

            $this->addFlash(
                'success',
                "L'annonce a bien été enregistrée."
            );
        }

        return $this->render('admin/ad/edit.html.twig', [
            'ad' => $ad,
            'form' => $form->createView()
        ]);

    }


    /**
     * Permet de supprimer une annonce
     *
     * @Route("admin/ads/{id}/delete", name="admin_ads_delete")
     *
     * @param Ad $ad
     * @param ObjectManager $manager
     * @return Response
     */
    public function delete(Ad $ad, ObjectManager $manager)
    {

        if (count($ad->getBookings()) > 0) {
            $this->addFlash(
                'warning',
                "Vous ne pouvez pas supprimer l'annonce {$ad->getTitle()} car elle possède déjà des réservations."
            );

        } else {
            $manager->remove($ad);
            $manager->flush();

            $this->addFlash(
                'success',
                "L'annonce {$ad->getTitle()} a bien été supprimée."
            );
        }
        return $this->redirectToRoute('admin_ads_index');
    }
}
