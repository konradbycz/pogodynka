<?php

namespace App\Controller;

use App\Entity\Measurement;
use App\Form\MeasurementType;
use App\Repository\MeasurementRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MeasurementController extends AbstractController
{
    public function index(MeasurementRepository $measurementRepository): Response
    {
        $this->denyAccessUnlessGranted('ROLE_MEASUREMENT_INDEX');
        return $this->render('measurement/index.html.twig', [
            'measurements' => $measurementRepository->findAll(),
        ]);
    }

    public function new(Request $request, MeasurementRepository $measurementRepository): Response
    {
        $this->denyAccessUnlessGranted('ROLE_MEASUREMENT_CREATE');
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

    public function show(Measurement $measurement): Response
    {
        $this->denyAccessUnlessGranted('ROLE_MEASUREMENT_SHOW');
        return $this->render('measurement/show.html.twig', [
            'measurement' => $measurement,
        ]);
    }

    public function edit(Request $request, Measurement $measurement, MeasurementRepository $measurementRepository): Response
    {
        $this->denyAccessUnlessGranted('ROLE_MEASUREMENT_EDIT');
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

    public function delete(Request $request, Measurement $measurement, MeasurementRepository $measurementRepository): Response
    {
        $this->denyAccessUnlessGranted('ROLE_MEASUREMENT_DELETE');
        if ($this->isCsrfTokenValid('delete'.$measurement->getId(), $request->request->get('_token'))) {
            $measurementRepository->remove($measurement, true);
        }

        return $this->redirectToRoute('app_measurement_index', [], Response::HTTP_SEE_OTHER);
    }
}
