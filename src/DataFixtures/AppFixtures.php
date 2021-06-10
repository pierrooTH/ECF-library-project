<?php

namespace App\DataFixtures;

use App\Entity\Author;
use App\Entity\Book;
use App\Entity\Borrower;
use App\Entity\Genre;
use App\Entity\Loan;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory as FakerFactory;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{
    private $encoder;
    private $faker;

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
        $this->faker = FakerFactory::create('fr_FR');
    }

    public function load(ObjectManager $manager)
    {
        // $product = new Product();
        // $manager->persist($product);
        $booksCount = 1000;
        $authorCount = 500;
        $authorPerBook = 2;
        $genrePerBook = 77;
        $genresArr = ['poésie', 'nouvelle', 'roman historique', "roman d'amour", "roman d'avanture", "sicence-fiction", 'fantasy', 'biographie', 'conte', 'témoignage', 'théâtre', 'essai', 'journal intime'];
        
        $this->loadAdmin($manager);
        $authors = $this->loadAuthors($manager, $authorCount);
        $books = $this->loadBooks($manager, $authors, $authorPerBook, $booksCount);
        $genres = $this->loadGenres($manager, $genresArr, $books, $genrePerBook);
        

        $manager->flush();
    }
    public function loadAdmin(ObjectManager $manager)
    {
        $user = new User();
        $user->setEmail('admin@example.com');
        $password = $this->encoder->encodePassword($user, '123');
        $user->setPassword($password);
        $user->setRoles(['ROLE_ADMIN']);

        $manager->persist($user);
    }

    public function loadAuthors(ObjectManager $manager, int $count)
    {
        $authors = [];
        $author = new Author();
            $author->setFirstname('nom inconnu');
            $author->setLastname('');
            $manager->persist($author);
            $authors[] = $author;
        $author = new Author();
            $author->setFirstname('Hugues');
            $author->setLastname('Cartier');
            $manager->persist($author);
            $authors[] = $author;
        $author = new Author();
            $author->setFirstname('Armand');
            $author->setLastname('Lambert');
            $manager->persist($author);
            $authors[] = $author;
        $author = new Author();
            $author->setFirstname('Thomas');
            $author->setLastname('Moitessier');
            $manager->persist($author);
            $authors[] = $author;

        for ($i = 4; $i < $count; $i++) {
            $author = new Author();
            $author->setFirstname($this->faker->firstname());
            $author->setLastname($this->faker->lastname());
            $manager->persist($author);
            $authors[] = $author;
        }
        return $authors;
    }
    
    public function loadBooks(ObjectManager $manager, array $authors, int $authorPerBook, int $count)
    {
        $books = [];
        $authorIndex = 0;
        $author = $authors[$authorIndex];

        $book = new Book();
            $book->setTitle('Lorem ipsum dolor sit amet');
            $book->setEditionYears('2010');
            $book->setPagesNumber('100');
            $book->setCodeIsbn('9785786930024');
            $book->setAuthor($author);
            $manager->persist($book);
            $books[] = $book;

        $book = new Book();
            $book->setTitle('Consectetur adipiscing elit');
            $book->setEditionYears('2011');
            $book->setPagesNumber('150');
            $book->setCodeIsbn('9783817260935');
            $manager->persist($book);
            $book->setAuthor($author);
            $books[] = $book;
        
            $authorIndex++;

        $book = new Book();
            $book->setTitle('Mihi quidem Antiochum');
            $book->setEditionYears('2012');
            $book->setPagesNumber('200');
            $book->setCodeIsbn('9782020493727');
            $manager->persist($book);
            $book->setAuthor($author);
            $books[] = $book;
        $book = new Book();
            $book->setTitle('Quem audis satis belle');
            $book->setEditionYears('2013');
            $book->setPagesNumber('250');
            $book->setCodeIsbn('9794059561353');
            $manager->persist($book);
            $book->setAuthor($author);
            $books[] = $book;
         for ($i = 4; $i < $count; $i++) {

            $author = $authors[$authorIndex];

            if ($i % $authorPerBook == 0) {
                $authorIndex++;
            }
            $book = new Book();
            $book->setTitle($this->faker->sentence($nbWords = 6, $variableNbWords = true));
            $book->setEditionYears($this->faker->year($max = 'now'));
            $book->setPagesNumber($this->faker->numberBetween($min = 100, $max = 1500));
            $book->setCodeIsbn($this->faker->isbn13());
            $book->setAuthor($author);

            $manager->persist($book);
            $books[] = $book;
        }
        return $books;
    }
    public function loadGenres(ObjectManager $manager, array $genresArr, array $books, int $genrePerBook)
    {
        foreach($genresArr as $genre){
            $bookIndex = 0;
            $genres = [];

            $genre = new Genre();
            $genre->setName($genre);

            
            $manager->persist($genre);


            $genres[] = $genre;

        }
        return $genres;
    }
}
