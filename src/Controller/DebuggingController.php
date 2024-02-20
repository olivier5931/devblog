<?php

namespace App\Controller;

use App\Entity\Search;
use App\Form\SearchType;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\Security\Core\Security;
use App\Entity\Debugging;
use App\Repository\DebuggingRepository;
use App\Form\DebuggingType;
use App\Entity\Comment;
use App\Form\CommentType;

class DebuggingController extends AbstractController
{
  /**
   * @Route("/debogage", name="debugging")
	 * @Route("/debugging", name="debugging_eng")
   */
  public function index(DebuggingRepository $repo, Request $request, PaginatorInterface $paginator,
												Security $security)
  {
    $user = $security->getUser();

    $debuggings = $repo->findAllDebuggings();

    $pagination = $paginator->paginate(
            $debuggings, $request->query->getInt('page', 1), 5
    );

		$args = [
			'user' => $user,
			'debuggings' => $debuggings,
			'pagination' => $pagination
		];

		if ($user != null) {
			$language = $user->getLanguage();
			if ($language == 'eng') {
				return $this->render('debugging/debugging_eng.html.twig', $args);
			}
		}

    return $this->render('debugging/debugging.html.twig', $args);
  }

  /**
   * @Route("/debogage/programming", name="debugging_programming")
	 * @Route("/debugging/programming", name="debugging_programming_eng")
   */
  public function showDebugprog(DebuggingRepository $repo, Request $request, PaginatorInterface $paginator,
																Security $security)
  {
    $user = $security->getUser();

    $debuggings = $repo->findBy(['category' => 'programming'], ['createdAt' => 'desc']);

    $pagination = $paginator->paginate(
            $debuggings, $request->query->getInt('page', 1), 5
    );

		$args = [
			'user' => $user,
			'debuggings' => $debuggings,
			'pagination' => $pagination
		];

		if ($user != null) {
			$language = $user->getLanguage();
			if ($language == 'eng') {
				return $this->render('debugging/programming_eng.html.twig', $args);
			}
		}

    return $this->render('debugging/programming.html.twig', $args);
  }

  /**
   * @Route("/debogage/framework", name="debugging_framework")
	 * @Route("/debugging/framework", name="debugging_framework_eng")
   */
  public function showDebugfram(DebuggingRepository $repo, Request $request, PaginatorInterface $paginator,
																Security $security)
  {
    $user = $security->getUser();

    $debuggings = $repo->findBy(['category' => 'framework'], ['createdAt' => 'desc']);

    $pagination = $paginator->paginate(
            $debuggings, $request->query->getInt('page', 1), 5
    );

		$args = [
			'user' => $user,
			'debuggings' => $debuggings,
			'pagination' => $pagination
		];

		if ($user != null) {
			$language = $user->getLanguage();
			if ($language == 'eng') {
				return $this->render('debugging/framework_eng.html.twig', $args);
			}
		}

    return $this->render('debugging/framework.html.twig', $args);
  }

  /**
   * @Route("/debogage/new", name="debugging_create")
   * @Route("/debogage/edit/{id}", name="debugging_edit")
   */
  public function form(Request $request, Security $security, Debugging $debugging = null)
  {
    $user = $security->getUser();
		$entityManager = $this->getDoctrine()->getManager();

    $this->denyAccessUnlessGranted('ROLE_ADMIN');

    if (!$debugging) {
      $debugging = new Debugging();
    }
    $form = $this->createForm(DebuggingType::class, $debugging);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {

      if (!$debugging->getId()) {
        $debugging->setCreatedAt(new \DateTime('now'));
      }
      $entityManager->persist($debugging);
      $entityManager->flush();

      $this->addFlash('success', 'admin, votre erreur est postée');
      return $this->redirectToRoute('debugging_show', ['id' => $debugging->getId()]);
    }

    return $this->render('debugging/create.html.twig', [
                'user' => $user,
                'formDebugging' => $form->createView(),
                'editMode' => $debugging->getId() !== null
    ]);
  }

  /**
   * @Route("/debogage/vue/{id}", name="debugging_show")
	 * @Route("/debugging/show/{id}", name="debugging_show_eng")
   */
  public function show(Debugging $debugging, Request $request, Security $security)
  {
    $user = $security->getUser();
		$entityManager = $this->getDoctrine()->getManager();

		if ($user != null) {
			$language = $user->getLanguage();
			if ($language == 'fr') {

				$comment = new Comment();

				$form = $this->createForm(CommentType::class, $comment);
				$form->handleRequest($request);

				if ($form->isSubmitted() && $form->isValid()) {

					$comment->setUsername($user->getUsername())
						->setCreatedAt(new \DateTime('now'))
						->setAvatar($user->getAvatar())
						->setLanguage($user->getLanguage())
						->setDebugging($debugging);

					$entityManager->persist($comment);
					$entityManager->flush();

					$this->addFlash('success', 'votre message est envoyé');
					return $this->redirectToRoute('debugging_show', ['id' => $debugging->getId()]);
				}

				return $this->render('debugging/show.html.twig', [
					'user' => $user,
					'debugging' => $debugging,
					'formComment' => $form->createView()
				]);
			}

			if ($language == 'eng') {

				$comment = new Comment();

				$form = $this->createForm(CommentType::class, $comment);
				$form->handleRequest($request);

				if ($form->isSubmitted() && $form->isValid()) {

					$comment->setUsername($user->getUsername())
						->setCreatedAt(new \DateTime('now'))
						->setAvatar($user->getAvatar())
						->setLanguage($user->getLanguage())
						->setDebugging($debugging);

					$entityManager->persist($comment);
					$entityManager->flush();

					$this->addFlash('success', 'your message is sent');
					return $this->redirectToRoute('debugging_show_eng', ['id' => $debugging->getId()]);
				}
			}
		}
		else {
			return $this->redirectToRoute('connect_github');
		}

		return $this->render('debugging/show_eng.html.twig', [
			'user' => $user,
			'debugging' => $debugging,
			'formComment' => $form->createView()
		]);
	}

	/**
	 * @Route("/debogage/search", name="debugging_search")
	 * @Route("/debugging/search", name="debugging_eng_search")
	 */
	public function searchDebug(DebuggingRepository $repo, Request $request, Security $security)
	{
		$user = $security->getUser();

		$search = new Search();

		$form = $this->createForm(SearchType::class, $search);
		$form->handleRequest($request);

		$debuggings = $repo->findDebuggingsList($search);

		$args = [
			'user' => $user,
			'debuggings' => $debuggings,
			'formSearch' => $form->createView()
		];

		if ($user != null) {
			$language = $user->getLanguage();
			if ($language == 'fr') {

				return $this->render('debugging/search_eng.html.twig', $args);
			}
		}

		return $this->render('debugging/search.html.twig', $args);
	}
}
