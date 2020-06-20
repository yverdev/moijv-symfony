<?php

namespace App\Twig;

use App\Repository\CategoryRepository;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class CategoriesExtension extends AbstractExtension
{
    /**
     * @var CategoryRepository
     */
    private $categoryRepository;

    /**
     * CategoriesExtension constructor.
     * @param CategoryRepository $categoryRepository
     */
    public function __construct(CategoryRepository $categoryRepository)
    {
        $this->categoryRepository = $categoryRepository;
    }

    public function getFunctions(): array
    {
        return [
            // {% for category in categories() %}
            new TwigFunction('categories', [$this, 'getAllCategories']),
        ];
    }

    public function getAllCategories()
    {
        return $this->categoryRepository->findAll();
    }
}