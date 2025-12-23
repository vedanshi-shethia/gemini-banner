<?php
namespace Vedanshi\GeminiBanner\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Vedanshi\GeminiBanner\Services\GeminiBannerService;

class GenerateGeminiBannerJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected array $payload;

    /**
     * @param array $payload
     *  [
     *    front_image,
     *    back_image,
     *    transparent_image,
     *    product_name
     *  ]
     */
    public function __construct(array $payload)
    {
        $this->payload = $payload;
    }

    public function handle(GeminiBannerService $service): void
    {
        $service->generate($this->payload);
    }
}
