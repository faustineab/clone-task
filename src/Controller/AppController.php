<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class AppController extends AbstractController
{
    /**
     * @Route("/coucou", name="home")
     */
    public function home()
    {
        return $this->redirectToRoute('note_index');
    }
}
