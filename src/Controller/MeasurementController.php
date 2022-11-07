<?php

namespace App\Controller;

use App\Entity\Measurement;
use App\Form\MeasurementType;
use App\Repository\MeasurementRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MeasurementController extends AbstractController
{
    #[IsGranted('ROLE_MEASUREMENT_INDEX')]
    public function index(MeasurementRepository $measurementRepository): Response
    {
        return $this->render('measurement/index.html.twig', [
            'measurements' => $measurementRepository->findAll(),
        ]);
    }

    #[IsGranted('ROLE_MEASUREMENT_CREATE')]
    public function new(Request $request, MeasurementRepository $measurementRepository): Response
    {
        $measurement = new Measurement();
        $form = $this->createForm(MeasurementType::class, $measurement, ['validation_groups' => ['new']]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $measurementRepository->save($measurement, true);

            return $this->redirectToRoute('app_measurement_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('measurement/new.html.twig', [
            'measurement' => $measurement,
            'form' => $form,
        ]);
    }

    #[IsGranted('ROLE_MEASUREMENT_SHOW')]
    public function show(Measurement $measurement): Response
    {
        return $this->render('measurement/show.html.twig', [
            'measurement' => $measurement,
        ]);
    }

    #[IsGranted('ROLE_MEASUREMENT_EDIT')]
    public function edit(Request $request, Measurement $measurement, MeasurementRepository $measurementRepository): Response
    {
        $form = $this->createForm(MeasurementType::class, $measurement, ['validation_groups' => ['new']]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $measurementRepository->save($measurement, true);

            return $this->redirectToRoute('app_measurement_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('measurement/edit.html.twig', [
            'measurement' => $measurement,
            'form' => $form,
        ]);
    }

    #[IsGranted('ROLE_MEASUREMENT_DELETE')]
    public function delete(Request $request, Measurement $measurement, MeasurementRepository $measurementRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$measurement->getId(), $request->request->get('_token'))) {
            $measurementRepository->remove($measurement, true);
        }

        return $this->redirectToRoute('app_measurement_index', [], Response::HTTP_SEE_OTHER);
    }
}
