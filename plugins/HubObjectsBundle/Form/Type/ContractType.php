<?php

declare(strict_types=1);

namespace MauticPlugin\HubObjectsBundle\Form\Type;

use Mautic\CoreBundle\Form\Type\FormButtonsType;
use Mautic\LeadBundle\Entity\Lead;
use MauticPlugin\HubObjectsBundle\Entity\Contract;
use MauticPlugin\HubObjectsBundle\Entity\Product;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ContractType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add('name', TextType::class, [
            'label' => 'mautic.hubobjects.contract.name',
            'attr'  => ['class' => 'form-control'],
        ]);

        $builder->add('value', MoneyType::class, [
            'label'    => 'mautic.hubobjects.contract.value',
            'attr'     => ['class' => 'form-control'],
            'currency' => 'USD',
            'required' => false,
        ]);

        $builder->add('startDate', DateTimeType::class, [
            'label' => 'mautic.hubobjects.contract.start_date',
            'widget' => 'single_text',
            'attr'  => ['class' => 'form-control'],
            'required' => false,
        ]);

        $builder->add('endDate', DateTimeType::class, [
            'label' => 'mautic.hubobjects.contract.end_date',
            'widget' => 'single_text',
            'attr'  => ['class' => 'form-control'],
            'required' => false,
        ]);

        $builder->add('contact', EntityType::class, [
            'class' => Lead::class,
            'choice_label' => 'email',
            'label' => 'mautic.hubobjects.contract.contact',
            'attr' => ['class' => 'form-control'],
        ]);

        $builder->add('products', EntityType::class, [
            'class' => Product::class,
            'choice_label' => 'name',
            'multiple' => true,
            'expanded' => false, // Use false for a select box
            'label' => 'mautic.hubobjects.contract.products',
            'attr' => ['class' => 'form-control'],
        ]);

        $builder->add('buttons', FormButtonsType::class);

        if (!empty($options['action'])) {
            $builder->setAction($options['action']);
        }
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Contract::class,
        ]);
    }

    public function getBlockPrefix(): string
    {
        return 'hubobjects_contract';
    }
}
