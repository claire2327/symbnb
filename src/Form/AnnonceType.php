<?php

namespace App\Form;

use App\Entity\Ad;
use Faker\Provider\Text;
use Symfony\Component\Form\AbstractType;
use App\Form\ApplicationType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class AnnonceType extends ApplicationType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class, $this->getConfiguration("Titre", "Tapez un super titre pour votre annonce !"))
            ->add('slug', TextType::class, $this->getConfiguration("Adresse web", "Tapez l'adresse web (automatique)", [
                'required' => false]))
            ->add('coverImage', UrlType::class, $this->getConfiguration("URL de l'image principale", "Donnez l'adresse d'une image qui donne vraiment envie"))
            ->add('introduction', TextType::class, $this->getConfiguration("Introduction", "Donnez une description globale de l'annonce"))
            // pour éviter les sauts de ligne, on ne met pas de TextArea
            ->add('content', TextareaType::class, $this->getConfiguration("Description détaillée", "Tapez une description qui donne vraiment envie de venir chez vous"))
            ->add('rooms', IntegerType::class, $this->getConfiguration("Nombre de chambres", "Indiquez le nobre de chambres de votre logement"))
            ->add('price', MoneyType::class, $this->getConfiguration("Prix par nuit", "Indiquez le prix par nuit"))
           ->add('images', CollectionType::class,
               [
                   'entry_type' => ImageType::class,
                   'allow_add' => true,
                   'allow_delete' => true
                   // permet de préciser si on le droit d'ajouter de nouveaux éléments
                   // ça crée la notion de prototype - code html qui correspond à un sous-formulaire vierge
           ] )
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Ad::class,
        ]);
    }
}
