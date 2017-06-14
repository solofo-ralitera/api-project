<?php
namespace AppBundle\Form;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class Attachment extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('type', EntityType::class, [
            'class' => 'AppBundle:AttachmentType'
        ]);
        $builder->add('author',EntityType::class, [
            'class' => 'AppBundle:User'
        ]);
        $builder->add('name', TextType::class);
        $builder->add('parameters', TextType::class);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => 'AppBundle\Entity\Attachment',
            'csrf_protection' => false
        ]);
    }
}