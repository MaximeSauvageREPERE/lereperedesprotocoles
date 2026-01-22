<?php

namespace App\Controller\Admin;

use App\Entity\DemandeInscription;
use App\Entity\Utilisateur;
use App\Repository\DemandeInscriptionRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/admin/demandes')]
#[IsGranted('ROLE_ADMIN')]
class DemandeController extends AbstractController
{
    #[Route('/', name: 'admin_demandes')]
    public function index(DemandeInscriptionRepository $repo, PaginatorInterface $paginator, Request $request): Response
    {
        $query = $repo->createQueryBuilder('d')
            ->orderBy('d.createdAt', 'DESC')
            ->getQuery();
        
        $demandes = $paginator->paginate(
            $query,
            $request->query->getInt('page', 1),
            9 // Nombre d'éléments par page
        );

        return $this->render('admin/demandes/index.html.twig', [
            'demandes' => $demandes,
        ]);
    }

     #[Route('/accepter/{id}', name: 'admin_demande_accepter')]
    public function accepter(
        DemandeInscription $demande,
        EntityManagerInterface $em,
        UserPasswordHasherInterface $hasher
    ): RedirectResponse {
        if ($demande->getStatus() !== 'pending') {
            return $this->redirectToRoute('admin_demandes');
        }

        // Créer le compte utilisateur
        $user = new Utilisateur();
        $user->setNom($demande->getNom());
        $user->setPrenom($demande->getPrenom());
        $user->setEmail($demande->getEmail());
        $user->setRoles(['ROLE_USER']);

        // Utiliser le mot de passe fourni dans la demande
        $user->setPassword($hasher->hashPassword($user, $demande->getMotDePasse()));

        $demande->setStatus('accepted');

        $em->persist($user);
        $em->flush();

        $this->addFlash('success', "Demande acceptée. L'utilisateur peut se connecter avec son mot de passe.");
        return $this->redirectToRoute('admin_demandes');
    }

    #[Route('/refuser/{id}', name: 'admin_demande_refuser')]
    public function refuser(DemandeInscription $demande, EntityManagerInterface $em): RedirectResponse
    {
        if ($demande->getStatus() === 'pending') {
            $demande->setStatus('refused');
            $em->flush();
            $this->addFlash('info', 'Demande refusée.');
        }
        return $this->redirectToRoute('admin_demandes');
    }
}