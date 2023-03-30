<?php

namespace App\Form;

use App\Entity\Post;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Validator\Constraints\File;

class POstFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class,[
                'attr'  => [
                    'class' =>'form',
                ],
                'label'=> 'Titre du post',
                'constraints' => [
                    new NotBlank([
                        "message" =>"Champs obligatoire"
                    ]),
                    new Length([
                        'min'=>3,
                        'max'=>20,
                        'minMessage'=>"Minimum {{ limit }}  caractéres" ,
                        'maxMessage'=>"Maximum {{ limit }} caractéres",
                    ])
                ]
            ])
            ->add('content', TextareaType::class,[
                'attr'  => [
                    'class' =>'form',
                ],
                'label'=> 'Contenu du post',
                'constraints' => [
                    new NotBlank([
                        "message" =>"Champs obligatoire"
                    ]),
                    new Length([
                        'min'=>3,
                        'max'=>20,
                        'minMessage'=>"Minimum {{ limit }}  caractéres" ,
                        'maxMessage'=>"Maximum {{ limit }} caractéres",
                    ])
                ]
            ])          
            ->add('url_img',FileType::class,[
                'mapped' => false,
                'attr'  => [
                    'class' =>'form',
                ],
                'label'=> 'Image du post',
                'constraints'=> [
                    //new NotBlank([
                     //   "message" =>"Champs obligatoire"
                   // ]),
                    new File([
                        'maxSize' => '3M',
                        'maxSizeMessage' =>'Votre fichier ne doit dépasser {{ limit }} ',
                        'mimeTypes' => [
                            'image/jpeg', 'image/avif', 'image/png'
                        ],
                        'mimeTypesMessage' =>'Votre fichier doit être de type {{ types }} ',
                    ])
                ]
            ] )
            //->add('created_at')
            //->add('updated_at')
            ->add('author',TextType::class,[
                'attr'  => [
                    'class' =>'form',
                ],
                'label'=> 'Auteur du post',
                'constraints' => [
                    new NotBlank([
                        "message" =>"Champs obligatoire"
                    ]),
                    new Length([
                        'min'=>3,
                        'max'=>20,
                        'minMessage'=>"Minimum {{ limit }}  caractéres" ,
                        'maxMessage'=>"Maximum {{ limit }} caractéres",
                    ])
                ]
            ] )
            ->add('category',TextType::class,[
                'attr'  => [
                    'class' =>'form',
                ],
                'label'=> 'Catégorie du post',
                'constraints' => [
                    new NotBlank([
                        "message" =>"Champs obligatoire"
                    ]),
                    new Length([
                        'min'=>3,
                        'max'=>20,
                        'minMessage'=>"Minimum {{ limit }}  caractéres" ,
                        'maxMessage'=>"Maximum {{ limit }} caractéres",
                    ])
                ]
            ] )
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Post::class,
        ]);
    }
}
