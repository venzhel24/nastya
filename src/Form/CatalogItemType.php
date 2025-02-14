<?php

// src/Form/CatalogItemType.php

namespace App\Form;

use App\Entity\CatalogItem;
use App\Entity\Manufacturer;
use App\Entity\Country;
use App\Entity\CatalogSection;
use App\Enum\AdvantageType;
use App\Enum\CatalogType;
use App\Service\PropertyNameService;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Validator\Constraints\File;

class CatalogItemType extends AbstractType
{
    private PropertyNameService $propertyNameService;

    public function __construct(PropertyNameService $propertyNameService)
    {
        $this->propertyNameService = $propertyNameService;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextareaType::class, [
                'label' => $this->propertyNameService->getDisplayName('name'),
                'required' => true,
            ])
            ->add('description', TextareaType::class, [
                'label' => $this->propertyNameService->getDisplayName('description'),
                'required' => true,
            ])
            ->add('type', ChoiceType::class, [
                'label' => $this->propertyNameService->getDisplayName('type'),
                'choices' => CatalogType::cases(),
                'choice_label' => fn(CatalogType $type) => $type->getLabel(),
                'choice_value' => fn(?CatalogType $type) => $type?->value,
                'expanded' => true,
                'multiple' => false,
                'required' => true,
            ])
            ->add('advantages', ChoiceType::class, [
                'label' => $this->propertyNameService->getDisplayName('advantages'),
                'choices' => AdvantageType::choices(),
                'expanded' => true,
                'multiple' => true,
            ])
            ->add('photo', FileType::class, [
                'label' => $this->propertyNameService->getDisplayName('photo'),
                'mapped' => false,
                'required' => false,
                'constraints' => [
                    new File([
                        'maxSize' => '5M',
                        'mimeTypes' => ['image/jpeg', 'image/png'],
                        'mimeTypesMessage' => 'Допустимы только JPG и PNG файлы',
                    ]),
                ],
            ])
            ->add('manufacturers', EntityType::class, [
                'class' => Manufacturer::class,
                'choice_label' => 'name',
                'label' => $this->propertyNameService->getDisplayName('manufacturers'),
                'required' => false,
                'multiple' => true,
                'expanded' => true,
            ])
            ->add('country', EntityType::class, [
                'class' => Country::class,
                'choice_label' => 'name',
                'label' => $this->propertyNameService->getDisplayName('country'),
                'required' => false,
            ])
            ->add('section', EntityType::class, [
                'class' => CatalogSection::class,
                'choice_label' => 'name',
                'label' => $this->propertyNameService->getDisplayName('section'),
                'required' => true,
            ])
            ->add('publishedAt', DateTimeType::class, [
                'label' => $this->propertyNameService->getDisplayName('publishedAt'),
                'required' => true,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => CatalogItem::class,
        ]);
    }
}

