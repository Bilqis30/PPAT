<?php

namespace App\Console\Commands;

use App\Models\Document;
use App\Models\Setting;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SendEventReminder extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'notification:send-event-reminder';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Kirim notifikasi pengingat WhatsApp acara dari Surat Masuk secara langsung (synchronous/tanpa queue)';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        // 0. Cek apakah notifikasi WhatsApp diaktifkan di Pengaturan Sistem (tabel settings)
        $isActiveRaw = Setting::get('wa_is_active', '1');
        $isActive = filter_var($isActiveRaw, FILTER_VALIDATE_BOOLEAN);

        if (! $isActive) {
            $this->info('Notifikasi WhatsApp pengingat acara sedang dinonaktifkan di Pengaturan Sistem.');
            Log::info('Notifikasi WA dilewati: Status wa_is_active dinonaktifkan.');

            return Command::SUCCESS;
        }

        // 1. Ambil jumlah hari pengingat (H-X) dari database
        $daysBefore = (int) Setting::get('wa_reminder_days', '2');
        $targetDate = Carbon::now()->addDays($daysBefore)->toDateString();
        $hLabel = $daysBefore === 0 ? 'HARI H' : "H-{$daysBefore}";

        $this->info("Menjalankan pengecekan Surat Masuk untuk tanggal acara: {$targetDate} ({$hLabel})...");

        // 2. Query dokumen tipe Surat Masuk dengan tanggal_acara persis H-X
        $documents = Document::where('type', 'Surat Masuk')
            ->whereNotNull('tanggal_acara')
            ->whereDate('tanggal_acara', $targetDate)
            ->get();

        if ($documents->isEmpty()) {
            $this->info("Tidak ada agenda acara Surat Masuk untuk {$hLabel} ({$targetDate}).");

            return Command::SUCCESS;
        }

        $this->info("Ditemukan {$documents->count()} agenda Surat Masuk {$hLabel}.");

        // Config WhatsApp Gateway dari database (tabel settings) atau fallback .env
        $gatewayUrl = Setting::get('wa_gateway_url') ?: config('services.whatsapp.url', env('WA_GATEWAY_URL', 'https://api.fonnte.com/send'));
        $token = Setting::get('wa_gateway_token') ?: config('services.whatsapp.token', env('WA_GATEWAY_TOKEN'));
        $recipientNumber = Setting::get('wa_recipient_number') ?: config('services.whatsapp.recipient', env('WA_RECIPIENT_NUMBER'));

        if (empty($token) || empty($recipientNumber)) {
            $this->error('Konfigurasi WhatsApp Gateway (wa_gateway_token / wa_recipient_number) belum diisi!');
            Log::warning('Notifikasi WA dibatalkan: Konfigurasi WhatsApp belum lengkap.');

            return Command::FAILURE;
        }

        $successCount = 0;
        $failCount = 0;

        // 3. Loop & Kirim WhatsApp secara Synchronous (Http::post tanpa Queue)
        foreach ($documents as $doc) {
            $formattedDate = Carbon::parse($doc->tanggal_acara)->translatedFormat('d F Y');
            $docNumber = $doc->document_number ?? 'Tanpa Nomor Surat';

            $message = "🔔 *PENGINGAT ACARA {$hLabel} (SURAT MASUK)* 🔔\n\n"
                ."Yth. Bapak Iwan Kurniawan,\n"
                ."Berikut adalah pengingat kegiatan/acara dari Surat Masuk:\n\n"
                ."📌 *Perihal:* {$doc->title}\n"
                ."📄 *Nomor Surat:* {$docNumber}\n"
                ."📅 *Tanggal Acara:* {$formattedDate}\n"
                .'📝 *Keterangan:* '.($doc->description ?? '-')."\n\n"
                .'Mohon untuk dapat mempersiapkan hal-hal yang diperlukan. Terima kasih.';

            try {
                // Eksekusi HTTP Request secara synchronous (langsung)
                $response = Http::withHeaders([
                    'Authorization' => $token,
                ])->post($gatewayUrl, [
                    'target' => $recipientNumber,
                    'phone' => $recipientNumber, // Compatibility for Wablas / Fonnte
                    'message' => $message,
                ]);

                $resJson = $response->json();
                $isGatewaySuccess = $response->successful() && ($resJson['status'] ?? true);

                if ($isGatewaySuccess) {
                    $successCount++;
                    $this->info("✓ Notifikasi WA berhasil diproses Gateway untuk Dokumen ID {$doc->id} ('{$doc->title}')");
                    if (! empty($resJson['detail'])) {
                        $this->comment("  Response Gateway: {$resJson['detail']}");
                    }
                    Log::info("Notifikasi WA {$hLabel} terkirim secara synchronous untuk dokumen ID {$doc->id}. Response: ".$response->body());
                } else {
                    $failCount++;
                    $reason = $resJson['reason'] ?? $resJson['detail'] ?? $response->body();
                    $this->error("✗ Gagal mengirim WA Dokumen ID {$doc->id}. Response: ".$reason);
                    Log::error("Gagal kirim Notifikasi WA {$hLabel} Dokumen ID {$doc->id}: ".$response->body());
                }
            } catch (\Throwable $e) {
                $failCount++;
                $this->error("✗ Exception saat mengirim WA Dokumen ID {$doc->id}: ".$e->getMessage());
                Log::error("Exception Notifikasi WA {$hLabel} Dokumen ID {$doc->id}: ".$e->getMessage());
            }
        }

        $this->info("Proses pengiriman selesai. Berhasil: {$successCount}, Gagal: {$failCount}.");

        return Command::SUCCESS;
    }
}
