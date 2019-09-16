<?php

namespace Platform\Line\Handlers\Commands;

use Carbon\Carbon;
use Platform\App\Commanding\CommandHandler;
use Platform\App\Exceptions\SeException;
use Platform\Line\Repositories\Contracts\LineRepository;
use Platform\Line\Repositories\Contracts\StyleRepository;
use Platform\Line\Transformers\StyleTransformer;

class GetApprovalListByIdCommandHandler implements CommandHandler
{
    /**
     * @var string
     */
    private $style;

    public function __construct(StyleRepository $styleRepo)
    {
        $this->styleRepo = $styleRepo;
    }

    public function handle($command)
    {
        \DB::beginTransaction();
        $style = $this->styleRepo->getStyleById($command->styleId);
        \DB::commit();

        $transform = new StyleTransformer;
        $transformStyle = $transform->transform($style);
        $production = $transformStyle['production'];
        $development = $transformStyle['development'];
        $shipped = $transformStyle['shipped'];

        return [
            'development' => $development, 
            'production' => $production, 
            'shipped' => $shipped
        ];
    }
}
