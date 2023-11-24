<?php

namespace App\Form;

use App\Entity\Product;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Image;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Positive;
use Symfony\Component\Validator\Constraints\PositiveOrZero;

class ProductType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        // dd($options['data']->getId());
        $builder
            ->add('designation' , TextType::class , [
                'constraints' => [
                    new NotBlank([
                        'message' => 'Designation is required' ,
                    ]),
                    new Length([
                        'min' => 2,
                        'max' => 255,
                        'minMessage' => 'Product desigation is too short',
                        'maxMessage' => 'Product desigation is too long',
                    ])
                ],
            ])
            ->add('price' , MoneyType::class, [
                'constraints' => [
                    new NotBlank([
                        'message' => 'Price is required' ,
                    ]),
                    new Positive([
                        'message' => 'Price must be positive and less than'
                    ]),
                    new Length([
                        'max' => 3,
                        'maxMessage' => 'Product price is too big',
                    ])
                ],
            ])
            ->add('description', TextareaType::class, [
                'constraints' => [
                    new NotBlank([
                    'message' => 'Description is required' ,
                    ]),
                ],
            ])
            ->add('quantity', IntegerType::class, [
                'constraints' => [
                    new NotBlank([
                    'message' => 'Quantity is required' ,
                    ]),
                    new PositiveOrZero([
                            'message' => 'Quantity must be positive of zero'
                        ])
                ],
            ])
            ->add('image' , FileType::class, [
                'data_class' =>null,
                // si le prduit est jouter , ajout de cotaints sur le chmaps de selection de fichier // si le perduit est modifie , pas de cntairntes
                    'constraints' => $options['data']->getId() ? [] : [
                        new NotBlank([
                            'message' => 'Image is required' ,
                        ]),
                        new Image([
                            'mimeTypesMessage' => 'File formats is not allowed',
                            'mimeTypes' => ['imag/jpeg' , 'image/png' , 'image/gif']
                        ])
                    ],
                ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Product::class,
        ]);
    }
}
