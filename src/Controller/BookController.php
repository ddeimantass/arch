<?php

namespace App\Controller;

use App\Entity\Book;
use App\Handler\BookHandler;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/books")
 */
class BookController extends AbstractController
{
    /** @var BookHandler */
    private $bookHandler;
    
    /**
     * @param BookHandler $bookHandler
     */
    public function __construct(BookHandler $bookHandler)
    {
        $this->bookHandler = $bookHandler;
    }
    
    /**
     * @Route("/", name="book_index", methods="GET")
     * @return Response
     */
    public function index(): Response
    {
        return $this->bookHandler->getList();
    }
    
    /**
     * @Route("/", name="book_new", methods="POST")
     * @param Request $request
     * @return Response
     */
    public function new(Request $request): Response
    {
        return $this->bookHandler->create($request);
    }
    
    /**
     * @Route("/{id}", name="book_show", methods="GET")
     * @param Book $book
     * @return Response
     */
    public function show(Book $book): Response
    {
        return $this->bookHandler->show($book);
    }
    
    /**
     * @Route("/{id}", name="book_edit", methods="PUT")
     * @param Request $request
     * @param Book $book
     * @return Response
     */
    public function edit(Request $request, Book $book): Response
    {
        return $this->bookHandler->update($request, $book);
    }
    
    /**
     * @Route("/{id}", name="book_delete", methods="DELETE")
     * @param Book $book
     * @return Response
     */
    public function delete(Book $book): Response
    {
        return $this->bookHandler->delete($book);
    }
}
