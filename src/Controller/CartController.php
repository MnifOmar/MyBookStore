<?php
// ============================================
// src/Controller/CartController.php
// ============================================

namespace App\Controller;

use App\Entity\Book;
use App\Entity\Order;
use App\Entity\OrderItem;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
 use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/cart')]
#[IsGranted('ROLE_ABONNE')]
class CartController extends AbstractController
{
    #[Route('/', name: 'app_cart')]
    public function index(SessionInterface $session, EntityManagerInterface $em): Response
    {
        $cart = $session->get('cart', []);
        $cartWithData = [];
        $total = 0;

        foreach ($cart as $id => $quantity) {
            $book = $em->getRepository(Book::class)->find($id);
            if ($book) {
                $cartWithData[] = [
                    'book' => $book,
                    'quantity' => $quantity
                ];
                $total += $book->getPrice() * $quantity;
            }
        }

        return $this->render('front/cart.html.twig', [
            'items' => $cartWithData,
            'total' => $total
        ]);
    }

    #[Route('/add/{id}', name: 'app_cart_add')]
    public function add(Book $book, SessionInterface $session): Response
    {
        $cart = $session->get('cart', []);
        $id = $book->getId();

        if (!empty($cart[$id])) {
            $cart[$id]++;
        } else {
            $cart[$id] = 1;
        }

        $session->set('cart', $cart);
        $this->addFlash('success', 'Livre ajouté au panier.');

        return $this->redirectToRoute('app_cart');
    }

    #[Route('/remove/{id}', name: 'app_cart_remove')]
    public function remove(Book $book, SessionInterface $session): Response
    {
        $cart = $session->get('cart', []);
        $id = $book->getId();

        if (!empty($cart[$id])) {
            unset($cart[$id]);
        }

        $session->set('cart', $cart);
        $this->addFlash('success', 'Livre retiré du panier.');

        return $this->redirectToRoute('app_cart');
    }

    #[Route('/confirm', name: 'app_cart_confirm')]
    public function confirm(SessionInterface $session, EntityManagerInterface $em): Response
    {
        $cart = $session->get('cart', []);

        if (empty($cart)) {
            $this->addFlash('error', 'Votre panier est vide.');
            return $this->redirectToRoute('app_cart');
        }

        $order = new Order();
        $order->setUser($this->getUser());
        $totalAmount = 0;

        foreach ($cart as $id => $quantity) {
            $book = $em->getRepository(Book::class)->find($id);
            if ($book && $book->getStock() >= $quantity) {
                $orderItem = new OrderItem();
                $orderItem->setBook($book);
                $orderItem->setQuantity($quantity);
                $orderItem->setUnitPrice($book->getPrice());
                $order->addOrderItem($orderItem);

                $totalAmount += $book->getPrice() * $quantity;

                // Mettre à jour le stock
                $book->setStock($book->getStock() - $quantity);
            }
        }

        $order->setTotalAmount($totalAmount);
        $em->persist($order);
        $em->flush();

        $session->remove('cart');
        $this->addFlash('success', 'Votre commande a été confirmée avec succès.');

        return $this->redirectToRoute('app_profile_orders');
    }
}
