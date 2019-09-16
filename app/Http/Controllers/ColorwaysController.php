<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests;
use Illuminate\Http\Request;
use League\Fractal\Manager;
use Platform\Techpacks\Repositories\Contracts\ColorwaysRepository;
use Platform\Techpacks\Transformers\ColorwaysTransformer;
use Platform\Techpacks\Validators\NewColorway;

class ColorwaysController extends ApiController
{
    protected $colorways;
    protected $validator;

    public function __construct(ColorwaysRepository $colorways, NewColorway $validator)
    {
        $this->validator = $validator;
        $this->colorways = $colorways;

        parent::__construct(new Manager());
    }

    /**
     * Get all the colorways of a techpack
     * @param  string $techpackId
     * @return mixed
     */
    public function index($techpackId)
    {
        $colorways = $this->colorways->getByTechpackId($techpackId);

        if ($colorways) {
            return $this->respondWithCollection($colorways, new ColorwaysTransformer, 'colorways');
        }

        return $this->setStatusCode(404)
                    ->respondWithError("We were not able to find any colorways for this techpack.");
    }

    /**
     * Store a new colorway
     * @param  string  $techpackId
     * @param  Request $request
     * @return mixed
     */
    public function store($techpackId, Request $request)
    {
        $data = $request->all();
        $data['techpackId'] = $techpackId;

        $this->validator->validate($data);

        $colorway = $this->colorways->addNewColorway($data);

        if ($colorway) {
            return $this->respondWithNewItem($colorway, new ColorwaysTransformer, 'colorways');
        }

        return $this->setStatusCode(500)
                    ->respondWithError('We were not able to add the colorway. Please try again.');
    }

    /**
     * Add multiple colorways at once
     * @param  string  $techpackId
     * @param  Request $request
     * @return mixed
     */
    public function bulkStore($techpackId, Request $request)
    {
        $data = $request->all();
        foreach ($data as $datum) {
            $datum['techpackId'] = $techpackId;
            $colorway = $this->colorways->addNewColorway($datum);
        }

        return $this->respondOk('Colorways added.');
    }

    /**
     * Delete a colorway
     * @return mixed
     */
    public function destroy($techpackId, $colorwayId)
    {
        $deleted = $this->colorways->deleteColorway($colorwayId);

        if ($deleted) {
            return $this->respondOk('Colorway deleted successfully.');
        }

        return $this->setStatusCode(500)->respondWithError('Something went wrong. Colorway could not be deleted');
    }
}
