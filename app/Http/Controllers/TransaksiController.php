use App\Notifications\TransaksiDiterimaNotification;

public function verifikasiTransaksi($id)
{
    $transaksi = Pemasukan::findOrFail($id); // sesuaikan model
    
    // Update status transaksi
    $transaksi->status = 'verified';
    $transaksi->save();
    
    // Kirim notifikasi ke user
    $transaksi->user->notify(new TransaksiDiterimaNotification($transaksi));
    
    return redirect()->back()->with('success', 'Transaksi berhasil diverifikasi dan notifikasi telah dikirim');
}