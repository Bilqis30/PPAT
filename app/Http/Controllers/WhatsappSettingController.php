<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class WhatsappSettingController extends Controller
{
    /**
     * Tampilkan halaman formulir pengaturan WhatsApp Gateway.
     */
    public function edit(): View
    {
        $settings = [
            'wa_gateway_url' => Setting::get('wa_gateway_url', 'https://api.fonnte.com/send'),
            'wa_gateway_token' => Setting::get('wa_gateway_token', ''),
            'wa_recipient_number' => Setting::get('wa_recipient_number', ''),
            'wa_is_active' => filter_var(Setting::get('wa_is_active', '1'), FILTER_VALIDATE_BOOLEAN),
            'wa_reminder_days' => (int) Setting::get('wa_reminder_days', '2'),
            'wa_reminder_time' => Setting::get('wa_reminder_time', '07:00'),
        ];

        return view('admin.settings.whatsapp', compact('settings'));
    }

    /**
     * Simpan pembaruan pengaturan WhatsApp Gateway ke database.
     */
    public function update(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'wa_gateway_url' => ['nullable', 'url', 'max:255'],
            'wa_gateway_token' => ['nullable', 'string', 'max:255'],
            'wa_recipient_number' => ['nullable', 'string', 'max:50'],
            'wa_reminder_days' => ['required', 'integer', 'min:0', 'max:30'],
            'wa_reminder_time' => ['required', 'date_format:H:i'],
        ]);

        Setting::set('wa_gateway_url', $validated['wa_gateway_url'] ?? 'https://api.fonnte.com/send');
        Setting::set('wa_gateway_token', $validated['wa_gateway_token'] ?? '');
        Setting::set('wa_recipient_number', $validated['wa_recipient_number'] ?? '');
        Setting::set('wa_reminder_days', (string) $validated['wa_reminder_days']);
        Setting::set('wa_reminder_time', $validated['wa_reminder_time']);

        $isActive = $request->has('wa_is_active') ? '1' : '0';
        Setting::set('wa_is_active', $isActive);

        return redirect()
            ->route('admin.settings.whatsapp')
            ->with('success', 'Pengaturan WhatsApp Gateway berhasil diperbarui.');
    }
}
