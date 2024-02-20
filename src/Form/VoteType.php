<?php

namespace App\Form;

use App\Entity\Poll;
use App\Entity\Opinion;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Validator\Constraints\Collection;
use Symfony\Component\Validator\Constraints\NotNull;
use Symfony\Component\Validator\Constraints\Choice;

class VoteType extends AbstractType
{
  public function buildForm(FormBuilderInterface $builder, array $options)
  {
    $builder->add('opinions', ChoiceType::class, [
        'multiple' => false,
        'expanded' => true,
        'choices' => $options['opinionsChoices'],
        'constraints' => [
          new Choice(array('choices' => array_keys($options['opinionsChoices'])))
        ]
			])
    ;
  }

  public function getName()
  {
    return "vote";
  }

  public function configureOptions(OptionsResolver $resolver)
  {
    $resolver->setRequired([('opinionsChoices')]);
  }

}
