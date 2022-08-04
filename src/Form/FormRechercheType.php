<?php

namespace App\Form;

use App\Entity\City;
use App\Entity\Country;
use App\Entity\Direction;
use App\Repository\CityRepository;
use App\Repository\CountryRepository;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FormRechercheType extends AbstractType
{
    private $cityRepository;
    public function  __construct(CityRepository $cityRepository){
$this->cityRepository=$cityRepository;

    }


    
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('direction', EntityType::class, [
            'class' => Direction::class,
            'choice_label' => 'name',
            'placeholder' => 'Choose a country',
            'query_builder' => fn (Directio $countryRepository) =>
            $countryRepository->findAllOrderedByAscNameQueryBuilder()
        ]) 
     ;

    $formModifier = function (FormInterface $form, Country $country = null) {
       
        $cities = $country === null ? [] : $this->cityRepository->findByCountryOrderedByAscName($country);
       
        $form->add('city', EntityType::class, [
            'class' => City::class,
            'choice_label' => 'name',
            'disabled' => $country === null,
            'placeholder' => 'Choose a city',
            'choices' => $cities
        ]);
    };
     

    $builder->addEventListener(
        FormEvents::PRE_SET_DATA,
        function (FormEvent $event) use ($formModifier) {
            $data = $event->getData();
            
            $formModifier($event->getForm(), $data->getCountry());
        }
    );


    $builder->get('country')->addEventListener(
        FormEvents::POST_SUBMIT,
        function (FormEvent $event) use ($formModifier) {
            $country = $event->getForm()->getData();
  
            $formModifier($event->getForm()->getParent(), $country);
        }
    );
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
