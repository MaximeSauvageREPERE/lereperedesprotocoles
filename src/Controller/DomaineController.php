<?php

namespace App\Controller;

use App\Entity\Domaine;
use App\Form\DomaineType;
use App\Repository\DomaineRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/domaine')]
final class DomaineController extends AbstractController
{
    #[Route(name: 'app_domaine_index', methods: ['GET'])]
    public function index(DomaineRepository $domaineRepository): Response
    {
        return $this->render('domaine/index.html.twig', [
            'domaines' => $domaineRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_domaine_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $domaine = new Domaine();
        $form = $this->createForm(DomaineType::class, $domaine);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($domaine);
            $entityManager->flush();

            return $this->redirectToRoute('app_domaine_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('domaine/new.html.twig', [
            'domaine' => $domaine,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_domaine_show', methods: ['GET'])]
    public function show(Domaine $domaine): Response
    {
        return $this->render('domaine/show.html.twig', [
            'domaine' => $domaine,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_domaine_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Domaine $domaine, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(DomaineType::class, $domaine);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_domaine_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('domaine/edit.html.twig', [
            'domaine' => $domaine,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_domaine_delete', methods: ['POST'])]
    public function delete(Request $request, Domaine $domaine, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$domaine->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($domaine);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_domaine_index', [], Response::HTTP_SEE_OTHER);
    }
}
