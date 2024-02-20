<?php

namespace App\Form;

use App\Entity\Debugging;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class DebuggingType extends AbstractType
{
  public function buildForm(FormBuilderInterface $builder, array $options)
  {
    $builder
      ->add('title', TextType::class, ['label' => 'Titre'])
      ->add('category', ChoiceType::class, [
        'label' => 'Catégorie',
        'choices'  => [
          'programming' => 'languages prog.',
          'framework' => 'framework'
        ],
      ])
      ->add('solution', TextareaType::class, ['label' => 'Solution'])
      ->add('solutioneng', TextareaType::class, ['label' => 'Solution-ENG'])
    ;
  }

  public function configureOptions(OptionsResolver $resolver)
  {
    $resolver->setDefaults([
      'data_class' => Debugging::class,
    ]);
  }
}
