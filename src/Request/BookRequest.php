<?php

namespace App\Request;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints as Assert;

class BookRequest
{
    /**
     * @var string
     * @Assert\NotBlank()
     */
    private $title;
    
    /**
     * @var int
     */
    private $year;
    
    /**
     * @var string
     * @Assert\NotBlank()
     */
    public $description;
    
    /**
     * @var string
     * @Assert\NotBlank()
     */
    public $author;
    
    /**
     * @param Request $request
     */
    public function __construct(Request $request)
    {
        $this->title = $request->get('title');
        $this->year = $request->get('year');
        $this->description = $request->get('description');
        $this->author = $request->get('author');
    }
    
    
    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }
    
    /**
     * @return int
     */
    public function getYear(): int
    {
        return $this->year;
    }
    
    /**
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }
    
    /**
     * @return string
     */
    public function getAuthor(): string
    {
        return $this->author;
    }
}