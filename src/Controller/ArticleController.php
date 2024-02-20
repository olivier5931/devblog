<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\Security\Core\Security;
use App\Entity\Article;
use App\Repository\ArticleRepository;
use App\Form\ArticleType;
use App\Entity\Comment;
use App\Form\CommentType;

class ArticleController extends AbstractController
{
  /**
   * @Route("/article/debutant", name="article_novice")
	 * @Route("/article/novice", name="article_novice_eng")
   */
  public function showArtnov(ArticleRepository $repo, Request $request, PaginatorInterface $paginator,
														 Security $security)
  {
    $user = $security->getUser();

    $articles = $repo->findBy(['category' => 'novice'], ['createdAt' => 'desc']);

    $pagination = $paginator->paginate(
      $articles, $request->query->getInt('page', 1), 5
    );

		$args = [
			'user' => $user,
			'articles' => $articles,
			'pagination' => $pagination
		];

		if ($user != null) {
			$language = $user->getLanguage();
			if ($language == 'eng') {
				return $this->render('article/novice_eng.html.twig', $args);
			}
		}

    return $this->render('article/novice.html.twig', $args);
  }

  /**
   * @Route("/article/programmation", name="article_programming")
	 * @Route("/article/programming", name="article_programming_eng")
   */
  public function showArtprog(ArticleRepository $repo, Request $request, PaginatorInterface $paginator,
															Security $security)
  {
    $user = $security->getUser();

    $articles = $repo->findBy(['category' => 'programming'], ['createdAt' => 'desc']);

    $pagination = $paginator->paginate(
      $articles, $request->query->getInt('page', 1), 5
    );

		$args = [
			'user' => $user,
			'articles' => $articles,
			'pagination' => $pagination
		];

		if ($user != null) {
			$language = $user->getLanguage();
			if ($language == 'eng') {
				return $this->render('article/programming_eng.html.twig', $args);
			}
		}

    return $this->render('article/programming.html.twig', $args);
  }

  /**
   * @Route("/article/framework", name="article_framework")
	 * @Route("/article/frameworks", name="article_framework_eng")
   */
  public function showArtfram(ArticleRepository $repo1, Request $request, PaginatorInterface $paginator,
															Security $security)
  {
    $user = $security->getUser();

    $articles = $repo1->findBy(['category' => 'framework'], ['createdAt' => 'desc']);

    $pagination = $paginator->paginate(
      $articles, $request->query->getInt('page', 1), 5
    );

		$args = [
			'user' => $user,
			'articles' => $articles,
			'pagination' => $pagination
		];

		if ($user != null) {
			$language = $user->getLanguage();
			if ($language == 'eng') {
				return $this->render('article/framework_eng.html.twig', $args);
			}
		}

    return $this->render('article/framework.html.twig', $args);
  }

  /**
   * @Route("/article/mixed", name="article_mixed")
	 * @Route("/article/mixed2", name="article_mixed_eng")
   */
  public function showArtmixed(ArticleRepository $repo, Request $request, PaginatorInterface $paginator,
															 Security $security)
  {
    $user = $security->getUser();

    $articles = $repo->findBy(['category' => 'mixed'], ['createdAt' => 'desc']);

    $pagination = $paginator->paginate(
      $articles, $request->query->getInt('page', 1), 5
    );

		$args = [
			'user' => $user,
			'articles' => $articles,
			'pagination' => $pagination
		];

		if ($user != null) {
			$language = $user->getLanguage();
			if ($language == 'eng') {
				return $this->render('article/mixed_eng.html.twig', $args);
			}
		}

    return $this->render('article/mixed.html.twig', $args);
  }

  /**
   * @Route("/article/new", name="article_create")
   * @Route("/article/edit/{id}", name="article_edit")
   */
  public function form(Request $request, Security $security, Article $article = null)
  {
    $user = $security->getUser();
		$entityManager = $this->getDoctrine()->getManager();

    $this->denyAccessUnlessGranted('ROLE_ADMIN');

    if (!$article) {
      $article = new Article();
    }
    $form = $this->createForm(ArticleType::class, $article);

    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {

      if (!$article->getId()) {
        $article->setCreatedAt(new \DateTime('now'));
      }
      $entityManager->persist($article);
      $entityManager->flush();

      $this->addFlash('success', 'admin, votre article est validé');
      return $this->redirectToRoute('article_show', ['id' => $article->getId()]);
    }

    return $this->render('article/create.html.twig', [
      'user' => $user,
      'formArticle' => $form->createView(),
      'editMode' => $article->getId() !== null
    ]);
  }

  /**
   * @Route("/article/vue/{id}", name="article_show")
	 * @Route("/article/show/{id}", name="article_show_eng")
   */
  public function show(Article $article, Request $request, Security $security)
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
						->setArticle($article);

					$entityManager->persist($comment);
					$entityManager->flush();

					$this->addFlash('success', 'votre message est envoyé');
					return $this->redirectToRoute('article_show', ['id' => $article->getId()]);
				}

				return $this->render('article/show.html.twig', [
					'user' => $user,
					'article' => $article,
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
						->setArticle($article);

					$entityManager->persist($comment);
					$entityManager->flush();

					$this->addFlash('success', 'your message is sent');
					return $this->redirectToRoute('article_show_eng', ['id' => $article->getId()]);
				}
			}
		}
		else {
			return $this->redirectToRoute('connect_github');
		}

		return $this->render('article/show_eng.html.twig', [
			'user' => $user,
			'article' => $article,
			'formComment' => $form->createView()
		]);
	}
}
