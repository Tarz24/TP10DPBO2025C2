<?php
// includes/helpers.php

/**
 * Fungsi untuk sanitasi input
 */
function sanitize($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

/**
 * Format angka ke mata uang Rupiah
 */
function formatRupiah($angka) {
    return "Rp " . number_format($angka, 0, ',', '.');
}

/**
 * Format tanggal Indonesia
 */
function formatTanggal($tanggal) {
    $bulan = array(
        1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
        5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
        9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
    );
    
    $split = explode('-', date('Y-n-j', strtotime($tanggal)));
    return $split[2] . ' ' . $bulan[(int)$split[1]] . ' ' . $split[0];
}

/**
 * Get status badge class
 */
function getStatusBadge($status) {
    switch($status) {
        case 'pending':
            return 'bg-warning';
        case 'preparing':
            return 'bg-info';
        case 'served':
            return 'bg-success';
        case 'paid':
            return 'bg-secondary';
        default:
            return 'bg-primary';
    }
}

/**
 * Get status text in Indonesian
 */
function getStatusText($status) {
    switch($status) {
        case 'pending':
            return 'Menunggu';
        case 'preparing':
            return 'Diproses';
        case 'served':
            return 'Disajikan';
        case 'paid':
            return 'Dibayar';
        default:
            return ucfirst($status);
    }
}

/**
 * Generate alert HTML
 */
function showAlert($type, $message) {
    $alertClass = ($type == 'success') ? 'alert-success' : 'alert-danger';
    return '<div class="alert ' . $alertClass . ' alert-dismissible fade show" role="alert">
                ' . $message . '
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>';
}
?>