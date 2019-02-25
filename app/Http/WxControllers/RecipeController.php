<?php
namespace App\Http\WxControllers;


/**
 * Class RecipeController
 * @package App\Http\WxControllers
 */
class RecipeController extends Controller
{
    /**
     * @var
     */
    protected $recipe;

    /**
     * RecipeController constructor.
     * @param $recipe
     */
    public function __construct(RecipeRepository $recipe)
    {
        $this->recipe = $recipe;
    }
}

