<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\Note;
use App\Entity\Label;
use App\Entity\Status;
use App\Repository\StatusRepository;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class NoteFixtures extends Fixture
{
    public const NOTE_REFERENCE = 'note';

    public function load(ObjectManager $manager)
    {
        $faker = Factory::create('fr_FR');

        // création des statuts
        $status = [];

        $statusOn = new Status();
        $statusOn->setName('On');
        $manager->persist($statusOn);
        $status[] = $statusOn;

        $statusArchived = new Status();
        $statusArchived->setName('Archived');
        $manager->persist($statusArchived);
        $status[] = $statusArchived;

        $statusTrash = new Status();
        $statusTrash->setName('Trash');
        $manager->persist($statusTrash);
        $status[] = $statusTrash;

        // création de 3 labels
        $labels = [];
        for ($i = 0; $i < 3; $i++) {
            $label = new Label();
            $label->setName($faker->word());
            $label->setColor($faker->hexColor);
            $manager->persist($label);
            $labels[] = $label;
        }
        
        // création des notes
        for ($j=0; $j < 10; $j++) { 
            $note = new Note();
            if (mt_rand(0,1)) {
                $note->setContent($faker->paragraph(3, true));
            } else {
                $note->setPicture($faker->imageUrl('640', '480', null, true, null, false));
            }
            if (mt_rand(0,1)) {
                $note->setTitle($faker->word());
            }
            if (mt_rand(0,1)) {
                $note->setDueDate($faker->dateTimeBetween('now', '+2 years', null));
            }

            // attribution d'un label
            $label = $labels[mt_rand(0, count($labels) -1)];
            $note->setLabel($label);

            // attribution d'un statut
            $aStatus = $status[mt_rand(0, count($status) -1)];
            $note->setStatus($aStatus);

            $manager->persist($note);
        }

        $manager->flush();
    }
}

