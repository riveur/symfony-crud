<?php

namespace App\Controller;

use App\Entity\Challenge;
use App\Entity\UserChallenge;
use App\Form\ChallengeType;
use App\Repository\ChallengeRepository;
use DateTimeImmutable;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * 
 * @method \App\Entity\User getUser()
 */
#[IsGranted('IS_AUTHENTICATED_FULLY')]
#[Route('/challenge')]
class ChallengeController extends AbstractController
{
    #[Route('/', name: 'app_challenge_index', methods: ['GET'])]
    public function index(ChallengeRepository $challengeRepository): Response
    {
        return $this->render('challenge/index.html.twig', [
            'challenges' => $challengeRepository->findAllByAuthor($this->getUser()),
        ]);
    }

    #[Route('/new', name: 'app_challenge_new', methods: ['GET', 'POST'])]
    public function new(Request $request, ChallengeRepository $challengeRepository): Response
    {
        $challenge = new Challenge();
        $form = $this->createForm(ChallengeType::class, $challenge);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $challenge
                ->setAuthor($this->getUser());

            $challengeRepository->save($challenge, true);

            $this->addFlash('success', 'Your challenge has been created !');

            return $this->redirectToRoute('app_challenge_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('challenge/new.html.twig', [
            'challenge' => $challenge,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_challenge_show', methods: ['GET'])]
    public function show(Challenge $challenge): Response
    {
        return $this->render('challenge/show.html.twig', [
            'challenge' => $challenge,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_challenge_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Challenge $challenge, ChallengeRepository $challengeRepository): Response
    {
        $form = $this->createForm(ChallengeType::class, $challenge);
        $form->handleRequest($request);

        if (
            $form->isSubmitted() &&
            $form->isValid() &&
            $challenge->getAuthor()->getId() === $this->getUser()->getId()
        ) {
            $challenge->setUpdatedAt(new DateTimeImmutable());
            $challengeRepository->save($challenge, true);

            $this->addFlash('success', 'Your changes has been applied !');

            return $this->redirectToRoute('app_challenge_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('challenge/edit.html.twig', [
            'challenge' => $challenge,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_challenge_delete', methods: ['POST'])]
    public function delete(Request $request, Challenge $challenge, ChallengeRepository $challengeRepository): Response
    {
        if (
            $this->isCsrfTokenValid('delete' . $challenge->getId(), $request->request->get('_token')) &&
            $challenge->getAuthor()->getId() === $this->getUser()->getId()
        ) {
            $challengeRepository->remove($challenge, true);

            $this->addFlash('success', 'Challenge removed successfully !');
        } else {
            $this->addFlash('danger', 'You are not able to delete this.');
        }

        return $this->redirectToRoute('app_challenge_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/{id}/accept', name: 'app_challenge_accept', methods: ['POST'])]
    public function accept(Request $request, Challenge $challenge, ChallengeRepository $challengeRepository): Response
    {
        if ($this->isCsrfTokenValid('accept' . $challenge->getId(), $request->request->get('_token'))) {
            $userChallenge = (new UserChallenge())
                ->setChallenge($challenge)
                ->setUser($this->getUser())
                ->setCompleted(false);

            $challenge->addUserChallenge($userChallenge);

            $challengeRepository->save($challenge, true);

            $this->addFlash('success', 'You have accepted the challenge: ' . $challenge->getTitle());
        } else {
            $this->addFlash('danger', 'You can\'t accept this challenge.');
        }

        return $this->redirectToRoute('dashboard', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/{id}/give-up', name: 'app_challenge_give_up', methods: ['POST'])]
    public function giveUp(Request $request, ChallengeRepository $challengeRepository): Response
    {
        $challenge = $challengeRepository->findWithUsers($request->get('id'));

        if ($this->isCsrfTokenValid('give-up' . $challenge->getId(), $request->request->get('_token'))) {
            /** @var \App\Entity\UserChallenge $userChallenge */
            $userChallenge = $challenge
                ->getChallengeUsers()
                ->filter(fn (UserChallenge $userChallenge) => $userChallenge->getUser() === $this->getUser())
                ->first();

            $challenge->removeUserChallenge($userChallenge);

            $challengeRepository->save($challenge, true);

            $this->addFlash('success', 'You give up the challenge: ' . $challenge->getTitle());
        } else {
            $this->addFlash('danger', 'You can\'t give up this challenge.');
        }

        return $this->redirectToRoute('dashboard', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/{id}/complete', name: 'app_challenge_complete', methods: ['POST'])]
    public function complete(Request $request, ChallengeRepository $challengeRepository): Response
    {
        $challenge = $challengeRepository->findWithUsers($request->get('id'));

        if ($this->isCsrfTokenValid('complete' . $challenge->getId(), $request->request->get('_token'))) {
            /** @var \App\Entity\UserChallenge $userChallenge */
            $userChallenge = $challenge
                ->getChallengeUsers()
                ->filter(fn (UserChallenge $userChallenge) => $userChallenge->getUser() === $this->getUser())
                ->first();

            $userChallenge->setCompleted(true);

            $challengeRepository->save($challenge, true);

            $this->addFlash('success', 'You completed the challenge: ' . $challenge->getTitle());
        } else {
            $this->addFlash('danger', 'You can\'t complete this challenge.');
        }

        return $this->redirectToRoute('dashboard', [], Response::HTTP_SEE_OTHER);
    }
}
