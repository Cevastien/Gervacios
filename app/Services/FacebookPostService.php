<?php

namespace App\Services;

use App\Models\BlogPost;
use App\Models\Setting;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class FacebookPostService
{
    private const GRAPH_URL = 'https://graph.facebook.com/v19.0';

    public function getPageId(): ?string
    {
        return Setting::get('fb_page_id') ?: config('services.facebook.page_id');
    }

    public function getAccessToken(): ?string
    {
        return Setting::get('fb_access_token') ?: config('services.facebook.access_token');
    }

    public function isConfigured(): bool
    {
        return !empty($this->getPageId()) && !empty($this->getAccessToken());
    }

    /**
     * Sync posts from the Facebook page feed into blog_posts.
     * Returns the number of new/updated posts, or -1 on failure.
     */
    public function sync(): int
    {
        if (!$this->isConfigured()) {
            Log::warning('FacebookPostService: not configured, skipping sync.');
            return -1;
        }

        $pageId = $this->getPageId();
        $token  = $this->getAccessToken();

        try {
            $response = Http::timeout(30)->get(self::GRAPH_URL . "/{$pageId}/posts", [
                'access_token' => $token,
                'fields'       => 'id,message,full_picture,created_time,permalink_url',
                'limit'        => 50,
            ]);

            if (!$response->ok()) {
                Log::error('FacebookPostService: API error', [
                    'status' => $response->status(),
                    'body'   => $response->body(),
                ]);
                return -1;
            }

            $data  = $response->json('data', []);
            $count = 0;

            foreach ($data as $fbPost) {
                $message = $fbPost['message'] ?? null;
                if (empty($message)) {
                    continue;
                }

                $lines   = preg_split('/\r?\n/', trim($message), 2);
                $title   = Str::limit(trim($lines[0]), 200);
                $content = $message;
                $excerpt = Str::limit($message, 300);

                BlogPost::updateOrCreate(
                    ['fb_post_id' => $fbPost['id']],
                    [
                        'title'        => $title,
                        'excerpt'      => $excerpt,
                        'content'      => $content,
                        'image_url'    => $fbPost['full_picture'] ?? null,
                        'fb_url'       => $fbPost['permalink_url'] ?? null,
                        'published_at' => isset($fbPost['created_time'])
                            ? \Carbon\Carbon::parse($fbPost['created_time'])
                            : now(),
                    ]
                );

                $count++;
            }

            Setting::set('fb_last_synced_at', now()->toIso8601String());

            return $count;
        } catch (\Throwable $e) {
            Log::error('FacebookPostService: sync failed', [
                'error' => $e->getMessage(),
            ]);
            return -1;
        }
    }
}
