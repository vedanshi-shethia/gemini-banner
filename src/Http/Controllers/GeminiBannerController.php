<?php
namespace Vedanshi\GeminiBanner\Http\Controllers;

use Vedanshi\GeminiBanner\Http\Requests\GenerateBannerRequest;
use Vedanshi\GeminiBanner\Jobs\GenerateGeminiBannerJob;
use Vedanshi\GeminiBanner\Facades\GeminiBanner as FacadesGeminiBanner;

class GeminiBannerController
{
    public function sync(GenerateBannerRequest $request)
    {
        return FacadesGeminiBanner::generate($request->payload());
    }

    public function async(GenerateBannerRequest $request)
    {
        GenerateGeminiBannerJob::dispatch($request->payload());

        return response()->json([
            'message' => 'Banner generation queued'
        ]);
    }
}
