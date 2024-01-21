<?php

namespace App\Form;

use App\Entity\Reservations;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ReservationsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('quantity', NumberType::class, 
            ['attr' => ['placeholder' => "Nombre de personnes"], 
            'label' => false, ] )
            ->add('requested_date', DateTimeType::class, 
            ['attr' => ['placeholder' => "Date de la réservation"], 
            'label' => false,
            'widget' => 'choice',
                'format' => 'dd/MM/yyyy', // Utilise le format "jour/mois/année"
                'html5' => false,
                'years' => range(date('Y'), date('Y') + 5), // Ajoute 5 années à partir de l'année actuelle
                'months' => range(1, 12), // Autorise tous les mois
                'days' => range(1, 31), // Autorise tous les jours du mois
                'hours' => range(11, 14),
                'minutes' => [0, 15, 30, 45], // Autorise seulement les minutes 00, 15, 30, 45
                'placeholder' => [
                    'hour' => 'Heure', 'minute' => 'Minute',
                    'day' => 'Jour', 'month' => 'Mois', 'year' => 'Année',
                ],
                
                ])
            ->add('comment', TextareaType::class, 
            ['attr' => ['placeholder' => "Ajouter un commentaire"], 
            'label' => false, ] )
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Reservations::class,
        ]);
    }
}
