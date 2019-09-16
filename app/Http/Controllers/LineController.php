<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests;
use Illuminate\Http\Request;
use League\Fractal\Manager;
use Platform\App\Commanding\DefaultCommandBus;
use Platform\Line\Commands\CreateNewLineCommand;
use Platform\Line\Commands\GetAllLinesCommand;
use Platform\Line\Commands\GetAllLinesMetaCommand;
use Platform\Line\Commands\GetAllStyleCommand;
use Platform\Line\Commands\GetLineByIdCommand;
use Platform\Line\Commands\UpdateLineCommand;
use Platform\Line\Repositories\Contracts\LineRepository;
use Platform\Line\Transformers\LineMinimalTransformer;
use Platform\Line\Transformers\LineTransformer;
use Platform\Line\Transformers\MetaLineTransformer;
use Platform\Line\Transformers\SalesStreamTransformer;
use Platform\Line\Transformers\VLPApprovalTransformer;

class LineController extends ApiController
{
	/**
	 * @var Platform\App\Commanding\DefaultCommandBus
	 */
	private $commandBus;

	/**
	 * @var Platform\Line\Repositories\Contracts\LineRepository
	 */
	protected $lineRepository;


	/**
	 * @param DefaultCommandBus $defaultBus
	 */
	public function __construct(
		DefaultCommandBus $commandBus,
		LineRepository $lineRepository
	) {
		$this->commandBus = $commandBus;
		$this->lineRepository = $lineRepository;

		parent::__construct(new Manager());
	}

	public function GetAllMeta() {
		$lines = $this->commandBus->execute(new GetAllLinesCommand());


		if (count($lines) > 0) {
			return $this->respondWithCollection($lines, new LineMinimalTransformer, 'lines');
		} else {
			return $this->respondWithError("No line found.");
		}
	}

	public function index() {
		$lines = $this->commandBus->execute(new GetAllLinesMetaCommand());

		if (count($lines) > 0) {
			return $this->respondWithPaginatedCollection($lines, new MetaLineTransformer, 'lines');
		} else {
			return $this->respondWithError("No line found.");
		}
	}


	/**
	 * Create a new Line item
	 *
	 * @param Request $request
	 * @return mixed
	 */
	public function store(Request $request) {
		$line = $this->commandBus->execute(new CreateNewLineCommand($request->all()));

		if ($line) {
			 return $this->respondWithNewItem($line, new LineTransformer, 'line');
		} else {
			return $this->respondWithError("Line creation failed. Please try again.");
		}
	}

	/**
	 * Update a line
	 *
	 * @param string $lineId
	 * @param Request $request
	 * @return mixed
	 */
	public function update($lineId, Request $request) {
		$line = $this->commandBus->execute(new UpdateLineCommand($lineId, $request->all()));

		if ($line) {
			return $this->respondWithItem($line, new LineTransformer, 'line');
		} else {
			return $this->respondWithError("Line updation failed");
		}
	}

	/**
	 * Get a single line item
	 *
	 * @param string $lineId
	 * @return void
	 */
	public function show($lineId) {
		$line = $this->commandBus->execute(new GetLineByIdCommand($lineId));
		// dd($line);
		if ($line) {
			return $this->respondWithItem($line, new LineTransformer, 'line');
		} else {
			return $this->respondWithError("No line found with this id.");
		}
	}

	/**
	 * For wip tracker
	 * @param  Request $request 
	 * @return array           
	 */
	public function salesStream(Request $request){

		$data = $request->all();
		$style = $this->commandBus->execute(new GetAllStyleCommand($data));
		// var_dump($style->toArray());
		// die();
		if ($style) {
			return $this->respondWithPaginatedCollection($style, new SalesStreamTransformer, 'styles');
		} else {
			return $this->respondWithError("No line found with this id.");
		}
	}

	/**
	 * complete line
	 * @param  string $lineId 
	 * @return string         
	 */
	public function completeLine($lineId)
	{
		$complete = $this->lineRepository->completeLine($lineId);
		if ($complete) {
			return $this->respondOk('Line completed successfully');
		}
		return $this->respondWithError('Faild to complete');
	}

	/**
	 * complete line
	 * @param  string $lineId 
	 * @return string         
	 */
	public function undoLine($lineId)
	{
		$undo = $this->lineRepository->undoLine($lineId);
		if ($undo) {
			return $this->respondOk('Line undo successfully');
		}
		return $this->respondWithError('Faild to undo');
	}

	/**
	 * Destory
	 * @param  Request $request
	 * @param  string  $id
	 * @return string
	 */
	public function destroy(Request $request, $id)
	{
		$data = $request->all();
        if (isset($data['type']) && $data['type'] == 'delete') {
            $delete = $this->lineRepository->deleteLine($id);
            if ($delete) {
                return $this->respondOk('Line deleted successfully');
            }
            return $this->respondWithError('Faild to delete');
        } else {
            $archived = $this->lineRepository->archiveLine($id);
            if ($archived) {
                return $this->respondOk('Line archived successfully');
            }
            return $this->respondWithError('Faild to archive');
        }
	}

	/**
	 * Rollback Line
	 * @param  string $lineId
	 * @return string
	 */
	public function rollbackLine($lineId)
    {
        $rollback = $this->lineRepository->rollbackLine($lineId);
        if ($rollback) {
                return $this->respondOk("Successfully rollbacked the style");
        } else {
                return $this->respondWithError("Failed to rollback the style. Please try again.");
        }
    }

	public function approveVLP($lineId)
	{
		$approval = \App\VLPAttachmentApproval::create([
			'id' => \Rhumsaa\Uuid\Uuid::uuid4()->toString(),
			'line_id' => $lineId,
			'approver_id' => \Auth::user()->id,
			'approval' => true,
		]);

		if ($approval) {
			return $this->respondWithItem($approval, new VLPApprovalTransformer, 'line');
		}

		return $this->respondWithError("Failed to approve VLP attachment.");
	}

	public function disapproveVLP($lineId)
	{
		$approval = \App\VLPAttachmentApproval::create([
			'id' => \Rhumsaa\Uuid\Uuid::uuid4()->toString(),
			'line_id' => $lineId,
			'approver_id' => \Auth::user()->id,
			'approval' => false,
		]);

		if ($approval) {
			return $this->respondWithItem($approval, new VLPApprovalTransformer, 'line');
		}

		return $this->respondWithError("Failed to disapprove VLP attachment.");
	}

	/**
	 * @param  Request $request
	 * @return mixed
	 */
	public function filter(Request $request)
	{
            $line = $this->lineRepository->filterLine($request->all());
            return $this->respondWithPaginatedCollection($line, new MetaLineTransformer, 'line');
	}

}
