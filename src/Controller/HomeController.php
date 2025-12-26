<?php
// ============================================
// src/Controller/HomeController.php
// ============================================

namespace App\Controller;

use App\Repository\BookRepository;
use App\Repository\CategoryRepository;
use App\Repository\EditorRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
 use Symfony\Component\Routing\Attribute\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(
        BookRepository $bookRepository,
        CategoryRepository $categoryRepository,
        EditorRepository $editorRepository
    ): Response {
        return $this->render('front/home.html.twig', [
            'latest_books' => $bookRepository->findLatestBooks(8),
            'categories' => $categoryRepository->findAllOrdered(),
            'editors' => $editorRepository->findAllOrdered(),
        ]);
    }
}
