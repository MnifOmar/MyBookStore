<?php
// src/DataFixtures/AppFixtures.php

namespace App\DataFixtures;

use App\Entity\Author;
use App\Entity\Book;
use App\Entity\Category;
use App\Entity\Editor;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    private UserPasswordHasherInterface $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }

    public function load(ObjectManager $manager): void
    {
        // Créer un administrateur
        $admin = new User();
        $admin->setEmail('admin@bookstore.com');
        $admin->setFirstName('Admin');
        $admin->setLastName('Bookstore');
        $admin->setRoles(['ROLE_ADMIN']);
        $admin->setPassword($this->passwordHasher->hashPassword($admin, 'admin123'));
        $admin->setAddress('123 Rue Admin, Tunis');
        $admin->setPhone('+216 12 345 678');
        $manager->persist($admin);

        // Créer un agent
        $agent = new User();
        $agent->setEmail('agent@bookstore.com');
        $agent->setFirstName('Agent');
        $agent->setLastName('Librairie');
        $agent->setRoles(['ROLE_AGENT']);
        $agent->setPassword($this->passwordHasher->hashPassword($agent, 'agent123'));
        $agent->setAddress('456 Avenue Agent, Tunis');
        $agent->setPhone('+216 98 765 432');
        $manager->persist($agent);

        // Créer un abonné
        $abonne = new User();
        $abonne->setEmail('user@bookstore.com');
        $abonne->setFirstName('Mohamed');
        $abonne->setLastName('Ben Ali');
        $abonne->setRoles(['ROLE_ABONNE']);
        $abonne->setPassword($this->passwordHasher->hashPassword($abonne, 'user123'));
        $abonne->setAddress('789 Rue Client, Tunis');
        $abonne->setPhone('+216 20 111 222');
        $manager->persist($abonne);

        // Créer des catégories
        $categories = [];
        $categoryNames = [
            'Romans' => 'Découvrez notre sélection de romans classiques et contemporains',
            'Science-Fiction' => 'Plongez dans des univers futuristes et imaginaires',
            'Histoire' => 'Explorez le passé à travers nos livres d\'histoire',
            'Développement Personnel' => 'Améliorez-vous avec nos guides de développement personnel',
            'Informatique' => 'Apprenez les dernières technologies',
            'Jeunesse' => 'Livres pour enfants et adolescents',
        ];

        foreach ($categoryNames as $name => $description) {
            $category = new Category();
            $category->setName($name);
            $category->setDescription($description);
            $categories[] = $category;
            $manager->persist($category);
        }

        // Créer des éditeurs
        $editors = [];
        $editorData = [
            ['name' => 'Gallimard', 'address' => '5 Rue Gaston-Gallimard, Paris', 'email' => 'contact@gallimard.fr'],
            ['name' => 'Flammarion', 'address' => '87 Quai Panhard-et-Levassor, Paris', 'email' => 'contact@flammarion.fr'],
            ['name' => 'Hachette', 'address' => '58 Rue Jean Bleuzen, Vanves', 'email' => 'contact@hachette.fr'],
            ['name' => 'Dunod', 'address' => '11 Rue Paul Bert, Malakoff', 'email' => 'contact@dunod.fr'],
            ['name' => 'Eyrolles', 'address' => '61 Boulevard Saint-Germain, Paris', 'email' => 'contact@eyrolles.com'],
        ];

        foreach ($editorData as $data) {
            $editor = new Editor();
            $editor->setName($data['name']);
            $editor->setAddress($data['address']);
            $editor->setEmail($data['email']);
            $editor->setPhone('+33 1 23 45 67 89');
            $editors[] = $editor;
            $manager->persist($editor);
        }

        // Créer des auteurs
        $authors = [];
        $authorData = [
            ['firstName' => 'Victor', 'lastName' => 'Hugo', 'biography' => 'Écrivain français du XIXe siècle'],
            ['firstName' => 'Albert', 'lastName' => 'Camus', 'biography' => 'Philosophe et écrivain français'],
            ['firstName' => 'Isaac', 'lastName' => 'Asimov', 'biography' => 'Auteur de science-fiction américain'],
            ['firstName' => 'J.K.', 'lastName' => 'Rowling', 'biography' => 'Auteure britannique de Harry Potter'],
            ['firstName' => 'Antoine', 'lastName' => 'de Saint-Exupéry', 'biography' => 'Aviateur et écrivain français'],
            ['firstName' => 'Yuval Noah', 'lastName' => 'Harari', 'biography' => 'Historien et auteur israélien'],
            ['firstName' => 'Robert C.', 'lastName' => 'Martin', 'biography' => 'Développeur et auteur de Clean Code'],
        ];

        foreach ($authorData as $data) {
            $author = new Author();
            $author->setFirstName($data['firstName']);
            $author->setLastName($data['lastName']);
            $author->setBiography($data['biography']);
            $authors[] = $author;
            $manager->persist($author);
        }

        // Créer des livres
        $booksData = [
            [
                'title' => 'Les Misérables',
                'isbn' => '978-2070409228',
                'description' => 'Un chef-d\'œuvre de Victor Hugo sur la justice sociale',
                'price' => '25.50',
                'stock' => 15,
                'year' => 1862,
                'category' => 0,
                'editor' => 0,
                'authors' => [0],
            ],
            [
                'title' => 'L\'Étranger',
                'isbn' => '978-2070360024',
                'description' => 'Roman philosophique d\'Albert Camus',
                'price' => '18.00',
                'stock' => 20,
                'year' => 1942,
                'category' => 0,
                'editor' => 0,
                'authors' => [1],
            ],
            [
                'title' => 'Fondation',
                'isbn' => '978-2070360031',
                'description' => 'Premier tome de la saga Fondation d\'Asimov',
                'price' => '22.00',
                'stock' => 12,
                'year' => 1951,
                'category' => 1,
                'editor' => 1,
                'authors' => [2],
            ],
            [
                'title' => 'Harry Potter à l\'école des sorciers',
                'isbn' => '978-2070643028',
                'description' => 'Le début de la saga magique',
                'price' => '20.00',
                'stock' => 30,
                'year' => 1997,
                'category' => 5,
                'editor' => 0,
                'authors' => [3],
            ],
            [
                'title' => 'Le Petit Prince',
                'isbn' => '978-2070612758',
                'description' => 'Un conte philosophique et poétique',
                'price' => '15.00',
                'stock' => 25,
                'year' => 1943,
                'category' => 5,
                'editor' => 0,
                'authors' => [4],
            ],
            [
                'title' => 'Sapiens: Une brève histoire de l\'humanité',
                'isbn' => '978-2226257017',
                'description' => 'L\'histoire de l\'humanité selon Yuval Noah Harari',
                'price' => '28.00',
                'stock' => 18,
                'year' => 2011,
                'category' => 2,
                'editor' => 1,
                'authors' => [5],
            ],
            [
                'title' => 'Clean Code',
                'isbn' => '978-0132350884',
                'description' => 'Guide du développement logiciel agile',
                'price' => '45.00',
                'stock' => 10,
                'year' => 2008,
                'category' => 4,
                'editor' => 4,
                'authors' => [6],
            ],
        ];

        foreach ($booksData as $data) {
            $book = new Book();
            $book->setTitle($data['title']);
            $book->setIsbn($data['isbn']);
            $book->setDescription($data['description']);
            $book->setPrice($data['price']);
            $book->setStock($data['stock']);
            $book->setPublicationYear($data['year']);
            $book->setCategory($categories[$data['category']]);
            $book->setEditor($editors[$data['editor']]);

            foreach ($data['authors'] as $authorIndex) {
                $book->addAuthor($authors[$authorIndex]);
            }

            $manager->persist($book);
        }

        $manager->flush();
    }
}
