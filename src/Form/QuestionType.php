<?php
/**
 * Created by PhpStorm.
 * User: marko
 * Date: 2/8/19
 * Time: 4:19 PM
 */

namespace App\Form;


use App\Entity\Category;
use App\Entity\Question;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class QuestionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('headline')
            ->add('body')
            ->add('rank')
            ->add('category_id', EntityType::class, ['class' => Category::class, 'choice_label' => 'headline'])
            ->add('save', SubmitType::class);
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Question::class
        ]);
    }


}