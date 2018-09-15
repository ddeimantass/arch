<?php

namespace App\Handler;

use App\Entity\Book;
use App\Request\BookRequest;
use Doctrine\ORM\EntityManagerInterface;
use JMS\Serializer\SerializerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class BookHandler
{
    /**
     * @var SerializerInterface
     */
    private $serializer;
    
    /**
     * @var EntityManagerInterface
     */
    private $em;
    
    /**
     * @var ValidatorInterface
     */
    private $validator;
    
    /**
     * @param SerializerInterface $serializer
     * @param EntityManagerInterface $em
     * @param ValidatorInterface $validator
     */
    public function __construct(
        SerializerInterface $serializer,
        EntityManagerInterface $em,
        ValidatorInterface $validator
    )
    {
        $this->serializer = $serializer;
        $this->em = $em;
        $this->validator = $validator;
    }
    
    /**
     * @return Response
     */
    public function getList(): Response
    {
        
        $books = $this->em->getRepository(Book::class)->findAll();
        $data = $this->serializer->serialize($books,'json');
        
        return new Response($data, Response::HTTP_OK);
    }
    
    /**
     * @param Request $request
     * @return Response
     */
    public function create(Request $request): Response
    {
        $BookRequest = new BookRequest($request);
        $violations = $this->validator->validate($BookRequest);
    
        if (0 !== \count($violations)) {
            $errors = [];
        
            foreach ($violations as $violation) {
                $errors[$violation->getPropertyPath()] = [
                    'message' => $violation->getMessage(),
                    'invalid_value' => $violation->getInvalidValue(),
                ];
            }
            $data = $this->serializer->serialize($errors,'json');
            return new Response($data, Response::HTTP_BAD_REQUEST);
        }
        $book = $this->saveBook($BookRequest);
        
        return new Response($book, Response::HTTP_CREATED);
    }
    
    /**
     * @param Book $book
     * @return Response
     */
    public function show(Book $book): Response
    {
        $data = $this->serializer->serialize($book,'json');
        
        return new Response($data, Response::HTTP_OK);
    }
    
    /**
     * @param Request $request
     * @param Book $book
     * @return Response
     */
    public function update(Request $request, Book $book): Response
    {
        $BookRequest = new BookRequest($request);
        $violations = $this->validator->validate($BookRequest);
        
        if (0 !== \count($violations)) {
            $errors = [];
            foreach ($violations as $violation) {
                $errors[$violation->getPropertyPath()] = [
                    'message' => $violation->getMessage(),
                    'invalid_value' => $violation->getInvalidValue(),
                ];
            }
            $data = $this->serializer->serialize($errors,'json');
            
            return new Response($data, Response::HTTP_BAD_REQUEST);
        }
    
        $this->saveBook($BookRequest, $book);
    
        return new Response(null, Response::HTTP_NO_CONTENT);
    }
    public function delete(Book $book)
    {
        $this->em->remove($book);
        $this->em->flush();
    
        return new Response(null, Response::HTTP_NO_CONTENT);
    }
    
    /**
     * @param BookRequest $bookRequest
     * @param Book|null $book
     * @return string
     */
    private function saveBook(BookRequest $bookRequest, ?Book $book = null): string
    {
        if ($book === null) {
            $book = new Book();
        }
        
        $book->setTitle($bookRequest->getTitle())
            ->setYear($bookRequest->getYear())
            ->setDescription($bookRequest->getDescription())
            ->setAuthor($bookRequest->getAuthor());
    
        $this->em->persist($book);
        $this->em->flush();
        
        return $this->serializer->serialize($book,'json');
    }
}