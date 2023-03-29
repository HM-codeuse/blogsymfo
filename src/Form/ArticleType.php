<?php

namespace App\Form;

use App\Entity\Article;
use Doctrine\DBAL\Types\TextType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class ArticleType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {

        //Titre
        $builder->add('Titre', TextType::class, [
                'label'=>'Titre',
                'constraints'=> [
                    new NotBlank(['message'=>'Ce champ ne peut être vide'])
                ]
                ]);

        //Contenu
        $builder->add('Contenu', TextareaType::class, [
            'label'=> 'Corps de l\'article'
        ]);

        $builder->add('Date');

        //Statut
        $builder->add('statut', CheckboxType::class, [
            'label'=>'Publier l\'article'
        ]);

        //Bouton Envoyer 
        $builder->add('submit', SubmitType::class, array('label' =>'Enregistrer')           
        );

        
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Article::class,
        ]);
    }
}
