<?php

namespace App\Tests;

use App\Entity\Book;
use Doctrine\ORM\EntityManagerInterface;
use Lakion\ApiTestCase\JsonApiTestCase;
use Symfony\Component\HttpFoundation\Response;

class BookControllerTest extends JsonApiTestCase
{
    /**
     * @var EntityManagerInterface
     */
    protected $em;
    
    public function setUp()
    {
        $kernel = self::bootKernel();
        
        $this->em = $kernel->getContainer()
            ->get('doctrine')
            ->getManager();
    }
    
    /**
     * @throws \Exception
     */
    public function testList()
    {
        $this->client->request('GET', '/books/');
        $response = $this->client->getResponse();
        $this->assertResponse($response, 'getBookList', Response::HTTP_OK);
    }

    /**
     * @throws \Exception
     */
    public function testCreateSuccess()
    {
        $this->client->request(
            'POST',
            '/books/',
            [
                'title' => 'Create',
                'year' => 1234,
                'description' => 'very long',
                'author' => 'me',
            ]
        );
        $response = $this->client->getResponse();

        $this->assertResponse($response, 'createBook', Response::HTTP_CREATED);
    }
    
    /**
     * @throws \Exception
     */
    public function testCreateFail()
    {
        $this->client->request(
            'POST',
            '/books/'
        );
        $response = $this->client->getResponse();

        $this->assertResponse($response, 'actionFail', Response::HTTP_BAD_REQUEST);
    }
    
    /**
     * @throws \Exception
     */
    public function testShow()
    {
        $this->client->request('GET', '/books/1');

        $response = $this->client->getResponse();
        $this->assertResponse($response, 'showBook', Response::HTTP_OK);
    }
    
    /**
     * @throws \Exception
     */
    public function testUpdateSuccess()
    {
        /** @var Book $book */
        $book = $this->em->getRepository(Book::class)->findOneBy(['author' => 'me']);
        $this->client->request(
            'PUT',
            '/books/' . $book->getId(),
            [
                'title' => 'Update',
                'year' => 1234,
                'description' => 'very long',
                'author' => 'me',
            ]
        );
        $this->em->refresh($book);
        $response = $this->client->getResponse();
        
        $this->assertResponseCode($response, Response::HTTP_NO_CONTENT);
        $this->assertEquals($book->getTitle(), 'Update');
    }
    
    /**
     * @throws \Exception
     */
    public function testUpdateFail()
    {
        /** @var Book $book */
        $book = $this->em->getRepository(Book::class)->findOneBy(['author' => 'me']);
        $this->client->request(
            'PUT',
            '/books/' . $book->getId()
        );
        $response = $this->client->getResponse();
        
        $this->assertResponse($response, 'actionFail', Response::HTTP_BAD_REQUEST);
    }

    public function testBookDelete()
    {
        /** @var Book $book */
        $book = $this->em->getRepository(Book::class)->findOneBy(['author' => 'me']);
        $this->client->request('DELETE', '/books/' . $book->getId());
        $response = $this->client->getResponse();
        
        $this->assertResponseCode($response, Response::HTTP_NO_CONTENT);
    }
    
    /**
     * {@inheritdoc}
     */
    public function tearDown(): void
    {
        $this->client = null;
        
        parent::tearDown();
    }
}
