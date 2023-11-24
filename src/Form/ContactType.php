<?php

namespace App\Form;

use App\Entity\Contact;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Unique;

class ContactType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('subject' , TextType::class , [
                'constraints' => [
                    new NotBlank([
                        'message' => 'Sebject is required' ,
                    ]),
                    new Length([
                        'min' => 5,
                        'max' => 200,
                        'minMessage' => 'Sebject is too short',
                        'maxMessage' => 'Sebject is too long',
                    ])
                ],
            ])
            ->add('email' , EmailType::class,[
                'constraints' => [
                    new NotBlank([
                        'message' => 'Email is required' ,
                    ]),
                    new Email([
                        'message' => 'Email not valid'
                    ]),
                ]
            ])
            ->add('message', TextareaType::class, [
                'constraints' => [
                    new NotBlank([
                    'message' => 'Message is required' ,
                    ]),
                    new Length([
                        'min' => 10,
                        'minMessage' => 'Sebject is too short',
                    ])
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Contact::class,
        ]);
    }
}
