<?php
/**
 * Created by PhpStorm.
 * User: marko
 * Date: 2/7/19
 * Time: 3:58 PM
 */

namespace App\Controller;


use App\Entity\Category;
use App\Entity\Question;
use App\Form\CategoryType;
use App\Form\QuestionType;
use App\Lib\Constants;
use App\Lib\Response\AjaxResponse;
use App\Repository\CategoryRepository;
use App\Service\HelpService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

class HelpController extends AbstractController
{


    /** @var HelpService */
    protected $helpService;
    /** @var CategoryRepository */
    protected $categoryRepository;

    public function __construct(HelpService $helpService, CategoryRepository $categoryRepository)
    {
        $this->helpService = $helpService;
        $this->categoryRepository = $categoryRepository;
    }


    /**
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function showAction()
    {

        $categories = $this->categoryRepository->findAll();

        return $this->render('show.html.twig', [
            'categories' => $categories
        ]);
    }

    /**
     * @param Request $request
     * @param null $category_id
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function createEditCategoryAction(Request $request, $category_id = null)
    {

        /** @var Category $category */
        $category = $this->helpService->getCategory($category_id);

        if (is_null($category)) {
            $category = new Category();
        }

        $categoryForm = $this->createForm(CategoryType::class, $category,
            ['action' => $this->generateUrl('fk_help_save', ['id' => $category_id, 'type' => Constants::CATEGORY])]);

        return $this->render('category/create_edit.html.twig', [
            'categoryForm' => $categoryForm->createView(),
        ]);
    }


    /**
     * @param Request $request
     * @param null|int $question_id
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function createEditQuestionAction(Request $request, $question_id = null)
    {

        /** @var Question $question */
        $question = $this->helpService->getQuestion($question_id);

        if (is_null($question)) {
            $question = new Question();
        }

        $questionForm = $this->createForm(QuestionType::class, $question,
            ['action' => $this->generateUrl('fk_help_save', ['id' => $question_id, 'type' => Constants::QUESTION])]);

        return $this->render('question/create_edit.html.twig', [
            'questionForm' => $questionForm->createView(),
        ]);
    }


    /**
     * @param Request $request
     * @param $type
     * @param null $id
     * @return AjaxResponse
     */
    public function saveAction(Request $request, $type, $id = null)
    {
        try {

            switch ($type) {
                case Constants::CATEGORY:
                    $this->helpService->saveCategoryFromForm($request, $id);
                    break;
                case Constants::QUESTION:
                    $this->helpService->saveQuestionFromForm($request, $id);
                    break;
                default:
                    return (new AjaxResponse(['message' => $type . ' is an invalid type URL parameter. "category" and "question" are the only valid ones']))->error();
                    break;
            }


            return (new AjaxResponse(['message' => 'Data Saved!']))->success();

        } catch (\Exception $exception) {

            return (new AjaxResponse(['message' => $exception->getMessage()]))->error();

        }
    }


}