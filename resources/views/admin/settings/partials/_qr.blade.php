@php
    use App\Models\Setting;
    $field =
        'w-full rounded-lg border border-slate-300 bg-white px-3 py-2.5 text-base text-slate-900 shadow-sm transition placeholder:text-slate-400 focus:border-panel-primary focus:outline-none focus:ring-2 focus:ring-panel-primary/15';
@endphp

<style>
    .qr-cropper-wrap .cropper-container {
        max-width: 100%;
    }

    .qr-cropper-wrap img {
        display: block;
        max-width: 100%;
    }
</style>

<div class="grid grid-cols-1 gap-y-4">
    @if (
        $errors->has('qr_image') ||
            $errors->has('qr_account_name') ||
            $errors->has('qr_account_number') ||
            $errors->has('qr_payment_label') ||
            $errors->has('crop_x') ||
            $errors->has('crop_y') ||
            $errors->has('crop_width') ||
            $errors->has('crop_height'))
        <div class="rounded-lg border border-red-300 bg-red-50 px-3 py-2 text-sm text-red-900" role="alert">
            <ul class="list-inside list-disc space-y-0.5">
                @foreach (['qr_image', 'qr_account_name', 'qr_account_number', 'qr_payment_label', 'crop_x', 'crop_y', 'crop_width', 'crop_height'] as $qrField)
                    @foreach ($errors->get($qrField) as $message)
                        <li>{{ $message }}</li>
                    @endforeach
                @endforeach
            </ul>
        </div>
    @endif

    <div>
        <h3 class="mb-1 text-xs font-bold uppercase tracking-[0.14em] text-black">Upload</h3>
        <p class="mb-2 text-xs leading-relaxed text-slate-600">Full screenshot; crop the QR in the next step.</p>
        <form action="{{ route('admin.settings.qr-upload') }}" method="post" enctype="multipart/form-data"
            class="grid grid-cols-1 gap-y-3">
            @csrf
            <div>
                <label class="mb-1.5 block text-xs font-bold uppercase tracking-[0.12em] text-black" for="qr-upload-input">Screenshot
                    file</label>
                <input id="qr-upload-input" type="file" name="qr_image" data-modal-initial-focus
                    accept=".jpg,.jpeg,.png,.webp,image/jpeg,image/png,image/webp"
                    class="block w-full rounded-lg border border-slate-200 bg-white text-sm text-slate-700 file:mr-4 file:rounded-lg file:border-0 file:bg-[#eef1f5] file:px-3 file:py-2 file:text-xs file:font-semibold file:text-panel-primary hover:file:bg-[#dde3ec]">
                @error('qr_image')
                    <p class="mt-1.5 text-sm font-medium text-red-700">{{ $message }}</p>
                @enderror
            </div>
            <button type="submit"
                class="inline-flex w-full items-center justify-center rounded-lg border border-panel-primary bg-panel-primary px-4 py-2.5 text-xs font-semibold uppercase tracking-[0.1em] text-white shadow-sm transition hover:bg-panel-primary-hover focus:outline-none focus-visible:ring-2 focus-visible:ring-panel-primary focus-visible:ring-offset-2">
                Upload &amp; crop
            </button>
        </form>
    </div>

    @if ($qrHasTemp)
        <div class="border-t border-slate-200 pt-4">
            <h3 class="mb-2 text-xs font-bold uppercase tracking-[0.14em] text-black">Crop the QR</h3>
            <div class="qr-cropper-wrap max-w-full rounded-lg border border-slate-200 bg-slate-50 p-2">
                <img id="qr-crop-image" src="{{ asset('images/qrcode-temp.png') }}" alt="QR screenshot to crop"
                    class="max-w-full">
            </div>

            <form id="qr-crop-form" action="{{ route('admin.settings.qr-crop') }}" method="post"
                class="mt-4 grid grid-cols-1 gap-y-4">
                @csrf
                <input type="hidden" name="crop_x" id="crop_x" value="">
                <input type="hidden" name="crop_y" id="crop_y" value="">
                <input type="hidden" name="crop_width" id="crop_width" value="">
                <input type="hidden" name="crop_height" id="crop_height" value="">

                <div>
                    <label class="mb-1.5 block text-xs font-bold uppercase tracking-[0.12em] text-black" for="qr-account-name">Account
                        name</label>
                    <input id="qr-account-name" type="text" name="qr_account_name"
                        value="{{ old('qr_account_name', Setting::get('qr_account_name')) }}" class="{{ $field }}">
                    @error('qr_account_name')
                        <p class="mt-1.5 text-sm font-medium text-red-700">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label class="mb-1.5 block text-xs font-bold uppercase tracking-[0.12em] text-black" for="qr-account-number">Account
                        number</label>
                    <input id="qr-account-number" type="text" name="qr_account_number"
                        value="{{ old('qr_account_number', Setting::get('qr_account_number')) }}" class="{{ $field }}">
                    @error('qr_account_number')
                        <p class="mt-1.5 text-sm font-medium text-red-700">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label class="mb-1.5 block text-xs font-bold uppercase tracking-[0.12em] text-black" for="qr-payment-label">Payment
                        label</label>
                    <input id="qr-payment-label" type="text" name="qr_payment_label"
                        value="{{ old('qr_payment_label', Setting::get('qr_payment_label')) }}" class="{{ $field }}">
                    @error('qr_payment_label')
                        <p class="mt-1.5 text-sm font-medium text-red-700">{{ $message }}</p>
                    @enderror
                </div>
                <button type="submit"
                    class="inline-flex w-full items-center justify-center rounded-lg border border-panel-primary bg-panel-primary px-4 py-2.5 text-xs font-semibold uppercase tracking-[0.1em] text-white shadow-sm transition hover:bg-panel-primary-hover focus:outline-none focus-visible:ring-2 focus-visible:ring-panel-primary focus-visible:ring-offset-2">
                    Save QR code
                </button>
            </form>
        </div>
    @endif
</div>
