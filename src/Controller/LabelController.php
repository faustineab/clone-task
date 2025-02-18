<?php

namespace App\Controller;

use App\Entity\Label;
use App\Form\LabelType;
use App\Repository\NoteRepository;
use App\Repository\LabelRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class LabelController extends AbstractController
{
    /**
     * @Route("/label/new", name="label_new")
     *
     * @return Response
     */
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
            'form' => $labelForm->createView(),
        ]);
    }


    /**
     * Affiche les notes avec labels
     *
     * @Route("/label/{name}", name="note_label")
     *
     * @return Response
     */
    public function label(Label $label, NoteRepository $noteRepo)
    {
        // récupération des notes par label
        $notes = $noteRepo->findBy(
            ['label' => $label, 'status' => 1],
            ['createdAt' => 'DESC']
        );

        return $this->render('note/index.html.twig', [
            'notes' => $notes
        ]);
    }

}
