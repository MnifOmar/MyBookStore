<?php
// ============================================
 // src/Controller/ProfileController.php
 // ============================================

 namespace App\Controller;

 use App\Form\ProfileType;
 use App\Repository\OrderRepository;
 use Doctrine\ORM\EntityManagerInterface;
 use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
 use Symfony\Component\HttpFoundation\Request;
 use Symfony\Component\HttpFoundation\Response;
 use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
  use Symfony\Component\Routing\Attribute\Route;
 use Symfony\Component\Security\Http\Attribute\IsGranted;

 #[Route('/profile')]
 #[IsGranted('ROLE_ABONNE')]
 class ProfileController extends AbstractController
 {
     #[Route('/', name: 'app_profile')]
     public function index(Request $request, EntityManagerInterface $em, UserPasswordHasherInterface $passwordHasher): Response
     {
         $user = $this->getUser();
         $form = $this->createForm(ProfileType::class, $user);
         $form->handleRequest($request);

         if ($form->isSubmitted() && $form->isValid()) {
             $plainPassword = $form->get('plainPassword')->getData();
             if ($plainPassword) {
                 $user->setPassword($passwordHasher->hashPassword($user, $plainPassword));
             }

             $em->flush();
             $this->addFlash('success', 'Profil mis à jour avec succès.');
             return $this->redirectToRoute('app_profile');
         }

         return $this->render('front/profile.html.twig', [
             'form' => $form->createView(),
         ]);
     }

     #[Route('/orders', name: 'app_profile_orders')]
     public function orders(OrderRepository $orderRepository): Response
     {
         $orders = $orderRepository->findByUser($this->getUser()->getId());

         return $this->render('front/orders.html.twig', [
             'orders' => $orders,
         ]);
     }
 }
