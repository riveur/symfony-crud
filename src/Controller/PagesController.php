<?php

namespace App\Controller;

use App\Repository\ChallengeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @method \App\Entity\User getUser()
 */
#[IsGranted('ROLE_USER')]
class PagesController extends AbstractController
{
    #[Route('/', name: 'dashboard')]
    public function dashboard(ChallengeRepository $cRepo): Response
    {

        $activeChallenges = $cRepo->findAllActiveByUser($this->getUser());
        $completedChallenges = $cRepo->findAllCompletedByUser($this->getUser());
        $inactiveChallenges = $cRepo->findAllInactive($this->getUser());

        return $this->render('pages/dashboard.html.twig', compact('activeChallenges', 'completedChallenges', 'inactiveChallenges'));
    }
}
