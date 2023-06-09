<?php

namespace App\Controller;

use App\Entity\Day;
use App\Form\DayType;
use App\Repository\DayRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/day')]
class DayController extends AbstractController
{
    #[Route('/', name: 'app_day_index', methods: ['GET'])]
    public function index(DayRepository $dayRepository): Response
    {
        return $this->render('day/index.html.twig', [
            'days' => $dayRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_day_new', methods: ['GET', 'POST'])]
    public function new(Request $request, DayRepository $dayRepository): Response
    {
        $day = new Day();
        $form = $this->createForm(DayType::class, $day);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $dayRepository->save($day, true);

            return $this->redirectToRoute('app_day_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('day/new.html.twig', [
            'day' => $day,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_day_show', methods: ['GET'])]
    public function show(Day $day): Response
    {
        return $this->render('day/show.html.twig', [
            'day' => $day,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_day_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Day $day, DayRepository $dayRepository): Response
    {
        $form = $this->createForm(DayType::class, $day);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $dayRepository->save($day, true);

            return $this->redirectToRoute('app_day_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('day/edit.html.twig', [
            'day' => $day,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_day_delete', methods: ['POST'])]
    public function delete(Request $request, Day $day, DayRepository $dayRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$day->getId(), $request->request->get('_token'))) {
            $dayRepository->remove($day, true);
        }

        return $this->redirectToRoute('app_day_index', [], Response::HTTP_SEE_OTHER);
    }
}
