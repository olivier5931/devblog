<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Knp\Component\Pager\PaginatorInterface;
use App\Entity\Poll;
use App\Repository\PollRepository;
use App\Form\PollType;
use App\Repository\OpinionRepository;
use App\Entity\Statistic;
use App\Repository\StatisticRepository;
use App\Form\VoteType;
use Symfony\Component\Security\Core\Security;

class PollController extends AbstractController
{
  /**
   * @Route("/poll", name="poll")
   */
  public function listPoll(PollRepository $repo, PaginatorInterface $paginator, Request $request,
													 Security $security)
  {
    $user = $security->getUser();

    $polls = $repo->findAllPolls();

    $pagination = $paginator->paginate(
      $polls, $request->query->getInt('page', 1), 10
    );

    return $this->render('poll/poll.html.twig', array(
      'user' => $user,
      'polls' => $polls,
      'pagination' => $pagination
    ));
  }

  /**
   * Display and process a form to vote on a poll
   * @Route("/poll/{id}/vote", name="poll_vote")
   */
  public function votePoll(PollRepository $repo, StatisticRepository $repo1, OpinionRepository $repo2,
													 Request $request, $id, Security $security)
  {
    $user = $security->getUser();
		$entityManager = $this->getDoctrine()->getManager();

    $this->denyAccessUnlessGranted('ROLE_GITHUB');

    $poll = $repo->find($id);
    $statistic = $repo1->findBy(['user' => $user, 'poll' => $poll]);

    if ($statistic) {
      $this->addFlash('warning', 'vous avez déjà voté pour ce sondage!');
      return $this->redirectToRoute('poll');
    }

    $opinionsChoices = array();
    foreach ($poll->getOpinions() as $opinion) {
      $opinionsChoices[$opinion->getName()] = $opinion->getName();
    }

    $statistic = new Statistic();

    $form = $this->createForm(VoteType::class, null, array('opinionsChoices' => $opinionsChoices));
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid())
    {
      $data = $form->getData();
      $opinion = $repo2->findOneBy(array('name' => $data));

      $opinion->setVotes($opinion->getVotes() + 1);

      $entityManager->persist($opinion);
      $statistic->setUser($user);
      $statistic->setPoll($poll);
      $statistic->setOpinion($opinion);

      $entityManager->persist($statistic);
      $entityManager->flush();

      // Show the results
      if ($user->getRoles() === ['ROLE_SUPER_ADMIN']) {
        $this->addFlash('success', 'admin, votre vote a été enregistré avec succès!');
      }
      else {
        $this->addFlash('success', 'votre vote a été enregistré avec succès!');
      }
      return $this->redirectToRoute('poll_results', array('id' => $id));
    }

    return $this->render('poll/vote.html.twig', array(
      'user' => $user,
      'poll' => $poll,
      'formPoll' => $form->createView()
    ));
  }

  /**
   * @Route("/poll/{id}/results", name="poll_results")
   */
  public function resultsPoll(PollRepository $repo, $id, Security $security)
  {
    $user = $security->getUser();

    $this->denyAccessUnlessGranted('ROLE_GITHUB');

    $poll = $repo->find($id);

    return $this->render('poll/results.html.twig', array(
      'user' => $user,
      'poll' => $poll
    ));
  }

  // Admin

  /**
   * Edit or add a new poll
   * @Route("/poll/new", name="poll_create")
   * @Route("/poll/{id}/edit", name="poll_edit")
   */
  public function form(Request $request, Security $security, Poll $poll = null)
  {
    $user = $security->getUser();
		$entityManager = $this->getDoctrine()->getManager();

    $this->denyAccessUnlessGranted('ROLE_ADMIN');

    if (!$poll) { $poll = new Poll(); }

    $form = $this->createForm(PollType::class, $poll);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid())
    {
      $poll->setCreatedAt(new \DateTime('now'));
      $entityManager->persist($poll);
      $entityManager->flush();

      $this->addFlash('success', 'admin, le sondage a été enregistré avec succès !');
      return $this->redirectToRoute('poll');
    }

    return $this->render('poll/create.html.twig', [
      'user' => $user,
      'formPoll' => $form->createView()
    ]);
  }
}
