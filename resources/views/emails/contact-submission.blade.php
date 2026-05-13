<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<style>
  body { margin: 0; padding: 0; background: #f5f0e8; font-family: Georgia, serif; color: #1a1410; }
  .wrap { max-width: 600px; margin: 40px auto; background: #fff; border: 1px solid #e0d8cc; }
  .header { background: #1a1410; padding: 32px 40px; }
  .header-brand { color: #c8a96e; font-size: 22px; letter-spacing: 0.12em; text-transform: uppercase; }
  .header-sub { color: #8a7a6a; font-size: 12px; letter-spacing: 0.08em; margin-top: 4px; }
  .body { padding: 40px; }
  .label { font-family: monospace; font-size: 10px; letter-spacing: 0.14em; text-transform: uppercase; color: #8a7a6a; margin-bottom: 4px; }
  .value { font-size: 15px; color: #1a1410; margin-bottom: 24px; line-height: 1.6; }
  .divider { border: none; border-top: 1px solid #e0d8cc; margin: 8px 0 24px; }
  .message-box { background: #faf7f2; border-left: 3px solid #c8a96e; padding: 16px 20px; margin-bottom: 24px; }
  .footer { background: #faf7f2; padding: 20px 40px; border-top: 1px solid #e0d8cc; font-size: 12px; color: #8a7a6a; }
</style>
</head>
<body>
<div class="wrap">
  <div class="header">
    <div class="header-brand">Nusagrade</div>
    <div class="header-sub">New contact inquiry received</div>
  </div>
  <div class="body">
    <div class="label">Full Name</div>
    <div class="value">{{ $data['name'] }}</div>

    @if (!empty($data['company']))
    <div class="label">Company</div>
    <div class="value">{{ $data['company'] }}</div>
    @endif

    <div class="label">Email</div>
    <div class="value"><a href="mailto:{{ $data['email'] }}" style="color:#c8a96e;">{{ $data['email'] }}</a></div>

    @if (!empty($data['phone']))
    <div class="label">Phone / WhatsApp</div>
    <div class="value">{{ $data['phone'] }}</div>
    @endif

    <hr class="divider">

    <div class="label">Message</div>
    <div class="message-box">{{ $data['message'] }}</div>

    <p style="font-size:13px;color:#8a7a6a;margin:0;">
      Reply directly to this email to respond to {{ $data['name'] }}.
    </p>
  </div>
  <div class="footer">
    &copy; {{ date('Y') }} Nusagrade &mdash; This is an automated notification from your website contact form.
  </div>
</div>
</body>
</html>
