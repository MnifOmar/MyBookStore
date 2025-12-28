<?php

namespace App\Controller\Admin;

use App\Entity\Author;
use App\Entity\Book;
use App\Entity\Category;
use App\Entity\Editor;
use App\Entity\Order;
use App\Entity\User;
use App\Repository\BookRepository;
use App\Repository\OrderRepository;
use App\Repository\UserRepository;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractDashboardController
{
    private BookRepository $bookRepository;
    private OrderRepository $orderRepository;
    private UserRepository $userRepository;

    public function __construct(
        BookRepository  $bookRepository,
        OrderRepository $orderRepository,
        UserRepository  $userRepository
    )
    {
        $this->bookRepository = $bookRepository;
        $this->orderRepository = $orderRepository;
        $this->userRepository = $userRepository;
    }

    #[Route('/admin', name: 'admin')]
    public function index(): Response
    {
        $orders = $this->orderRepository->findAll();

        $statusCounts = [
            'pending' => 0,
            'processing' => 0,
            'shipped' => 0,
            'delivered' => 0,
            'cancelled' => 0,
        ];

        $totalRevenue = 0;
        foreach ($orders as $order) {
            $statusCounts[$order->getStatus()]++;
            $totalRevenue += (float)$order->getTotalAmount();
        }

        return $this->render('admin/dashboard.html.twig', [
            'total_books' => count($this->bookRepository->findAll()),
            'total_orders' => count($orders),
            'total_users' => count($this->userRepository->findAll()),
            'total_revenue' => $totalRevenue,
            'latest_orders' => $this->orderRepository->findBy([], ['createdAt' => 'DESC'], 5),
            'status_counts' => $statusCounts,
        ]);
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('MyBookstore Admin');
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToDashboard('Dashboard', 'fa fa-home');
        yield MenuItem::linkToCrud('Auteurs', 'fa fa-user', Author::class);
        yield MenuItem::linkToCrud('Éditeurs', 'fa fa-building', Editor::class);
        yield MenuItem::linkToCrud('Catégories', 'fa fa-tags', Category::class);
        yield MenuItem::linkToCrud('Livres', 'fa fa-book', Book::class);
        yield MenuItem::linkToCrud('Commandes', 'fa fa-shopping-cart', Order::class);

        if ($this->isGranted('ROLE_ADMIN')) {
            yield MenuItem::linkToCrud('Utilisateurs', 'fa fa-users', User::class);
        }
    }
}
