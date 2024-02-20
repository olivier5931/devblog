<?php

namespace App\Form;

use App\Entity\Contact;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class ContactType extends AbstractType
{
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		$builder
			->add('username', TextType::class, ['label' => 'Identifiant'])
			->add('email', TextType::class, ['label' => 'Adresse-email'])
			->add('subject', TextType::class, ['label' => 'Sujet'])
			->add('body', TextareaType::class, ['label' => 'Message'])
		;
	}

	public function setDefaultOptions(OptionsResolver $resolver)
	{
		$resolver->setDefaults([
			'data_class' => Contact::class,
		]);
	}

}
