<?php

namespace App\Controller;

use App\Service\StatsService;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class AdminDashboardController extends AbstractController
{
    /**
     * @Route("/admin", name="admin_dashboard")
     */
    public function index(ObjectManager $manager, StatsService $statsService)
    {
        //$users      = $statsService->getUsersCount();
        //$ads        = $statsService->getAdsCount();
        //$bookings   = $statsService->getBookingsCount();
        //$comments   = $statsService->getCommentsCount();

        $stats      = $statsService->getStats();
        $bestAds    = $statsService->getAdsStats('DESC');
        $worstAds   = $statsService->getAdsStats('ASC');

        return $this->render('admin/dashboard/index.html.twig', [
            'stats'     => $stats,
            'bestAds'   => $bestAds,
            'worstAds'  => $worstAds
        ]);
        // compact() est une fonction php qui permet de renvoyer un tableau où la clé est la même que la variable
    }
}
