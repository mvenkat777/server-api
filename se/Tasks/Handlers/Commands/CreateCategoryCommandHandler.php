<?php
namespace Platform\Tasks\Handlers\Commands;

use Platform\App\Commanding\CommandHandler;
use Platform\App\Exceptions\SeException;
use Platform\Tasks\Repositories\Contracts\CategoryRepository;
use Platform\Tasks\Validators\CreateCategory;

class CreateCategoryCommandHandler implements CommandHandler 
{
	/**
	 * @var Platform\Tasks\Repositories\Contracts\CategoryRepository
	 */
	protected $categoryRepository;

	/**
	 * @var Platform\Tasks\Validators\CreateCategory
	 */
	protected $categoryValidator;

	/**
	 * @param CategoryRepository $categoryRepository
	 */
	public function __construct(CategoryRepository $categoryRepository,
								CreateCategory $categoryValidator)
	{
		$this->categoryRepository = $categoryRepository;
		$this->categoryValidator = $categoryValidator;
	}

	/**
	 * @param  CreateCategoryCommand $command
	 * @return App\Category
	 */
	public function handle($command)
	{
		$this->categoryValidator->validate((array)$command);
		return $this->categoryRepository->createCategory($command->title);
	}

}