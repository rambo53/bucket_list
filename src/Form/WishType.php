<?php

namespace App\Form;

use App\Entity\Category;
use App\Entity\Wish;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class WishType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title',TextType::class,[
                'label'=>'Titre :',
                
            ])
            ->add('description',TextareaType::class,[
                'label'=>'Description :',
                'required'=>false
            ])
            ->add('auteur',TextType::class,[
                'label'=>'Auteur :',
            ])
            ->add('categories',EntityType::class,[			
                'class'=>Category::class,
                'label'=>'Catégorie :',
		        'choice_label'=>'name',				
		        'placeholder'=>'Choisir une catégorie.'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Wish::class,
        ]);
    }
}
