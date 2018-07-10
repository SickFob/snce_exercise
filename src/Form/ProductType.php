<?php

namespace App\Form;

use App\Entity\Product;
use App\Entity\Tag;

use App\Repository\TagRepository;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;

use Symfony\Component\Form\CallbackTransformer;
use App\Form\DataTransformer\TagsToEntityTransformer;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\PersistentCollection;
use Doctrine\Common\Persistence\ObjectManager;

use Symfony\Component\HttpFoundation\File\File;


class ProductType extends AbstractType
{
    private $transformer;

    public function __construct(EntityManagerInterface $entityManager, ObjectManager $om)
    {
        $this->entityManager = $entityManager;
        $this->om = $om;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, [
                'required' => true,
            ])
            ->add('description', TextareaType::class, [
                'required' => false
            ])
            ->add('image_path', FileType::class, [
                'label' => 'Image',
                'required' => false,
                'data_class' => null
            ])
            ->add('tags', TextType::class, [
                'required' => true
            ]);

            $builder->get('tags')
            ->addModelTransformer(new CallbackTransformer(
                function ($tagsAsArray) {
                    $listOfTags = [];
                    foreach ($tagsAsArray as $key => $value) {
                        array_push($listOfTags, $value);
                    }
                    return implode(',', $listOfTags);
                },
                function ($tagsAsString) {
                    $product = new Product();
                    foreach (explode(',', $tagsAsString) as $key => $value) {
                        $tag = new Tag();
                        $existentTag = $this->om
                            ->getRepository(Tag::class)
                            ->findOneBy(array('name' => $value));
                        if(!empty((array)$existentTag)) {
                            $tag = $existentTag;
                        } else {
                            $tag->setName($value);
                        }
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
            'data_class' => Product::class,
        ]);
    }
}
