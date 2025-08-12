<?php

declare(strict_types=1);

namespace MauticPlugin\HubObjectsBundle\Form\Type;

use Mautic\CoreBundle\Form\Type\FormButtonsType;
use MauticPlugin\HubObjectsBundle\Entity\Product;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProductType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add('name', TextType::class, [
            'label' => 'mautic.hubobjects.product.name',
            'attr'  => ['class' => 'form-control'],
        ]);

        $builder->add('description', TextareaType::class, [
            'label' => 'mautic.hubobjects.product.description',
            'attr'  => ['class' => 'form-control'],
            'required' => false,
        ]);

        $builder->add('price', MoneyType::class, [
            'label'    => 'mautic.hubobjects.product.price',
            'attr'     => ['class' => 'form-control'],
            'currency' => 'USD',
            'required' => false,
        ]);

        $builder->add('buttons', FormButtonsType::class);

        if (!empty($options['action'])) {
            $builder->setAction($options['action']);
        }
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Product::class,
        ]);
    }

    public function getBlockPrefix(): string
    {
        return 'hubobjects_product';
    }
}
