<?php

namespace App\Form;

use App\Entity\Post;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class POstFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class,[
                'attr'  => [
                    'class' =>'form',
                ],
                'required'=> false 
            ])
            ->add('content', TextareaType::class,[
                'attr'  => [
                    'class' =>'form',
                ]
            ])          
            ->add('url_img',FileType::class,[
                'attr'  => [
                    'class' =>'form',
                ]
            ] )
            //->add('created_at')
            //->add('updated_at')
            ->add('author',TextType::class,[
                'attr'  => [
                    'class' =>'form',
                ]
            ] )
            ->add('category',TextType::class,[
                'attr'  => [
                    'class' =>'form',
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
