<?php

namespace App\Controller;

use App\Entity\Post;
use App\Form\PostType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

class PostController extends AbstractController
{
  /**
   * @Route("/post", name="app_post")
   */
  public function index(Request $request): Response
  {
    $post = new Post();
    $form = $this->createForm(PostType::class, $post);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
      /** @var UploadedFile $file */
      $file = $form->get('photo')->getData();

      // this condition is needed because the 'brochure' field is not required
      // so the PDF file must be processed only when a file is uploaded
      if ($file) {
        $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        // this is needed to safely include the file name as part of the URL
        // $safeFilename = $slugger->slug($originalFilename);
        $safeFilename = transliterator_transliterate("Any-Latin; Latin-ASCII; [^A-Za-z0-9_] remove; Lower()", $originalFilename);
        $newFilename = $safeFilename . '-' . uniqid() . '.' . $file->guessExtension();

        // Move the file to the directory where brochures are stored
        try {
          $file->move(
            $this->getParameter("photos_dir"),
            $newFilename
          );
        } catch (FileException $e) {
          // ... handle exception if something happens during file upload
          throw new FileException("ERROR uploading file");
        }

        // updates the 'brochureFilename' property to store the PDF file name
        // instead of its contents
        $post->setPhoto($newFilename);
      }


      $user = $this->getUser();
      $post->setUser($user);

      $em = $this->getDoctrine()->getManager();
      $em->persist($post);
      $em->flush();

      return $this->redirectToRoute("app_dashboard");
    }

    return $this->render('post/index.html.twig', [
      'form' => $form->createView()
    ]);
  }

  /**
   * @Route("/post/{id}", name="single_post")
   */
  public function seePost($id)
  {
    $em = $this->getDoctrine()->getManager();
    $post = $em->getRepository(Post::class)->find($id);

    return $this->render('post/single_post.html.twig', [
      'post' => $post
    ]);
  }

  /**
   * @Route("/post/own/posts", name="own_posts")
   */
  public function ownPosts()
  {
    $user = $this->getUser();

    $em = $this->getDoctrine()->getManager();
    $posts = $em->getRepository(Post::class)->findBy(["user" => $user]);

    return $this->render('post/own_post.html.twig', [
      'posts' => $posts
    ]);
  }
}
