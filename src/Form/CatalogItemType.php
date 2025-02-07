<?php

namespace App\Form;

use App\Entity\CatalogItem;
use App\Entity\Manufacturer;
use App\Entity\Country;
use App\Entity\CatalogSection;
use App\Enum\AdvantageType;
use App\Enum\CatalogType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class CatalogItemType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('description', TextareaType::class, [
                'label' => 'Описание',
                'required' => true,
            ])
            ->add('type', ChoiceType::class, [
                'label' => 'Тип',
                'choices' => CatalogType::choices(),
                'expanded' => true,
                'multiple' => false,
                'required' => true,
            ])
            ->add('advantages', ChoiceType::class, [
                'label' => 'Преимущества',
                'choices' => AdvantageType::choices(),
                'expanded' => true,
                'multiple' => true,
            ])
            ->add('photo', FileType::class, [
                'label' => 'Фото',
                'required' => false,
                'mapped' => false,
            ])
            ->add('manufacturers', EntityType::class, [
                'class' => Manufacturer::class,
                'choice_label' => 'name',
                'label' => 'Производители',
                'required' => false,
                'multiple' => true,
                'expanded' => true,
            ])
            ->add('country', EntityType::class, [
                'class' => Country::class,
                'choice_label' => 'name',
                'label' => 'Страна',
                'required' => false,
            ])
            ->add('section', EntityType::class, [
                'class' => CatalogSection::class,
                'choice_label' => 'name',
                'label' => 'Раздел',
                'required' => true,
            ])
            ->add('publishedAt', DateTimeType::class, [
                'label' => 'Дата публикации',
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
