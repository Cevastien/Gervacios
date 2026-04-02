<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Services\BookingConfirmationService;
use App\Services\PayMongoService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PayMongoWebhookController extends Controller
{
    public function handle(Request $request, PayMongoService $payMongoService)
    {
        $payload = $request->getContent();
        $signature = $request->header('Paymongo-Signature', '');

        if (!$payMongoService->verifyWebhookSignature($payload, $signature)) {
            Log::warning('PayMongo webhook signature verification failed');
            return response()->json(['error' => 'Invalid signature'], 403);
        }

        $data = $request->input('data', []);
        $eventType = $data['attributes']['type'] ?? '';
        $resourceData = $data['attributes']['data'] ?? [];

        Log::info('PayMongo webhook received', ['type' => $eventType]);

        if ($eventType === 'link.payment.paid') {
            return $this->handlePaymentPaid($resourceData);
        }

        return response()->json(['status' => 'ignored']);
    }

    private function handlePaymentPaid(array $resourceData): \Illuminate\Http\JsonResponse
    {
        $linkId = $resourceData['id'] ?? null;
        $paymentId = $resourceData['attributes']['payments'][0]['id'] ?? null;
        $remarks = $resourceData['attributes']['remarks'] ?? '';

        $booking = null;

        if ($remarks) {
            $booking = Booking::where('booking_ref', $remarks)->first();
        }

        if (!$booking && $linkId) {
            $booking = Booking::where('paymongo_link_id', $linkId)->first();
        }

        if (!$booking) {
            Log::warning('PayMongo webhook: booking not found', [
                'link_id' => $linkId,
                'remarks' => $remarks,
            ]);
            return response()->json(['status' => 'booking_not_found'], 200);
        }

        if ($booking->payment_status === 'paid') {
            return response()->json(['status' => 'already_processed'], 200);
        }

        $booking->update([
            'paymongo_payment_id' => $paymentId,
        ]);

        $table = app(BookingConfirmationService::class)->confirm($booking);

        if (! $table) {
            Log::warning('PayMongo webhook: no table available for auto-assignment', [
                'booking_ref' => $booking->booking_ref,
            ]);
        }

        Log::info('PayMongo payment confirmed for booking', [
            'booking_ref' => $booking->booking_ref,
        ]);

        return response()->json(['status' => 'success']);
    }
}
