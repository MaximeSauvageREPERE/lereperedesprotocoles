<?php

namespace App\Controller;

use App\Entity\Protocole;
use App\Form\ProtocoleType;
use App\Repository\ProtocoleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use App\Repository\DomaineRepository;
use App\Repository\RubriqueRepository;
use App\Repository\ThemeRepository;

#[Route('/protocole')]
final class ProtocoleController extends AbstractController
{
    #[Route(name: 'app_protocole_index', methods: ['GET'])]
    #[IsGranted('ROLE_USER')]
    public function index(Request $request, ProtocoleRepository $protocoleRepository, DomaineRepository $domaineRepository, RubriqueRepository $rubriqueRepository, ThemeRepository $themeRepository): Response
    {
        $domaineId = $request->query->get('domaine');
        $rubriqueId = $request->query->get('rubrique');
        $themeId = $request->query->get('theme');

        // Filtrer les protocoles selon les critères
        $queryBuilder = $protocoleRepository->createQueryBuilder('p')
            ->leftJoin('p.theme', 't')
            ->leftJoin('t.rubrique', 'r')
            ->leftJoin('r.domaines', 'd');

        if ($themeId) {
            $queryBuilder->andWhere('t.id = :themeId')->setParameter('themeId', $themeId);
        }
        if ($rubriqueId) {
            $queryBuilder->andWhere('r.id = :rubriqueId')->setParameter('rubriqueId', $rubriqueId);
        }
        if ($domaineId) {
            $queryBuilder->andWhere('d.id = :domaineId')->setParameter('domaineId', $domaineId);
        }

        $protocoles = $queryBuilder->getQuery()->getResult();

        // Admin : vue complète (CRUD)
        if ($this->isGranted('ROLE_ADMIN')) {
            return $this->render('protocole/index.html.twig', [
                'protocoles' => $protocoles,
                'domaines' => $domaineRepository->findAll(),
                'rubriques' => $rubriqueRepository->findAll(),
                'themes' => $themeRepository->findAll(),
                'selectedDomaine' => $domaineId,
                'selectedRubrique' => $rubriqueId,
                'selectedTheme' => $themeId,
            ]);
        }
        // Utilisateur : vue restreinte
        return $this->render('protocole/index_user.html.twig', [
            'protocoles' => $protocoles,
            'domaines' => $domaineRepository->findAll(),
            'rubriques' => $rubriqueRepository->findAll(),
            'themes' => $themeRepository->findAll(),
            'selectedDomaine' => $domaineId,
            'selectedRubrique' => $rubriqueId,
            'selectedTheme' => $themeId,
        ]);
    }

    #[Route('/new', name: 'app_protocole_new', methods: ['GET', 'POST'])]
    #[IsGranted('ROLE_ADMIN')]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $protocole = new Protocole();
        $form = $this->createForm(ProtocoleType::class, $protocole);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($protocole);
            $entityManager->flush();

            return $this->redirectToRoute('app_protocole_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('protocole/new.html.twig', [
            'protocole' => $protocole,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_protocole_show', methods: ['GET'])]
    #[IsGranted('ROLE_USER')]
    public function show(Protocole $protocole): Response
    {
        // Admin : vue complète
        if ($this->isGranted('ROLE_ADMIN')) {
            return $this->render('protocole/show.html.twig', [
                'protocole' => $protocole,
            ]);
        }
        // Utilisateur : vue restreinte
        return $this->render('protocole/show_user.html.twig', [
            'protocole' => $protocole,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_protocole_edit', methods: ['GET', 'POST'])]
    #[IsGranted('ROLE_ADMIN')]
    public function edit(Request $request, Protocole $protocole, EntityManagerInterface $entityManager): Response
    {
        $oldFichier = $protocole->getFichier();
        
        $form = $this->createForm(ProtocoleType::class, $protocole);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Supprime l'ancien PDF si un nouveau est uploadé
            if ($protocole->getPdfFile() && $oldFichier && $oldFichier !== $protocole->getFichier()) {
                $oldFilePath = $this->getParameter('kernel.project_dir') . '/public/uploads/protocoles/' . $oldFichier;
                if (file_exists($oldFilePath)) {
                    unlink($oldFilePath);
                }
            }

            $entityManager->flush();

            return $this->redirectToRoute('app_protocole_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('protocole/edit.html.twig', [
            'protocole' => $protocole,
            'form' => $form,
        ]);
    }

    #[Route('/{id}/delete', name: 'app_protocole_delete', methods: ['POST'])]
    #[IsGranted('ROLE_ADMIN')]
    public function delete(Request $request, Protocole $protocole, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$protocole->getId(), $request->getPayload()->getString('_token'))) {
            // Supprime le fichier PDF s'il existe
            $fichier = $protocole->getFichier();
            if ($fichier) {
                $filePath = $this->getParameter('kernel.project_dir') . '/public/uploads/protocoles/' . $fichier;
                if (file_exists($filePath)) {
                    unlink($filePath);
                }
            }
            
            $entityManager->remove($protocole);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_protocole_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/{id}/delete-pdf', name: 'app_protocole_delete_pdf', methods: ['POST'])]
    #[IsGranted('ROLE_ADMIN')]
    public function deletePdf(Protocole $protocole, EntityManagerInterface $em): Response
    {
        // Récupère le chemin du fichier avant de le supprimer de l'entité
        $fichier = $protocole->getFichier();
        
        if ($fichier) {
            $filePath = $this->getParameter('kernel.project_dir') . '/public/uploads/protocoles/' . $fichier;
            
            // Supprime le fichier physique s'il existe
            if (file_exists($filePath)) {
                unlink($filePath);
            }
        }
        
        // Supprime les références en base
        $protocole->setFichier(null);
        $protocole->setPdfFile(null);
        $em->flush();

        return $this->json(['success' => true]);
    }
}