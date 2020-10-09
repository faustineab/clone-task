<?php

namespace App\Controller;

use App\Entity\Note;
use App\Entity\Label;
use App\Form\NoteType;
use App\Repository\NoteRepository;
use App\Repository\StatusRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/note")
 */
class NoteController extends AbstractController
{
    /**
     * Affiche les notes
     *
     * @Route("/", name="note_index")
     *
     * @return Response
     */
    public function index(NoteRepository $noteRepo)
    {
        // récupération des notes par date de création
        $notes = $noteRepo->findBy(['status' => 1], ['createdAt' => 'DESC']);

        return $this->render('note/index.html.twig', [
            'notes' => $notes,
        ]);
    }

    /**
     * crée une nouvelle note
     * 
     * @Route=("/new", name="note_new")
     *
     * @return Response
     */
    public function new(EntityManagerInterface $manager, Request $request, StatusRepository $statusRepo)
    {
        // Formulaire de création de note
        $note = new Note();
        
        $status = $statusRepo->findOneBy(['name' => 'On']);

        $form = $this->createForm(NoteType::class, $note);
        
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            dump($status);
            $note->setStatus($status);
            $note->setCreatedAt(new \DateTime);
            $manager->persist($note);
            $manager->flush();

            return $this->redirectToRoute('note_index');
        } 

        return $this->render('note/_new_note.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * Affiche les notes avec reminders
     *
     * @Route("/reminder", name="reminder")
     *
     * @return Response
     */
    public function reminder(NoteRepository $noteRepo)
    {
        // récupération des notes par date "reminder"
        $notes = $noteRepo->findAllByDueDate();

        return $this->render('note/index.html.twig', [
            'notes' => $notes,
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
            array('label' => $label, 'status' => 1),
            array('createdAt' => 'DESC')
        );

        return $this->render('note/index.html.twig', [
            'notes' => $notes,
        ]);
    }

    /**
     * Affiche les notes archivées
     *
     * @Route("/archive", name="archive")
     *
     * @return Response
     */
    public function archive(NoteRepository $noteRepo)
    {
        // récupération des notes par label
        $notes = $noteRepo->findBy(['status' => 2]);

        return $this->render('note/index.html.twig', [
            'notes' => $notes,
        ]);
    }

    /**
     * Affiche les notes archivées
     *
     * @Route("/trash", name="trash")
     *
     * @return Response
     */
    public function trash(NoteRepository $noteRepo)
    {
        // récupération des notes par label
        $notes = $noteRepo->findBy(['status' => 3]);

        return $this->render('note/index.html.twig', [
            'notes' => $notes,
        ]);
    }

   
}