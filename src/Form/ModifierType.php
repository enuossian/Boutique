<?php

namespace App\Form;

use App\Entity\User;
use PhpParser\Parser\Multiple;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class ModifierType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            //->add('email')
            ->add('roles', ChoiceType::class,[
                'choices' => [
                    'Admin' => 'admin', 
                    'User' => 'user'
                ],
                'multiple' => true,
                'expanded' => true
            ])
            //->add('password')
            //->add('nom')
            //->add('prenom')
            //->add('civilite')
            //->add('date_enregistrement')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
