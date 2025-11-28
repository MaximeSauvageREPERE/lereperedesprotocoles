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

#[Route('/protocole')]
final class ProtocoleController extends AbstractController
{
    #[Route(name: 'app_protocole_index', methods: ['GET'])]
    public function index(ProtocoleRepository $protocoleRepository): Response
    {
        return $this->render('protocole/index.html.twig', [
            'protocoles' => $protocoleRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_protocole_new', methods: ['GET', 'POST'])]
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
    public function show(Protocole $protocole): Response
    {
        return $this->render('protocole/show.html.twig', [
            'protocole' => $protocole,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_protocole_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Protocole $protocole, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ProtocoleType::class, $protocole);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_protocole_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('protocole/edit.html.twig', [
            'protocole' => $protocole,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_protocole_delete', methods: ['POST'])]
    public function delete(Request $request, Protocole $protocole, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$protocole->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($protocole);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_protocole_index', [], Response::HTTP_SEE_OTHER);
    }
}
