<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\Security\Core\Security;
use App\Repository\ArticleRepository;
use App\Form\LanguageFrType;
use App\Form\LanguageEngType;
use App\Form\ContactType;

class DefaultController extends AbstractController
{
  /**
   * @Route("/accueil", name="homepage")
	 * @Route("/home", name="homepage_eng")
   */
  public function index(ArticleRepository $repo, Request $request, PaginatorInterface $paginator,
												Security $security)
  {
    $user = $security->getUser();

		$articles = $repo->findAllArticles();

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
				return $this->render('default/homepage_eng.html.twig', $args);
			}
		}

    return $this->render('default/homepage.html.twig', $args);
	}

  /**
   * @Route("/langue", name="language")
	 * @Route("/language", name="language_eng")
   */
  public function languageFr(Request $request, Security $security)
  {
    $user = $security->getUser();

    if (!$user) {
      return $this->redirectToRoute('connect_github');
    }

		$language = $user->getLanguage();
		$entityManager = $this->getDoctrine()->getManager();

		if ($language == 'fr') {

			$form = $this->createForm(LanguageEngType::class, $user);
			$form->handleRequest($request);

			if ($form->isSubmitted() && $form->isValid()) {

				$user->setLanguage('eng');
				$entityManager->persist($user);
				$entityManager->flush();

				$this->addFlash('success', 'your choice is saved');
				return $this->redirectToRoute('homepage_eng');
			}

			return $this->render('default/language.html.twig', [
				'user' => $user,
				'formLanguageFr' => $form->createView(),
				'formLanguageEng' => $form->createView()
			]);
		}

		if ($language == 'eng') {

			$form = $this->createForm(LanguageFrType::class, $user);
			$form->handleRequest($request);

			if ($form->isSubmitted() && $form->isValid()) {

				$user->setLanguage('fr');
				$entityManager->persist($user);
				$entityManager->flush();

				$this->addFlash('success', 'votre choix est enregistré');
				return $this->redirectToRoute('homepage');
			}
		}

		return $this->render('default/language_eng.html.twig', [
			'user' => $user,
			'formLanguageFr' => $form->createView(),
			'formLanguageEng' => $form->createView()
		]);
  }

  /**
   * @Route("/contact", name="contact")
   */
  public function contact(Request $request, Security $security, \Swift_Mailer $mailer)
  {
    $user = $security->getUser();

		if (!$user) {
			return $this->redirectToRoute('connect_github');
		}

    $form = $this->createForm(ContactType::class);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {

      $contactFormData = $form->getData();

      $message = (new \Swift_Message('Blog'))
        ->setFrom($contactFormData['email'])
        ->setTo('blog@blog.com')
        ->setBody(
          $contactFormData['body'], 'text/plain'
        );

      $mailer->send($message);

      $this->addFlash('success', 'message envoyé !');
      return $this->redirectToRoute('homepage');
    }

    return $this->render('default/contact.html.twig', [
      'user' => $user,
      'formContact' => $form->createView()
    ]);
  }

  /**
   * @Route("/error", name="error")
   */
  public function error(Request $request)
  {
    return $this->render('default/error.html.twig');
  }

  /**
   * @Route("/logout", name="app_logout")
   */
  public function logout()
  {
    return $this->redirectToRoute('homepage');
  }
}
