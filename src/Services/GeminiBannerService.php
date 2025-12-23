<?php

namespace Vedanshi\GeminiBanner\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

class GeminiBannerService
{
    public function generate(array $data): string
    {
        $disk = config('gemini-banner.disks.input');

        $frontBase64 = base64_encode(Storage::disk($disk)->get($data['front_image']));
        $backBase64  = base64_encode(Storage::disk($disk)->get($data['back_image']));
        $transparentBase64 = base64_encode(Storage::disk($disk)->get($data['transparent_image']));

        $prompt = $this->buildPrompt($data['product_name']);

        $contents = [[
            'parts' => [
                ['text' => $prompt],

                ['inline_data' => [
                    'mime_type' => Storage::disk($disk)->mimeType($data['front_image']),
                    'data' => $frontBase64,
                ]],

                ['inline_data' => [
                    'mime_type' => Storage::disk($disk)->mimeType($data['back_image']),
                    'data' => $backBase64,
                ]],

                ['inline_data' => [
                    'mime_type' => Storage::disk($disk)->mimeType($data['transparent_image']),
                    'data' => $transparentBase64,
                ]],
            ],
        ]];


        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
            'x-goog-api-key' => config('gemini-banner.api_key'),
        ])->post(
            config('gemini-banner.endpoint'),
            ['contents' => $contents]
        );

        $result = $response->json();

        foreach ($result['candidates'][0]['content']['parts'] ?? [] as $part) {
            if (isset($part['inlineData']['data'])) {
                return $this->storeImage($part['inlineData']['data'], $data);
            }
        }

        throw new \Exception('No image returned by Gemini');
    }

    protected function storeImage(string $base64, array $data): string
    {
        $disk = config('gemini-banner.disks.output');
        $path = config('gemini-banner.paths.output');

        $filename = $path.'/banner-'.uniqid().'.png';

        Storage::disk($disk)->put($filename, base64_decode($base64));

        if (config('gemini-banner.cleanup.enabled')) {
            $this->cleanup($data);
        }

        return Storage::disk($disk)->url($filename);
    }

    protected function cleanup(array $data): void
    {
        $disk = config('gemini-banner.disks.input');

        Storage::disk($disk)->delete([
            $data['front_image'],
            $data['back_image'],
            $data['transparent_image'],
        ]);
    }


    protected function buildPrompt(string $productName): string
    {
        return "
                You are a professional graphic designer creating a high-quality WEBSITE BANNER for a book store.

                TASK:
                Create an attractive advertisement banner using the provided book cover images.

                BOOK NAME:
                {$productName}

                CRITICAL RULES (STRICT – DO NOT VIOLATE):
                - DO NOT generate, invent, modify, misspell, merge, redraw, or hallucinate ANY text.
                - The ONLY text allowed in the entire banner is the exact product name: {$productName}.
                - Do NOT distort, blur, crop, stretch, stylize, or partially hide any existing text on the book covers.
                - Book cover text must remain 100% readable and unchanged.
                - Do NOT add slogans, taglines, author names, or decorative text.
                - Do NOT add watermarks, logos, or extra typography.
                - Do NOT repeat the book covers.

                IMAGE USAGE:
                - Use the FRONT cover image as the primary visual focus.
                - Use the BACK cover image as a supporting visual element or background theme.
                - Use the THIRD image strictly as the reference for FINAL ASPECT RATIO and layout framing.
                - Maintain the exact aspect ratio of the third image without cropping or resizing distortions.
                - The book covers must be used exactly as provided, COPY-PASTE into the banner.
                - Preserve original proportions, colors, resolution, and typography of the covers.
                - All text visible on the covers must remain 100% identical, readable, and unchanged.
                - DO NOT recreate, redraw, re-illustrate, reinterpret, stylize, enhance, or regenerate the book cover images.

                DESIGN DIRECTION:
                - Fully fill the banner area with a rich, colorful, friendly design.
                - Add creative illustrations, playful vectors, soft shapes, or story-themed elements inspired by the book covers.
                - Match the color palette, art style, and mood of the front and back covers.
                - Ensure the design feels lively, premium, and suitable for a book website.
                - Avoid plain or empty backgrounds — the banner must feel visually engaging and complete.
                - Match the prmiary color and secondary color from the book covers to get the color palet for the banner.

                QUALITY REQUIREMENTS:
                - Clean edges, sharp details, high visual clarity.
                - Balanced composition with clear hierarchy.
                - No overlapping of illustrations over book cover text.
                - No artifacts, glitches, or warped elements.
                - No plain single color margins or backgrounds.
                - Background should match the color palet of the book covers.

                OUTPUT:
                - A single cohesive website banner image.
                - No text other than {$productName}.
        ";
    }

    public function test()
    {
        return 'Gemini Banner package is working!';
    }

}
