<?php
/**
 * Created by PhpStorm.
 * User: marko
 * Date: 2/7/19
 * Time: 4:12 PM
 */

namespace App\Service;


use App\Entity\Category;
use App\Entity\Question;
use App\Form\CategoryType;
use App\Form\QuestionType;
use App\Repository\CategoryRepository;
use App\Repository\QuestionRepository;
use Symfony\Component\Form\FormFactory;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;

class HelpService
{

    /** @var CategoryRepository */
    protected $categoryRepository;
    /** @var QuestionRepository */
    protected $questionRepository;
    /** @var FormFactory */
    protected $formFactory;

    public function __construct(CategoryRepository $categoryRepository, QuestionRepository $questionRepository, FormFactoryInterface $formFactory)
    {

        $this->categoryRepository = $categoryRepository;
        $this->questionRepository = $questionRepository;
        $this->formFactory = $formFactory;
    }

    /**
     * @param Request $request
     * @param int $category_id
     * @return Category|mixed
     * @throws \Doctrine\ORM\ORMException
     */
    public function saveCategoryFromForm(Request $request, $category_id)
    {

        /** @var Category $category */
        $category = $this->getCategory($category_id);

        $categoryForm = $this->formFactory->create(CategoryType::class, $category);
        $categoryForm->handleRequest($request);

        if (!$categoryForm->isValid()) {
            throw new \Exception('Invalid');
        }
        if (!$categoryForm->isSubmitted()) {
            throw new \Exception('Category Form Not Submitted!');
        }

        $category = $categoryForm->getData();
        $category->setSlug($this->createSlug($category->getHeadline()));
        $category->setCreatedAt();
        $this->categoryRepository->save($category, true);

        return $category;

    }

    /**
     * @param Request $request
     * @param $question_id
     * @return Question|mixed
     * @throws \Doctrine\ORM\ORMException
     */
    public function saveQuestionFromForm(Request $request, $question_id)
    {

        /** @var Question $question */
        $question = $this->getQuestion($question_id);

        $questionForm = $this->formFactory->create(QuestionType::class, $question);
        $questionForm->handleRequest($request);

        if (!$questionForm->isValid()) {
            throw new \Exception('Invalid');
        }
        if (!$questionForm->isSubmitted()) {
            throw new \Exception('Question Form Not Submitted!');
        }

        $question = $questionForm->getData();
        $question->setSlug($this->createSlug($question->getHeadline()));
        $question->setCreatedAt();
        $this->questionRepository->save($question, true);

        return $question;

    }

    /**
     * Creating a slug for Category\Question
     *
     * @param string $headline
     * @return mixed
     */
    public function createSlug($headline)
    {
        $slug = strtolower($headline);
        $slug = preg_replace('/\s+/', '_', trim($slug));
        if (strpos($slug, '/')) {

            $slug = preg_replace('/\//', '_and_', trim($slug));

        }

        return $slug;
    }

    /**
     * @param int $category_id
     * @return \App\Entity\Category|null
     */
    public function getCategory($category_id)
    {
        if (!is_int($category_id) or is_null($category_id)) {
            return null;
        }
        return $this->categoryRepository->find($category_id);

    }


    /**
     * @param $question_id
     * @return \App\Entity\Question|null
     */
    public function getQuestion($question_id)
    {

        if (!is_int((int)$question_id) or is_null($question_id)) {
            return null;
        }
        return $this->questionRepository->find($question_id);

    }


}