<?php

namespace App\DataFixtures;

use App\Entity\Author;
use App\Entity\Book;
use App\Entity\Borrower;
use App\Entity\Genre;
use App\Entity\Loan;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory as FakerFactory;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture implements FixtureGroupInterface
{
    private $encoder;
    private $faker;

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
        $this->faker = FakerFactory::create('fr_FR');
    }

    public static function getGroups(): array
    {
        return ['test'];
    }

    public function load(ObjectManager $manager)
    {
        $booksCount = 1000;
        $authorCount = 500;
        $authorPerBook = 2;
        $genrePerBook = 77;
        $borrowerCount = 103;
        $loanCount = 203;
        $loanPerBorrower = 2;
        
        $this->loadAdmin($manager);
        $authors = $this->loadAuthors($manager, $authorCount);
        $genres = $this->loadGenres($manager);
        $books = $this->loadBooks($manager, $authors, $authorPerBook, $genres, $booksCount);
        $borrowers = $this->loadBorrowers($manager, $borrowerCount);
        $loans = $this->loadLoans($manager, $borrowers, $books, $loanPerBorrower, $loanCount);
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
        $author->setFirstname('');
        $author->setLastname('nom inconnu');
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

    public function loadGenres(ObjectManager $manager)
    {
        $bookGenres = [
            1 => [
                'name'=>'poésie'
            ],
            2 => [
                'name'=>'roman historique',
            ],
            3 => [
                'name'=>'roman d\'amour',
            ],
            4 => [
                'name'=>'roman d\'aventure',
            ],
            5 => [
                'name'=>'science-fiction',
            ],
            6 => [
                'name'=>'fantasy',
            ],
            7 => [
                'name'=>'biographie',
            ],
            8 => [
                'name'=>'témoignage',
            ],
            9 => [
                'name'=>'théâtre',
            ],
            10 => [
                'name'=>'essai',
            ],
            11 => [
                'name'=>'journal intime'
            ],
            12 => [
                'name'=>'nouvelle'
            ],
            13 => [
                'name'=>'conte'
            ],
        ];
        $genres= [];

        foreach($bookGenres as $key => $value){
            $genre = new Genre();
            $genre->setName($value['name']);

            $manager->persist($genre);

            $genres[] = $genre;
        }
        return $genres;

    }
    
    public function loadBooks(ObjectManager $manager, array $authors, int $authorPerBook, array $genres, int $count)
    {
        $books = [];

        $book = new Book();
        $book->setTitle('Lorem ipsum dolor sit amet');
        $book->setEditionYears('2010');
        $book->setPagesNumber('100');
        $book->setCodeIsbn('9785786930024');
        $authorIndex =0;
        $author = $authors[$authorIndex];
        $book->setAuthor($author);
        $genreIndex = 0;
        $genre = $genres[$genreIndex];
        $book->addGenre($genre);
        $manager->persist($book);
        $books[] = $book;

        $book = new Book();
        $book->setTitle('Consectetur adipiscing elit');
        $book->setEditionYears('2011');
        $book->setPagesNumber('150');
        $book->setCodeIsbn('9783817260935');
        $authorIndex = 1;
        $author = $authors[$authorIndex];
        $book->setAuthor($author);
        $genreIndex = 1;
        $genre = $genres[$genreIndex];
        $book->addGenre($genre);
        $manager->persist($book);
        $books[] = $book;  

        $book = new Book();
        $book->setTitle('Mihi quidem Antiochum');
        $book->setEditionYears('2012');
        $book->setPagesNumber('200');
        $book->setCodeIsbn('9782020493727');
        $authorIndex = 2;
        $author = $authors[$authorIndex];
        $book->setAuthor($author);
        $genreIndex = 2;
        $genre = $genres[$genreIndex];
        $book->addGenre($genre);
        $manager->persist($book);
        $books[] = $book;

        $book = new Book();
        $book->setTitle('Quem audis satis belle');
        $book->setEditionYears('2013');
        $book->setPagesNumber('250');
        $book->setCodeIsbn('9794059561353');
        $authorIndex = 3;
        $author = $authors[$authorIndex];
        $book->setAuthor($author);
        $genreIndex = 3;
        $genre = $genres[$genreIndex];
        $book->addGenre($genre);
        $manager->persist($book);
        $books[] = $book;

        $authorIndex = 1;

         for ($i = 4; $i < $count; $i++) {
            $author = $authors[$authorIndex];
            if ($i % $authorPerBook == 0) {
                $authorIndex++;
            }

            $book = new Book();
            $book->setTitle($this->faker->sentence($nbWords = 6, $variableNbWords = true));
            $book->setEditionYears($this->faker->year($max = 'now'));
            $book->setPagesNumber($this->faker->numberBetween($min = 100, $max = 1050));
            $book->setCodeIsbn($this->faker->isbn13());
            $book->setAuthor($author);

            $genreCount = random_int(1,2);
            $randomGenres = $this->faker->randomElements($genres, $genreCount);
            foreach ($randomGenres as $randomGenre) {
                $book->addGenre($randomGenre);
            }
            $manager->persist($book);
            $books[] = $book;
        }
        return $books;
    }

    public function loadBorrowers(ObjectManager $manager, $count)
    {
        $borrowers = [];

        $user = new User();
        $user->setEmail('foo.foo@example.com');
        $password = $this->encoder->encodePassword($user, '123');
        $user->setPassword($password);
        $user->setRoles(['ROLE_BORROWER']);
        $manager->persist($user);

        $borrower = new Borrower();
        $borrower->setLastname('foo');
        $borrower->setFirstname('foo');
        $borrower->setPhoneNumber('123456789');
        $borrower->setActive(true);
        $borrower->setCreationDate(\DateTime::createFromFormat('Y-m-d H:i:s', '2020-01-01 10:00:00'));
        $borrower->setUser($user);
        $manager->persist($borrower);
        $borrowers[] = $borrower;

        $user = new User();
        $user->setEmail('bar.bar@example.com');
        $password = $this->encoder->encodePassword($user, '123');
        $user->setPassword($password);
        $user->setRoles(['ROLE_BORROWER']);
        $manager->persist($user);

        $borrower = new Borrower();
        $borrower->setLastname('bar');
        $borrower->setFirstname('bar');
        $borrower->setPhoneNumber('123456789');
        $borrower->setActive(false);
        $borrower->setCreationDate(\DateTime::createFromFormat('Y-m-d H:i:s', '2020-02-01 11:00:00'));
        $borrower->setModificationDate((\DateTime::createFromFormat('Y-m-d H:i:s', '2020-05-01 12:00:00')));
        $borrower->setUser($user);
        $manager->persist($borrower);
        $borrowers[] = $borrower;

        $user = new User();
        $user->setEmail('baz.baz@example.com');
        $password = $this->encoder->encodePassword($user, '123');
        $user->setPassword($password);
        $user->setRoles(['ROLE_BORROWER']);
        $manager->persist($user);

        $borrower = new Borrower();
        $borrower->setLastname('baz');
        $borrower->setFirstname('baz');
        $borrower->setPhoneNumber('123456789');
        $borrower->setActive(true);
        $borrower->setCreationDate(\DateTime::createFromFormat('Y-m-d H:i:s', '2020-03-01 12:00:00'));
        $borrower->setModificationDate(null);
        $borrower->setUser($user);
        $manager->persist($borrower);
        $borrowers[] = $borrower;

        for ($i = 3; $i < $count; $i++){

            $lastname = $this->faker->lastname();
            $firstname = $this->faker->firstname();

            $user = new User();
            $user->setEmail($lastname.'.'.$firstname.'@'.$this->faker->safeEmailDomain());
            $password = $this->encoder->encodePassword($user, '123');
            $user->setPassword($password);
            $user->setRoles(['ROLE_BORROWER']);
            $manager->persist($user);

            $borrower = new Borrower();
            $borrower->setLastname($lastname);
            $borrower->setFirstname($firstname);
            $borrower->setPhoneNumber($this->faker->phoneNumber());
            $borrower->setActive($this->faker->boolean());
            $borrower->setCreationDate($this->faker->dateTimeThisDecade());
            $creationDate = $borrower->getCreationDate();
            $modificationDate = \DateTime::createFromFormat('Y-m-d H:i:s', $creationDate->format('Y-m-d H:i:s'));
            $modificationDate->add(new \DateInterval('P4M'));
            $borrower->setModificationDate($modificationDate);
            $borrower->setUser($user);
            $manager->persist($borrower);
            $borrowers[] = $borrower;
        }
        return $borrowers;
    }

    public function loadLoans(ObjectManager $manager, array $borrowers, array $books, int $loanPerBorrower, int $count)
    {
        $loans = [];


        $loan = new Loan();
            $loan->setLoanDate(\DateTime::createFromFormat('Y-m-d H:i:s', '2020-02-01 10:00:00'));
            $loan->setReturnDate(\DateTime::createFromFormat('Y-m-d H:i:s', '2020-03-01 10:00:00'));
            $bookIndex = 0;
            $book = $books[$bookIndex];
            $loan->setBook($book);
            $borrowerIndex = 0;
            $borrower = $borrowers[$borrowerIndex];
            $loan->setBorrower($borrower);
            $manager->persist($loan);
            $loans[] = $loan;

            $loan = new Loan();
            $loan->setLoanDate(\DateTime::createFromFormat('Y-m-d H:i:s', '2020-03-01 10:00:00'));
            $loan->setReturnDate(\DateTime::createFromFormat('Y-m-d H:i:s', '2020-04-01 10:00:00'));
            $bookIndex = 1;
            $book = $books[$bookIndex];
            $loan->setBook($book);
            $borrowerIndex = 1;
            $borrower = $borrowers[$borrowerIndex];
            $loan->setBorrower($borrower);
            $manager->persist($loan);
            $loans[] = $loan;

            $loan = new Loan();
            $loan->setLoanDate(\DateTime::createFromFormat('Y-m-d H:i:s', '2020-04-01 10:00:00'));
            $loan->setReturnDate(null);
            $bookIndex = 2;
            $book = $books[$bookIndex];
            $loan->setBook($book);
            $borrowerIndex = 2;
            $borrower = $borrowers[$borrowerIndex];
            $loan->setBorrower($borrower);
            $manager->persist($loan);
            $loans[] = $loan;

            $bookIndex++;
            $borrowerIndex++;

            for ($i = 3; $i < $count; $i++) {
                $book = $books[$bookIndex];
                $bookIndex++;
                $borrower = $borrowers[$borrowerIndex];

                if ($i % $loanPerBorrower == 0) {
                    $borrowerIndex++;
                }

                $loan = new Loan();
                $loan->setLoanDate($this->faker->dateTimeThisDecade());
                $loanDate = $loan->getLoanDate();
                $returnDate = \DateTime::createFromFormat('Y-m-d H:i:s', $loanDate->format('Y-m-d H:i:s'));
                $returnDate->add(new \DateInterval('P3M'));
                $loan->setReturnDate($returnDate);
                $loan->setBook($book);
                $loan->setBorrower($borrower);
                $manager->persist($loan);
                $loans[] = $loan;
            }

        return $loans;
    }
}
