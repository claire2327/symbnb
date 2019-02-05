<?php

namespace App\Controller;

use App\Entity\Booking;
use App\Form\AdminBookingType;
use App\Repository\BookingRepository;
use App\Service\PaginationService;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminBookingController extends AbstractController
{
    /**
     * @Route("/admin/bookings/{page<\d+>?1}", name="admin_bookings_index")     *
     */
    public function index($page, PaginationService $pagination)
    {
        $pagination->setEntityClass(Booking::class)
                    //->setRoute('admin_bookings_index')
                 ->setPage($page);

        return $this->render('admin/booking/index.html.twig', [
            'pagination' =>   $pagination
        ]);
    }

    /**
     *
     * @Route("/admin/bookings/{id}/edit", name="admin_bookings_edit")
     * @return Response
     */
    public function edit(Booking $booking, Request $request, ObjectManager $manager) {

        $form = $this->createForm(AdminBookingType::class, $booking, [
            'validation_groups' => ["Default"]
            // optionnel puisque le groupe par défaut est appelé par défaut... Si c'était 'front', on le mettrait ici
        ]);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {

            $booking->setAmount(0);
            // 0 est considéré comme "empty" et donc va forcer le recalcul lors du prepersist de l'entité
            $manager->persist($booking);
            // en théorie, pas besoin de faire le persst car booking existe déjà, mais va lancer le "prepersist" de l'entité du coup
            $manager->flush();

            $this->addFlash(
                'success',
                "La réservation n°{$booking->getId()} a bien été modifiée."
            );
            return $this->redirectToRoute("admin_bookings_index");
        }

        return $this->render('admin/booking/edit.html.twig', [
            'form' => $form->createView(),
            'booking' => $booking

        ]);
    }

    /**
     * @param Booking $booking
     * @param ObjectManager $manager
     *
     * @Route("/admin/bookings/{id}/delete", name="admin_bookings_delete")
     *
     * @return Response
     */
    public function delete(Booking $booking, ObjectManager $manager) {
        $manager->remove($booking);
        $manager->flush();
        $this->addFlash(
            'success',
            "La réservation a bien été supprimée."
        );

        return $this->redirectToRoute('admin_bookings_index');

    }
}
