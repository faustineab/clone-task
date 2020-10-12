<?php

namespace App\Controller;

use App\Entity\Note;
use App\Entity\Label;
use App\Form\NewNoteType;
use App\Service\MessageGenerator;
use App\Repository\NoteRepository;
use App\Repository\LabelRepository;
use App\Repository\StatusRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class NoteController extends AbstractController
{
    /**
     * Affiche les notes
     *
     * @Route("/note", name="note_index")
     *
     * @return Response
     */
    public function index(NoteRepository $noteRepo)
    {
        return $this->render('note/index.html.twig', [
            'notes' => $noteRepo->findByStatus(1),
        ]);
    }

    /**
     * crée une nouvelle note
     *
     * @Route("/note/new", name="note_new", methods={"GET", "POST"})
     * 
     * @param Request $request
     * @param StatusRepository $statusRepository
     * @param EntityManagerInterface $entityManager
     * @return Response
     */
    public function new(Request $request, StatusRepository $statusRepository, EntityManagerInterface $entityManager)
    {
        $note = new Note();
        $status = $statusRepository->findOneBy(['name' => 'On']);

        $form = $this->createForm(NewNoteType::class, $note);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $note->setStatus($status);
            $note->setCreatedAt(new \DateTime());
            
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($note);
            $entityManager->flush();
        
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
     * Affiche les notes archivées
     *
     * @Route("/archive", name="archive")
     *
     * @return Response
     */
    public function archive(NoteRepository $noteRepo)
    {
        // récupération des notes par statut
        $notes = $noteRepo->findByStatus(2);
        
        return $this->render('note/index.html.twig', [
            'notes' => $notes,
        ]);
    }
    
    /**
     * Supprime une note
     * 
     * @Route("/note/{id}/delete", name="note_delete")
     */
    public function delete(Note $note, StatusRepository $statusRepository, EntityManagerInterface $manager)
    {
        $note->setStatus($statusRepository->findOneBy(['name' => 'Trash']));
        $manager->persist($note);
        $manager->flush();

        return $this->redirectToRoute('trash');
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
        // récupération des notes par statut
        $notes = $noteRepo->findByStatus(3);
        
        return $this->render('note/index.html.twig', [
            'notes' => $notes,
        ]);
    }
}