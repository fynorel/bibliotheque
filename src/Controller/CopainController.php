<?php

namespace App\Controller;

use App\Entity\Copain;
use App\Form\CopainType;
use App\Repository\CopainRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/copain')]
final class CopainController extends AbstractController
{
    #[Route(name: 'app_copain_index', methods: ['GET'])]
    public function index(CopainRepository $copainRepository): Response
    {
        return $this->render('copain/index.html.twig', [
            'copains' => $copainRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_copain_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $copain = new Copain();
        $form = $this->createForm(CopainType::class, $copain);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($copain);
            $entityManager->flush();

            return $this->redirectToRoute('app_copain_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('copain/new.html.twig', [
            'copain' => $copain,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_copain_show', methods: ['GET'])]
    public function show(Copain $copain): Response
    {
        return $this->render('copain/show.html.twig', [
            'copain' => $copain,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_copain_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Copain $copain, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(CopainType::class, $copain);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_copain_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('copain/edit.html.twig', [
            'copain' => $copain,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_copain_delete', methods: ['POST'])]
    public function delete(Request $request, Copain $copain, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$copain->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($copain);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_copain_index', [], Response::HTTP_SEE_OTHER);
    }
}
