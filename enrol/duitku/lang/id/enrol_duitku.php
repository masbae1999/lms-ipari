<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * Contains all the strings used in the plugin - Indonesian language
 * @package   enrol_duitku
 * @copyright 2022 Michael David <mikedh2612@gmail.com>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

$string['pluginname'] = 'Pembayaran Duitku';
$string['pluginname_desc'] = 'Modul Duitku memungkinkan Anda mengatur kursus berbayar. Jika biaya untuk kursus adalah nol, siswa tidak diminta untuk membayar untuk masuk. Ada biaya seluruh situs yang Anda tetapkan di sini sebagai default untuk seluruh situs dan kemudian pengaturan kursus yang dapat Anda tetapkan untuk setiap kursus secara individual. Biaya kursus menggantikan biaya situs.';

// Membership strings
$string['membership'] = 'Keanggotaan Tahunan';
$string['membership_desc'] = 'Keanggotaan tahunan memungkinkan akses ke semua kursus berbayar di situs.';
$string['membership_price'] = 'Harga keanggotaan';
$string['membership_price_desc'] = 'Harga untuk keanggotaan tahunan dalam IDR';
$string['membership_settings'] = 'Pengaturan Keanggotaan';
$string['membership_dashboard'] = 'Dasbor Keanggotaan';
$string['membership_dashboard_title'] = 'Dasbor Keanggotaan';
$string['annual_membership'] = 'Keanggotaan Tahunan';
$string['membership_role_name'] = 'Penyuluh Agama';
$string['expires_on'] = 'Berakhir pada:';
$string['days_remaining'] = 'hari tersisa';
$string['renewal_notice'] = 'Keanggotaan Anda akan segera berakhir. Perpanjang sekarang untuk mempertahankan akses ke semua kursus.';
$string['renew_now'] = 'Perpanjang Keanggotaan';
$string['subscribe_now'] = 'Berlangganan Sekarang';
$string['active'] = 'Aktif';
$string['expired'] = 'Berakhir';
$string['not_subscribed'] = 'Belum Berlangganan';
$string['membership_active'] = 'Keanggotaan Aktif';
$string['membership_expires'] = 'Berakhir:';
$string['membership_subscribe'] = 'Langganan Keanggotaan Tahunan';
$string['membership_benefits'] = 'Manfaat:';
$string['membership_benefit1'] = 'Akses ke semua kursus berbayar';
$string['membership_benefit2'] = 'Kursus baru ditambahkan secara otomatis';
$string['membership_benefit3'] = 'Akses khusus ke sumber daya anggota';

// Transaction verification strings
$string['verify_transaction'] = 'Verifikasi Transaksi';
$string['verify'] = 'Verifikasi';
$string['reference_code'] = 'Kode Referensi';
$string['transaction_not_found'] = 'Transaksi tidak ditemukan';
$string['back_to_dashboard'] = 'Kembali ke Dasbor';
$string['check_status_now'] = 'Periksa Status Sekarang';
$string['not_your_transaction'] = 'Transaksi ini tidak terkait dengan akun Anda';
$string['transaction_details'] = 'Detail Transaksi';
$string['merchantorder_id'] = 'ID Order Merchant';
$string['amount'] = 'Jumlah';
$string['payment_time'] = 'Waktu Pembayaran';
$string['payment_status'] = 'Status Pembayaran';
$string['payment_type'] = 'Jenis Pembayaran';
$string['status_success'] = 'Berhasil';
$string['status_pending'] = 'Tertunda';
$string['status_failed'] = 'Gagal';
$string['course_enrollment'] = 'Pendaftaran Kursus';
$string['recent_logs'] = 'Log Transaksi Terbaru';
$string['time'] = 'Waktu';
$string['type'] = 'Tipe';
$string['status'] = 'Status';
$string['active_membership'] = 'Anda memiliki keanggotaan aktif';
$string['no_active_membership'] = 'Anda tidak memiliki keanggotaan aktif';
$string['verify_transaction_explanation'] = 'Jika Anda telah menyelesaikan pembayaran tetapi keanggotaan Anda tidak aktif, Anda dapat memeriksa statusnya lagi:';
$string['check_status_now'] = 'Periksa Status Sekarang';
$string['membership_activated'] = 'Keanggotaan Anda telah berhasil diaktifkan';
$string['membership_check_failed'] = 'Pembayaran terverifikasi, tetapi aktivasi keanggotaan gagal. Silakan hubungi dukungan.';
$string['course_enrollment_transaction'] = 'Ini adalah transaksi pendaftaran kursus, bukan pembayaran keanggotaan';
$string['transaction_status_error'] = 'Status transaksi dari Duitku: {$a}';
$string['thank_you_membership'] = 'Terima Kasih atas Keanggotaan Anda';
$string['membership_payment'] = 'Pembayaran Keanggotaan';
$string['membership_payment_success'] = 'Pembayaran keanggotaan Anda berhasil. Anda sekarang memiliki akses ke semua kursus berbayar.';
$string['membership_payment_pending'] = 'Pembayaran keanggotaan Anda sedang diproses. Harap tunggu beberapa saat atau periksa status Anda nanti.';
$string['gotodashboard'] = 'Pergi ke Dasbor';
$string['view_benefits'] = 'Lihat detail keanggotaan';
$string['per_year'] = 'per tahun';
$string['membership_extended'] = 'Keanggotaan berhasil diperpanjang';
$string['membership_revoked'] = 'Keanggotaan berhasil dicabut';
$string['membership_created'] = 'Keanggotaan berhasil dibuat';
$string['error_creating_membership'] = 'Kesalahan saat membuat keanggotaan';
$string['error_user_mismatch'] = 'Kesalahan ketidakcocokan pengguna';
$string['thank_you_membership'] = 'Terima kasih telah berlangganan keanggotaan tahunan kami!';
$string['membership_payment_success'] = 'Pembayaran keanggotaan Anda berhasil. Anda sekarang memiliki akses ke semua kursus berbayar.';
$string['membership_payment_pending'] = 'Pembayaran keanggotaan Anda tertunda. Setelah selesai, Anda akan memiliki akses ke semua kursus berbayar.';
$string['days_remaining'] = 'hari tersisa';
$string['membership_payment'] = 'Pembayaran Keanggotaan';
$string['membership_return'] = 'Kembali dari Pembayaran Keanggotaan';
$string['checking_status'] = 'Memeriksa status pembayaran...';
$string['checking_payment'] = 'Memeriksa status pembayaran, halaman akan disegarkan dalam:';
$string['gotodashboard'] = 'Kembali ke Dasbor';
$string['renewal_notice'] = 'Keanggotaan Anda akan segera berakhir. Perpanjang sekarang untuk mempertahankan akses tanpa gangguan.';
$string['error_already_subscribed'] = 'Anda sudah memiliki keanggotaan aktif.';
$string['error_user_mismatch'] = 'ID pengguna tidak cocok. Silakan coba lagi.';
$string['continue'] = 'Lanjutkan';
$string['membership_title'] = 'Keanggotaan Tahunan';
$string['membership_heading'] = 'Langganan Keanggotaan Tahunan';
$string['membership_annual'] = 'Keanggotaan Tahunan';
$string['per_year'] = 'per tahun';
$string['active_membership'] = 'Keanggotaan Anda aktif';
$string['no_active_membership'] = 'Anda tidak memiliki keanggotaan aktif';
$string['can_renew_now'] = 'Anda dapat memperpanjang keanggotaan Anda sekarang untuk memperpanjangnya.';
$string['membership_product_name'] = 'Keanggotaan Tahunan - Penyuluh Agama';
$string['payment_error'] = 'Terjadi kesalahan saat memproses pembayaran Anda. Silakan coba lagi.';
$string['payment_plugin_disabled'] = 'Plugin pembayaran Duitku dinonaktifkan.';

// Keep other strings from the English version or translate as needed
