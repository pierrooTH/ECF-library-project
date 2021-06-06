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
        
        $this->loadAdmin($manager);
        $books = $this->loadBooks($manager, $booksCount);

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
    
    public function loadBooks(ObjectManager $manager, $count)
    {
        $books = [];
        $author = new Author();
            $author->setFirstname('unknow');
            $author->setLastname('unknow');
        $author2 = new Author();    
            $author2->setFirstname('Hugues');
            $author2->setLastname('Cartier');
        $author3 = new Author();
            $author3->setFirstname('Armand');
            $author3->setLastname('Lambert');
        $author4 = new Author();
            $author4->setFirstname('Thomas');
            $author4->setLastname('Moitessier');
        $manager->persist($author);
        $manager->persist($author2);
        $manager->persist($author3);
        $manager->persist($author4);

        $book = new Book();
            $book->setTitle('Lorem ipsum dolor sit amet');
            $book->setEditionYears('2010');
            $book->setPagesNumber('100');
            $book->setCodeIsbn('9785786930024');
        $book2 = new Book();
            $book2->setTitle('Consectetur adipiscing elit');
            $book2->setEditionYears('2011');
            $book2->setPagesNumber('150');
            $book2->setCodeIsbn('9783817260935');
        $book3 = new Book();
            $book3->setTitle('Mihi quidem Antiochum');
            $book3->setEditionYears('2012');
            $book3->setPagesNumber('200');
            $book3->setCodeIsbn('9782020493727');
        $book4 = new Book();
            $book4->setTitle('Quem audis satis belle');
            $book4->setEditionYears('2013');
            $book4->setPagesNumber('250');
            $book4->setCodeIsbn('9794059561353');

        $book->setAuthor($author);
        $book2->setAuthor($author2);
        $book3->setAuthor($author3);
        $book4->setAuthor($author4);

        $manager->persist($book);
        $manager->persist($book2);
        $manager->persist($book3);
        $manager->persist($book4);

        $books[] = $book;

        $genre = ['poésie', 'nouvelle', 'roman historique', 
        "roman d'amour", "roman d'avanture", 
        'science-fiction', 'fantasy', 'biographie', 
        'conte', 'témoignage', 'théâtre', 'essai', 'journal intime' ];

        for ($i = 4; $i < $count; $i++) {
            $author = new Author();
            $author->setFirstname($this->faker->firstname());
            $author->setLastname($this->faker->lastname());
            $manager->persist($author);

            $genre = new Genre(); 
            
            $book = new Book();
            $book->setTitle($this->faker->sentence($nbWords = 6, $variableNbWords = true));
            $book->setEditionYears($this->faker->year($max = 'now'));
            $book->setPagesNumber($this->faker->numberBetween($min = 100, $max = 1500));
            $book->setCodeIsbn($this->faker->isbn13());
            $book->setAuthor($author);

            $manager->persist($book);
            $books[] = $book;
        }
    }
}
