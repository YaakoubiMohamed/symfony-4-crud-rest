<?php
namespace App\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use App\Entity\Book;
use App\Form\BookType;
/**
 * Book controller.
 * @Route("/api", name="api_")
 */
class BookController extends FOSRestController
{
  /**
   * Lists all Books.
   * @Rest\Get("/books")
   *
   * @return Response
   */
  public function getBookAction()
  {
    $repository = $this->getDoctrine()->getRepository(Book::class);
    $books = $repository->findall();
    return $this->handleView($this->view($books));
  }

  /**
  *@Rest\Delete("/book/{id}")
  */

  public function deleteAction($id){

    $book = new Book;
    $em = $this->getDoctrine()->getManager();
    $data = $this->getDoctrine()->getRepository("App:Book")->find($id);
    //dd($data);
    $em->remove($data);
    $em->flush();

    $repository = $this->getDoctrine()->getRepository(Book::class);
    $books = $repository->findall();
    return $this->handleView($this->view($books));
  }

  /**
  *@Rest\Get("/book/{id}")
  */

  public function viewAction($id){

    $book = new Book;
    $em = $this->getDoctrine()->getManager();
    $books = $this->getDoctrine()->getRepository("App:Book")->find($id);
    
    return $this->handleView($this->view($books));
  }

  
  /**
   * Create Book.
   * @Rest\Post("/books")
   *
   * @return Response
   */
  
  public function postBookAction(Request $request)
  {
    $book = new Book();
    $form = $this->createForm(BookType::class, $book);
    $data = json_decode($request->getContent(), true);
    $form->submit($data);
    if ($form->isSubmitted() && $form->isValid()) {
      $em = $this->getDoctrine()->getManager();
      $em->persist($book);
      $em->flush();
      return $this->handleView($this->view(['status' => 'ok'], Response::HTTP_CREATED));
    }
    return $this->handleView($this->view($form->getErrors()));
  }

  /**
  *@Rest\Put("/book/{id}")
  */
  public function updateAction($id, Request $request){

    $data = new Book;
    $titre = $request->get("titre");
    $theme = $request->get("theme");
    $prix = $request->get("prix");
    $em = $this->getDoctrine()->getManager();
    $book = $this->getDoctrine()->getRepository("App:Book")->find($id);

    $book->setTitre($titre);
    $book->setTheme($theme);
    $book->setPrix($prix);

    $em->flush();

    return new View("Updated!!", Response:: HTTP_OK);
  }
  
}