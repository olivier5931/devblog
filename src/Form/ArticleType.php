<?php

namespace App\Form;

use App\Entity\Article;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class ArticleType extends AbstractType
{
  public function buildForm(FormBuilderInterface $builder, array $options)
  {
    $builder
      ->add('image', TextType::class, ['label' => 'Image-URL'])
      ->add('title', TextType::class, ['label' => 'Titre'])
      ->add('titleeng', TextType::class, ['label' => 'Titre-ENG'])
      ->add('category', ChoiceType::class, [
        'label' => 'Catégorie',
        'choices'  => [
          'novice' => 'novice',
          'programming' => 'languages prog.',
          'framework' => 'framework',
          'mixed' => 'duo technologies'
        ],
      ])
      ->add('content', TextType::class, ['label' => 'Introduction'])
      ->add('contenteng', TextType::class, ['label' => 'Introduction-ENG'])
      ->add('fullcontent', TextareaType::class, ['label' => 'Contenu'])
      ->add('fullcontenteng', TextareaType::class, ['label' => 'Content-ENG'])
    ;
  }

  public function configureOptions(OptionsResolver $resolver)
  {
    $resolver->setDefaults([
      'data_class' => Article::class,
    ]);
  }
}
