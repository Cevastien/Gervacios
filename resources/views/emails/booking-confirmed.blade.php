<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Booking confirmed</title>
</head>
<body style="margin:0;padding:0;background-color:#f4f4f5;font-family:Georgia,'Times New Roman',serif;-webkit-font-smoothing:antialiased;">
    <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="background-color:#f4f4f5;padding:32px 16px;">
        <tr>
            <td align="center">
                <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="max-width:520px;background-color:#ffffff;border-radius:8px;overflow:hidden;box-shadow:0 1px 3px rgba(0,0,0,0.08);">
                    <tr>
                        <td style="padding:28px 28px 8px 28px;">
                            <p style="margin:0;font-size:13px;letter-spacing:0.12em;text-transform:uppercase;color:#78716c;">{{ $venueName }}</p>
                            <h1 style="margin:12px 0 0 0;font-size:22px;font-weight:600;color:#1c1917;line-height:1.3;">Your booking is confirmed</h1>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding:8px 28px 24px 28px;">
                            <p style="margin:0 0 16px 0;font-size:15px;line-height:1.6;color:#44403c;">
                                Hi {{ $customerName }},
                            </p>
                            <p style="margin:0 0 20px 0;font-size:15px;line-height:1.6;color:#44403c;">
                                Thank you for your reservation. Here are your details:
                            </p>
                            <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="border:1px solid #e7e5e4;border-radius:6px;background-color:#fafaf9;">
                                <tr>
                                    <td style="padding:16px 18px;">
                                        <p style="margin:0 0 10px 0;font-size:12px;text-transform:uppercase;letter-spacing:0.06em;color:#78716c;">Reference</p>
                                        <p style="margin:0;font-size:18px;font-weight:600;letter-spacing:0.04em;color:#1c1917;font-family:ui-monospace,Menlo,Monaco,Consolas,monospace;">{{ $bookingRef }}</p>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="padding:0 18px 16px 18px;border-top:1px solid #e7e5e4;">
                                        <table role="presentation" width="100%" cellspacing="0" cellpadding="0">
                                            <tr>
                                                <td style="padding-top:14px;width:50%;vertical-align:top;">
                                                    <p style="margin:0 0 6px 0;font-size:12px;text-transform:uppercase;letter-spacing:0.06em;color:#78716c;">Date &amp; time</p>
                                                    <p style="margin:0;font-size:15px;color:#292524;line-height:1.5;">
                                                        {{ $bookedAt->timezone(config('app.timezone'))->format('l, M j, Y') }}<br>
                                                        <span style="color:#57534e;">{{ $bookedAt->timezone(config('app.timezone'))->format('g:i A') }}</span>
                                                    </p>
                                                </td>
                                                <td style="padding-top:14px;width:50%;vertical-align:top;">
                                                    <p style="margin:0 0 6px 0;font-size:12px;text-transform:uppercase;letter-spacing:0.06em;color:#78716c;">Party size</p>
                                                    <p style="margin:0;font-size:15px;color:#292524;">{{ $partySize }} {{ $partySize === 1 ? 'guest' : 'guests' }}</p>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>
                            <p style="margin:22px 0 0 0;font-size:14px;line-height:1.6;color:#57534e;border-left:3px solid #d6d3d1;padding-left:14px;">
                                Please present your <strong>booking reference</strong> when you arrive so we can seat you without delay.
                            </p>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding:0 28px 28px 28px;">
                            <p style="margin:0;font-size:13px;color:#a8a29e;">We look forward to seeing you at {{ $venueName }}.</p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
