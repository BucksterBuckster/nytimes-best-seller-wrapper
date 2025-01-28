<?php

namespace App\Http\Controllers;

use App\Http\Requests\BestSellersRequest;
use App\Services\NYTBestSellersService;
use Illuminate\Http\JsonResponse;

class NYTBestSellersController extends Controller
{
    public function __construct(
        private NYTBestSellersService $nytService
    ) {}

    public function __invoke(BestSellersRequest $request): JsonResponse
    {

        try {
            $data = $this->nytService->getBestSellers($request->validated());

            return response()->json($data);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Failed to fetch bestsellers',
                'message' => $e->getMessage(),
            ], 500);
        }
    }
}
