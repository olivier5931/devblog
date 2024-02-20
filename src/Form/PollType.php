<?php

namespace App\Form;

use App\Entity\Poll;
use App\Entity\Opinion;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

class PollType extends AbstractType
{
  public function buildForm(FormBuilderInterface $builder, array $options)
  {
    $builder
      ->add('name', TextType::class, ['label' => 'Nom'])
      ->add('category', ChoiceType::class, [
        'label' => 'Catégorie',
        'choices'  => [
          'novice' => 'novice',
          'programming' => 'languages prog.',
          'framework' => 'framework',
          'mixed' => 'duo technologies'
        ],
      ])
      ->add('published', CheckboxType::class, ['label' => 'Publié'])
      ->add('closed', CheckboxType::class, ['label' => 'Clos'])
      ->add('opinions', CollectionType::class, [
        'entry_type' => OpinionType::class,
        'allow_add' => true,
        'allow_delete' => true,
        'by_reference' => false
      ]);
  }

  public function configureOptions(OptionsResolver $resolver)
  {
    $resolver->setDefaults([
      'opinion_form' => 'App\Entity\Poll',
      'cascade_validation' => true
    ]);
  }
}
