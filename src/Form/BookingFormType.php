<?php

namespace App\Form;

use App\Entity\Booking;
use App\Entity\Service;
use App\Entity\ServiceCategory;
use App\Entity\Timeslot;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BookingFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('serviceCategory', EntityType::class, [
                'class' => ServiceCategory::class,
                'mapped' => false,
                'choice_label' => 'name',
            ])
            ->add('service', EntityType::class, [
                'class' => Service::class,
                'choice_label' => 'name',
            ])
            ->add('timeslot', EntityType::class, [
                'class' => Timeslot::class,
                'choice_label' => 'time',
            ])
            ->add('clientName')
            ->add('clientPhone')
            ->add('price', NumberType::class, [
                'disabled' => true
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Booking::class,
        ]);
    }
}
