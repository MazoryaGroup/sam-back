<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\HomeHero;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class HomeHeroController extends Controller
{
    public function show(): JsonResponse
    {
        $hero = HomeHero::first();

        if (!$hero) {
            return response()->json([
                'success' => true,
                'data' => null,
            ]);
        }

        return response()->json([
            'success' => true,
            'data' => [
                'id' => $hero->id,

                'image' => $hero->image
                    ? Storage::disk('api_public')->url($hero->image)
                    : null,

                'production_image' => $hero->production_image
                    ? Storage::disk('api_public')->url($hero->production_image)
                    : null,

                'magazine_image' => $hero->magazine_image
                    ? Storage::disk('api_public')->url($hero->magazine_image)
                    : null,
            ],
        ]);
    }


}
