<?php
// ============================================
// src/Controller/BookCrudController.php
// ============================================
namespace App\Controller;

use App\Entity\Book;
use App\Repository\BookRepository;
use App\Repository\CategoryRepository;
use App\Repository\EditorRepository;
use App\Repository\AuthorRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
 use Symfony\Component\Routing\Attribute\Route;

#[Route('/books')]
class BookController extends AbstractController
{
    #[Route('/', name: 'app_books')]
    public function index(
        Request $request,
        BookRepository $bookRepository,
        CategoryRepository $categoryRepository,
        EditorRepository $editorRepository,
        AuthorRepository $authorRepository,
        PaginatorInterface $paginator
    ): Response {
        $searchTerm = $request->query->get('search');
        $categoryId = $request->query->get('category');
        $editorId = $request->query->get('editor');
        $authorId = $request->query->get('author');

        $query = $bookRepository->searchBooks($searchTerm, $categoryId, $editorId, $authorId);

        $pagination = $paginator->paginate(
            $query,
            $request->query->getInt('page', 1),
            12
        );

        return $this->render('front/books.html.twig', [
            'pagination' => $pagination,
            'categories' => $categoryRepository->findAllOrdered(),
            'editors' => $editorRepository->findAllOrdered(),
            'authors' => $authorRepository->findAllOrdered(),
            'current_search' => $searchTerm,
            'current_category' => $categoryId,
            'current_editor' => $editorId,
            'current_author' => $authorId,
        ]);
    }

    #[Route('/{id}', name: 'app_book_show')]
    public function show(Book $book): Response
    {
        return $this->render('front/book_detail.html.twig', [
            'book' => $book,
        ]);
    }
}
