<?php

namespace App\Controller;

use App\Entity\Post;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractController
{
  /**
   * @Route("/", name="app_dashboard")
   */
  public function index(PaginatorInterface $paginator, Request $request): Response
  {

    $user = $this->getUser();

    if ($user) {
      $em = $this->getDoctrine()->getManager();
      $postsQuery = $em->getRepository(Post::class)->findAllQuery();
      $posts = $postsQuery->getResult();

      $pagination = $paginator->paginate(
        $postsQuery, /* query NOT result */
        $request->query->getInt('page', 1), /*page number*/
        2 /*limit per page*/
      );

      return $this->render('dashboard/index.html.twig', [
        'pagination' => $pagination,
        'posts' => $posts
      ]);
    } else {
      return $this->redirectToRoute("app_login");
    }
  }
}
