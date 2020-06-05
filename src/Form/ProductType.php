<?php

namespace App\Form;

use App\Entity\Product;
use App\Entity\Tag;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Image;
use Tetranz\Select2EntityBundle\Form\Type\Select2EntityType;

class ProductType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Nom'
            ])
            ->add('imageFile', FileType::class, [
                'required' => false,
                //'constraints' => [
                //  new Image()
                //]
            ])
            ->add('price', MoneyType::class, [
                'currency' => 'EUR',
                'divisor' => 100,
                'label' => 'Prix'
            ])
            ->add('description', TextareaType::class, [
                'label' => 'Description',
                'attr' => [
                    'placeholder' => 'Saisir votre description'
                ]
            ])
            ->add('tags', Select2EntityType::class, [
                'class' => Tag::class,
                'multiple' => true,
                'remote_route' => 'tag',
                'text_property' => 'name',
                'cache' => false,
                'language' => 'fr',
                'placeholder' => 'Sélectionner un ou plusieurs tags',
                'allow_add' => [
                    'enabled' => true,
                    'tag_separators' => '[","]',
                ]
            ])
        ;

    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Product::class,
        ]);
    }
}
