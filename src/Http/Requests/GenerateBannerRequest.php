<?php

namespace Vedanshi\GeminiBanner\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class GenerateBannerRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'front_image' => ['required', 'file', 'image'],
            'back_image' => ['required', 'file', 'image'],
            'transparent_image' => ['required', 'file', 'image'],
            'product_name' => ['required', 'string', 'max:255'],
        ];
    }

    /**
     * Normalize data for service / job / helper
     */
    public function payload(): array
    {
        $disk = config('gemini-banner.disks.input');

        return [
            'front_image' => $this->file('front_image')
                ->store(config('gemini-banner.paths.front'), $disk),

            'back_image' => $this->file('back_image')
                ->store(config('gemini-banner.paths.back'), $disk),

            'transparent_image' => $this->file('transparent_image')
                ->store(config('gemini-banner.paths.reference'), $disk),

            'product_name' => $this->input('product_name'),
        ];
    }


}
