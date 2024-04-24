<?php

namespace App\Form;

use App\Entity\User;
use App\Entity\Pictures;
use Doctrine\ORM\QueryBuilder;
use App\Repository\PicturesRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class UserType extends AbstractType
{

    private $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $user = $this->security->getUser();

        $builder
            ->add('pictures', EntityType::class, [
                'class' => Pictures::class,
                'query_builder' => function (PicturesRepository $pr) use ($user): QueryBuilder {
                    return $pr->createQueryBuilder('p')
                        ->andWhere('p.User = :user')
                        ->setParameter('user', $user)
                        ->orderBy('p.id', 'ASC');
                },
                'choice_label' => 'title',
                'multiple' => true,
                'autocomplete' => true,
            ])
            ->add('save', SubmitType::class, [
                'label' => 'Editer',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class
        ]);
    }
}