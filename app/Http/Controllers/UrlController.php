<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Url;
use App\Models\UrlHit;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class UrlController extends Controller
{
    public function shorten(Request $request)
    {
        $request->validate([
            'long_url' => 'required|url'
        ]);

        $shortUrl = Str::random(6);

        try {
            Url::create([
                'long_url' => $request->long_url,
                'short_url' => $shortUrl,
            ]);

            $appPath = config('app.path');
            $shortenedUrl = "http://{$appPath}/api/short/{$shortUrl}";

            return response()->json([
                'status' => 'success',
                'short_url' => $shortenedUrl
            ]);
        } catch (\Exception $e) {
            Log::error('Error creating short URL: ' . $e->getMessage());
            return response()->json([
                'status' => 'fail',
                'message' => '非預期錯誤'
            ]);
        }
    }

    public function getLongUrl(Request $request, $short_url)
    {
        try {
            $url = Url::where('short_url', $short_url)->firstOrFail();

            if ($url->hit_count >= 10) {
                return response()->json([
                    'status' => 'fail',
                    'message' => '開啟超過10次'
                ], 403);
            }

            $url->increment('hit_count');

            UrlHit::create([
                'url_id' => $url->id,
                'hit_time' => Carbon::now(),
                'hit_number' => $url->hit_count,
            ]);

            return response()->json([
                'status' => 'success',
                'long_url' => $url->long_url,
                'count' => $url->hit_count
            ], 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'status' => 'fail',
                'message' => '短網址不存在'
            ], 404);
        } catch (\Exception $e) {
            Log::error('Error retrieving long URL: ' . $e->getMessage());
            return response()->json([
                'status' => 'fail',
                'message' => '非預期錯誤'
            ], 500);
        }
    }
}
