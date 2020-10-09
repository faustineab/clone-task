<?php

namespace App\Controller;

use App\Entity\Label;
use App\Form\LabelType;
use App\Repository\LabelRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class LabelController extends AbstractController
{
    /**
     * @Route("/label", name="label")
     */
    public function index(LabelRepository $labelRepository)
    {
        // récupération des labels
        $labels = $labelRepository->findAll();

        return $this->render('label/index.html.twig', [
            'labels' => $labels,
        ]);
    }

    public function new(EntityManagerInterface $manager, Request $request)
    {
        // Formulaire de création de label
        $label = new Label();
        $labelForm = $this->createForm(LabelType::class, $label);
        $labelForm->handleRequest($request);
        if ($labelForm->isSubmitted() && $labelForm->isValid()) {
            $manager->persist($label);
            $manager->flush();

            return $this->redirectToRoute('note_index');
        }

        return $this->render('note/_new_label.html.twig', [
            'label_form' => $labelForm->createView()
        ]);
    }
}
