<?php
namespace Platform\Tasks\Transformers;

use App\Task;
use League\Fractal\Manager;
use League\Fractal\TransformerAbstract;
use Platform\Priority\Transformers\PriorityTransformer;
use Platform\Tasks\Transformers\CategoryTransformer;
use Platform\Tasks\Transformers\TagTransformer;

class SchemaTransformer extends TransformerAbstract
{
	public function __construct()
    {
        $this->manager = new Manager();
    }

	public function transform(){

		$categories = $this->collection(
						(new \App\TaskCategory)->getAllCategory(),
						new CategoryTransformer
					);
        $categories = $this->manager->createData($categories)->toArray();

        $tags = $this->collection(
        			(new \App\TaskTag)->getAllTags(), 
        			new TagTransformer
        		);
        $tags = $this->manager->createData($tags)->toArray();

        $priorities = $this->collection(
				    	(new \App\Priority)->getAllPriority(), 
				    	new PriorityTransformer
				    );
        $priorities = $this->manager->createData($priorities)->toArray();

		return [
			'categories' => $categories['data'],
			'tags' => $tags['data'],
			'priorities' => $priorities['data'],
			'inboxCount' => count((new \App\Task)->unseenTask()),
			'reviewCount' => count((new \App\Task)->getTaskByType())
		];
	}
}