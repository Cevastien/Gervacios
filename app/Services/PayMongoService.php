<?php

namespace App\Services;

use App\Models\Setting;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class PayMongoService
{
    private function getSecretKey(): string
    {
        return Setting::get('paymongo_secret_key', config('services.paymongo.secret_key', ''));
    }

    public function isConfigured(): bool
    {
        return !empty($this->getSecretKey());
    }

    /**
     * Create a PayMongo payment link.
     *
     * @return array{checkout_url: string, payment_link_id: string}|null
     */
    public function createPaymentLink(int $amountCentavos, string $description, string $bookingRef, string $successUrl, string $failedUrl): ?array
    {
        $secretKey = $this->getSecretKey();
        if (empty($secretKey)) {
            return null;
        }

        try {
            $response = Http::withBasicAuth($secretKey, '')
                ->post('https://api.paymongo.com/v1/links', [
                    'data' => [
                        'attributes' => [
                            'amount'      => $amountCentavos,
                            'description' => $description,
                            'remarks'     => $bookingRef,
                        ],
                    ],
                ]);

            if (!$response->successful()) {
                Log::error('PayMongo createPaymentLink failed', [
                    'status' => $response->status(),
                    'body'   => $response->body(),
                ]);
                return null;
            }

            $data = $response->json('data');

            return [
                'checkout_url'    => $data['attributes']['checkout_url'],
                'payment_link_id' => $data['id'],
            ];
        } catch (\Exception $e) {
            Log::error('PayMongo createPaymentLink exception', ['error' => $e->getMessage()]);
            return null;
        }
    }

    /**
     * Get payment link status from PayMongo.
     *
     * @return array{status: string, payments: array}|null
     */
    public function getPaymentLink(string $linkId): ?array
    {
        $secretKey = $this->getSecretKey();
        if (empty($secretKey)) {
            return null;
        }

        try {
            $response = Http::withBasicAuth($secretKey, '')
                ->get("https://api.paymongo.com/v1/links/{$linkId}");

            if (!$response->successful()) {
                return null;
            }

            $data = $response->json('data');

            return [
                'status'   => $data['attributes']['status'],
                'payments' => $data['attributes']['payments'] ?? [],
            ];
        } catch (\Exception $e) {
            Log::error('PayMongo getPaymentLink exception', ['error' => $e->getMessage()]);
            return null;
        }
    }

    /**
     * Verify PayMongo webhook signature.
     */
    public function verifyWebhookSignature(string $payload, string $signatureHeader): bool
    {
        $webhookSecret = Setting::get('paymongo_webhook_secret', config('services.paymongo.webhook_secret', ''));
        if (empty($webhookSecret)) {
            return false;
        }

        $parts = [];
        foreach (explode(',', $signatureHeader) as $part) {
            $kv = explode('=', $part, 2);
            if (count($kv) === 2) {
                $parts[$kv[0]] = $kv[1];
            }
        }

        $timestamp = $parts['t'] ?? '';
        $testSig   = $parts['te'] ?? '';
        $liveSig   = $parts['li'] ?? '';

        $expectedPayload = "{$timestamp}.{$payload}";
        $computedSig = hash_hmac('sha256', $expectedPayload, $webhookSecret);

        $signatureToCheck = !empty($liveSig) ? $liveSig : $testSig;

        return hash_equals($computedSig, $signatureToCheck);
    }
}
