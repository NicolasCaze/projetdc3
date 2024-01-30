<?php

namespace App\Form;

use App\Entity\Reservations;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

class ReservationsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('quantity', NumberType::class, 
            ['attr' => ['placeholder' => "Nombre de personnes"], 
            'label' => false, 
            'required' => false,])
            ->add('requested_date', DateType::class, 
            [ 
                'widget' => 'single_text',
                'html5' => true,
                'format' => 'yyyy-MM-dd',
                'label' => false,   
                ])
                ->add('indoor', CheckboxType::class, [
                    'label' => 'À l\'intérieur',
                    'required' => false ,
                    
                ])
    
                ->add('outdoor', CheckboxType::class, [
                    'label' => 'À l\'extérieur',
                    'required' => false,
                ])
            ->add('comment', TextareaType::class, 
            ['attr' => ['placeholder' => "Ajouter un commentaire"], 
            'label' => false , 
            'required' => false, 
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Reservations::class,
        ]);
    }
}
