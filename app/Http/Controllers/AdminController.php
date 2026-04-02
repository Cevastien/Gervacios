<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Setting;
use App\Services\BookingConfirmationService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Intervention\Image\ImageManager;

/**
 * AdminController
 *
 * Serves the admin dashboard and management pages.
 * Dashboard functionality is handled by Livewire components.
 */
class AdminController extends Controller
{
    /**
     * Display the admin dashboard.
     */
    public function index()
    {
        return view('admin.dashboard');
    }

    /**
     * Seating analytics (charts and metrics).
     */
    public function seatingAnalytics()
    {
        return view('admin.seating-analytics');
    }

    /**
     * Display the tables management page.
     */
    public function tables()
    {
        return view('admin.tables');
    }

    /**
     * Display the waitlist management page.
     */
    public function waitlist()
    {
        return view('admin.waitlist');
    }

    /**
     * Display the bookings history page.
     */
    public function bookings()
    {
        return view('admin.bookings', [
            'recentBookings' => Booking::with('table')->latest()->take(50)->get(),
        ]);
    }

    public function uploadQr(Request $request): RedirectResponse
    {
        $request->validate([
            'qr_image' => ['required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
        ]);

        $dir = public_path('images');
        if (!File::isDirectory($dir)) {
            File::makeDirectory($dir, 0755, true);
        }

        $request->file('qr_image')->move($dir, 'qrcode-temp.png');

        return back()->with('success', 'Image uploaded. Now crop the QR code area.');
    }

    public function saveQrCrop(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'qr_account_name' => ['required', 'string', 'max:100'],
            'qr_account_number' => ['required', 'string', 'max:50'],
            'qr_payment_label' => ['nullable', 'string', 'max:100'],
            'crop_x' => ['required', 'numeric'],
            'crop_y' => ['required', 'numeric'],
            'crop_width' => ['required', 'numeric'],
            'crop_height' => ['required', 'numeric'],
        ]);

        $tempPath = public_path('images/qrcode-temp.png');
        if (!File::isFile($tempPath)) {
            return back()->withErrors(['crop_x' => 'No uploaded image found. Please upload a screenshot first.'])->withInput();
        }

        $manager = ImageManager::gd();
        $image = $manager->read($tempPath);

        $iw = $image->width();
        $ih = $image->height();

        $x = (int) round((float) $validated['crop_x']);
        $y = (int) round((float) $validated['crop_y']);
        $cw = (int) round((float) $validated['crop_width']);
        $ch = (int) round((float) $validated['crop_height']);

        $x = max(0, min($x, max(0, $iw - 1)));
        $y = max(0, min($y, max(0, $ih - 1)));
        $cw = max(1, min($cw, $iw - $x));
        $ch = max(1, min($ch, $ih - $y));

        $image->crop($cw, $ch, $x, $y);
        $image->cover(400, 400);

        $outPath = public_path('images/qrcode.png');
        $image->save($outPath);

        File::delete($tempPath);

        Setting::set('qr_image_path', 'images/qrcode.png');
        Setting::set('qr_account_name', $validated['qr_account_name']);
        Setting::set('qr_account_number', $validated['qr_account_number']);
        Setting::set(
            'qr_payment_label',
            $validated['qr_payment_label'] ?? 'GCash · InstaPay accepted'
        );
        Setting::set('qr_updated_at', now()->timestamp);

        return back()->with('success', 'QR code updated successfully.');
    }

    public function verifyPayment(Booking $booking): RedirectResponse
    {
        if ($booking->payment_method === 'paymongo' || $booking->paymongo_payment_id !== null) {
            return back()->with('error', 'PayMongo payments are confirmed automatically — they cannot be approved here.');
        }

        if ($booking->payment_method !== 'manual_qr' || $booking->payment_status !== 'pending_verification') {
            return back()->with('error', 'Only manual QR bookings awaiting verification can be approved with this action.');
        }

        $verifierId = auth()->id();
        if ($verifierId === null) {
            return back()->with('error', 'You must be signed in to verify payments.');
        }

        $booking->update([
            'payment_verified_by' => (string) $verifierId,
        ]);

        $table = app(BookingConfirmationService::class)->confirm($booking);

        if ($table) {
            return back()->with(
                'success',
                'Payment verified. Table ' . $table->label . ' assigned automatically.'
            );
        }

        return back()->with(
            'success',
            'Payment verified. No table available — please assign manually.'
        );
    }

    public function rejectPayment(Booking $booking): RedirectResponse
    {
        if ($booking->payment_method !== 'manual_qr') {
            return back()->with('error', 'Only manual QR bookings can be rejected with this action.');
        }

        if ($booking->payment_status !== 'pending_verification') {
            return back()->with('error', 'This booking is not awaiting payment verification.');
        }

        if (auth()->id() === null) {
            return back()->with('error', 'You must be signed in to reject payments.');
        }

        app(BookingConfirmationService::class)->reject($booking);

        return back()->with('success', 'Payment rejected. The guest has been notified by SMS.');
    }

    public function menu()
    {
        if (auth()->check() && !auth()->user()->isAdmin()) {
            abort(403);
        }

        return view('admin.menu');
    }

    public function settings()
    {
        if (auth()->check() && !auth()->user()->isAdmin()) {
            abort(403);
        }

        return view('admin.settings');
    }

    public function logs()
    {
        if (auth()->check() && !auth()->user()->isAdmin()) {
            abort(403);
        }

        return view('admin.logs');
    }
}
