<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class RegisterController extends AbstractController
{
  /**
   * @Route("/register", name="app_register")
   */
  public function index(Request $request, UserPasswordEncoderInterface $passwordEncoder): Response
  {
    $user = new User();
    $form = $this->createForm(UserType::class, $user);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
      $user->setPassword($passwordEncoder->encodePassword($user, $form["password"]->getData()));

      $em = $this->getDoctrine()->getManager();
      $em->persist($user);
      $em->flush();

      $this->addFlash("success", User::REGISTER_SUCCESS);

      return $this->redirectToRoute("app_register");
    }

    return $this->render('register/index.html.twig', [
      'controller_name' => 'RegisterController',
      "form" => $form->createView()
    ]);
  }
}
