<?php

namespace App\Form;

use App\Entity\Label;
use App\Entity\Note;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class NoteType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'title', 
                TextType::class, [
                    'required' => false,
                    'attr' => [
                        'label' => false,
                        'placeholder' => 'Titre'
                    ]    
                ])
            
            ->add(
                'content', 
                TextareaType::class, [
                    'required' => false,
                    'attr' => [
                        'placeholder' => "Créez une note..."
                    ]
                ])
            
            ->add(
                'picture', 
                UrlType::class, [
                    'required' => false,
                    'label_format' => false,
                    'attr' => [
                        'placeholder' => "Votre URL ici"
                    ]
                ])
            
            ->add(
                'dueDate', 
                DateType::class, [
                    'required' => false,
                    'widget' => 'single_text',
                    'attr' => [
                        'placeholder' => "Créez une alerte"
                    ]
                ])
            
            ->add(
                'label', 
                EntityType::class, [
                    'required' => false,
                    'class' => Label::class,
                    'choice_label' => 'name',
                    'attr' => [
                        'placeholder' => "Ajoutez un label à votre note"
                    ]
                ])

            ->add(
                'save',
                SubmitType::class
            )
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Note::class,
        ]);
    }
}
