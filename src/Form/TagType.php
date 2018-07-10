<?php

namespace App\Form;

use App\Entity\Tag;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class TagType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class);

        $builder->get('tags')
            ->addModelTransformer(new CallbackTransformer(
                function ($tagsAsArray) {
                    // transform the array to a string
                    if($tagsAsArray['elements'] == null) {
                        return '';
                    }
                    return implode(', ', $tagsAsArray);
                },
                function ($tagsAsString) {
                    $product = new Product();
                    foreach (explode(', ', $tagsAsString) as $key => $value) {
                        $tag = new Tag();
                        $tag->setName($value);
                        $this->entityManager->persist($tag);
                        $product->addTag($tag);
                    }
                    // transform the string back to an array
                    return new PersistentCollection($this->entityManager, Tag::class, $product->getTags());
                    
                }
            ));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Tag::class,
        ]);
    }

    public function getName()
    {
        return 'tag';
    }

    public function getParent()
    {
        return TextType::class;
    }
}
