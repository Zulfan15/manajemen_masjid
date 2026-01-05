<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class TransaksiDiterimaNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public $pemasukan;
    public $jenisTransaksi;
    public $adminName;

    public function __construct($pemasukan, $jenisTransaksi, $adminName = null)
    {
        $this->pemasukan = $pemasukan;
        $this->jenisTransaksi = $jenisTransaksi;
        $this->adminName = $adminName ?? 'Admin Masjid';
    }

    public function via($notifiable)
    {
        return ['mail', 'database'];
    }

    public function toMail($notifiable)
    {
        $jumlah = number_format($this->pemasukan->jumlah, 0, ',', '.');
        $tanggal = \Carbon\Carbon::parse($this->pemasukan->tanggal)->format('d F Y');
        $idTransaksi = 'TRX-' . str_pad($this->pemasukan->id, 6, '0', STR_PAD_LEFT);
        $tanggalVerifikasi = now()->format('d F Y H:i');

        return (new MailMessage)
            ->subject('✅ TRANSAKSI BERHASIL - Manajemen Masjid')
            ->greeting('**Assalamualaikum Warahmatullahi Wabarakatuh**')
            ->line('')
            ->line('Kepada Yth. **' . $notifiable->name . '**')
            ->line('')
            ->line('Kami informasikan bahwa transaksi Anda **telah berhasil diverifikasi** oleh admin.')
            ->line('')
            ->line('**📋 DETAIL TRANSAKSI**')
            ->line('┌──────────────────────────────────────')
            ->line('│ **ID Transaksi:** ' . $idTransaksi)
            ->line('│ **Jenis Transaksi:** ' . $this->jenisTransaksi)
            ->line('│ **Nominal:** **Rp ' . $jumlah . '**')
            ->line('│ **Tanggal Transaksi:** ' . $tanggal)
            ->line('│ **Tanggal Verifikasi:** ' . $tanggalVerifikasi)
            ->line('│ **Diverifikasi oleh:** ' . $this->adminName)
            ->line('│ **Status:** ✅ **TERVERIFIKASI**')
            ->line('└──────────────────────────────────────')
            ->line('')
            ->line('Terima kasih atas kontribusi Anda untuk perkembangan Masjid kami.')
            ->line('Semoga amal ibadah ini diterima oleh Allah SWT dan menjadi pahala yang berlipat.')
            ->line('')
            ->line('**🤲 Doa:**')
            ->line('*"Ya Allah, terimalah amal kami, sesungguhnya Engkau Maha Mendengar lagi Maha Mengetahui."*')
            ->line('')
            ->line('**Wassalamualaikum Warahmatullahi Wabarakatuh**')
            ->salutation('**Tim Manajemen Masjid**')
            ->action('📊 Lihat Dashboard Transaksi', url('/pemasukan'))
            ->line('')
            ->line('*Email ini dikirim secara otomatis, mohon tidak membalas email ini.*');
    }

    public function toDatabase($notifiable)
    {
        return [
            'title' => 'Transaksi Diverifikasi',
            'message' => 'Transaksi ' . $this->jenisTransaksi . ' sebesar Rp ' . 
                        number_format($this->pemasukan->jumlah, 0, ',', '.') . 
                        ' telah diverifikasi',
            'link' => '/pemasukan/' . $this->pemasukan->id,
            'icon' => 'check-circle',
            'color' => 'success',
            'transaksi_id' => $this->pemasukan->id,
            'jumlah' => $this->pemasukan->jumlah,
            'jenis' => $this->jenisTransaksi
        ];
    }
}