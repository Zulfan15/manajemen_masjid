<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;
use Carbon\Carbon;

class TransaksiDiterimaNotification extends Notification
{
    use Queueable;

    public $pemasukan;
    public $jenisTransaksi;

    /**
     * Create a new notification instance.
     */
    public function __construct($pemasukan, $jenisTransaksi = null)
    {
        $this->pemasukan = $pemasukan;
        $this->jenisTransaksi = $jenisTransaksi ?? 'Transaksi';
    }

    /**
     * Get the notification's delivery channels.
     */
    public function via($notifiable)
    {
        return ['mail', 'database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail($notifiable)
    {
        $jumlah = number_format($this->pemasukan->jumlah, 0, ',', '.');
        $tanggal = Carbon::parse($this->pemasukan->tanggal)->format('d F Y');
        $idTransaksi = 'TRX-' . str_pad($this->pemasukan->id, 6, '0', STR_PAD_LEFT);
        $tanggalVerifikasi = now()->format('d F Y, H:i') . ' WIB';

        return (new MailMessage)
            ->subject('✅ Transaksi Terverifikasi - Manajemen Masjid')
            ->greeting('Assalamualaikum Warahmatullahi Wabarakatuh')
            ->line('Kepada Yth. **' . $notifiable->name . '**,')
            ->line('')
            ->line('Alhamdulillah, kami informasikan bahwa transaksi Anda **telah berhasil diverifikasi** oleh admin.')
            ->line('')
            ->line('### 📋 DETAIL TRANSAKSI')
            ->line('**ID Transaksi:** ' . $idTransaksi)
            ->line('**Jenis:** ' . $this->jenisTransaksi)
            ->line('**Nominal:** Rp ' . $jumlah)
            ->line('**Tanggal Transaksi:** ' . $tanggal)
            ->line('**Tanggal Verifikasi:** ' . $tanggalVerifikasi)
            ->line('**Status:** ✅ TERVERIFIKASI')
            ->line('')
            ->action('📊 Lihat Detail Transaksi', url('/pemasukan'))
            ->line('')
            ->line('Terima kasih atas kontribusi Anda untuk kemajuan masjid.')
            ->line('Semoga amal ibadah ini diterima oleh Allah SWT dan menjadi pahala yang berlipat ganda.')
            ->line('')
            ->line('### 🤲 Doa')
            ->line('*"Ya Allah, terimalah amal kami, sesungguhnya Engkau Maha Mendengar lagi Maha Mengetahui."*')
            ->line('')
            ->salutation('Wassalamualaikum Warahmatullahi Wabarakatuh,  
**Tim Manajemen Masjid**');
    }

    /**
     * Get the array representation of the notification (untuk database).
     */
    public function toArray($notifiable)
    {
        return [
            'title' => 'Transaksi Diverifikasi',
            'message' => 'Transaksi ' . $this->jenisTransaksi . ' sebesar Rp ' . 
                        number_format($this->pemasukan->jumlah, 0, ',', '.') . 
                        ' telah diverifikasi',
            'transaksi_id' => $this->pemasukan->id,
            'jumlah' => $this->pemasukan->jumlah,
            'jenis' => $this->jenisTransaksi,
            'link' => '/pemasukan',
            'icon' => 'check-circle',
            'color' => 'success',
        ];
    }
}