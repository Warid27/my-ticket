<?php
/**
 * Format number to short notation (e.g., 1000 -> 1k, 10000 -> 10k)
 *
 * @param int|float $number The number to format
 * @param int       $precision Decimal places for millions/billions (default: 1)
 * @return string Formatted number
 */
function formatShortNumber(int|float $number, int $precision = 1): string
{
    $negative = $number < 0;
    $number   = abs($number);

    $formatted = match (true) {
        $number >= 1_000_000_000 => number_format($number / 1_000_000_000, $precision) . 'B',
        $number >= 1_000_000     => number_format($number / 1_000_000, $precision) . 'M',
        $number >= 1_000         => number_format($number / 1_000, $precision) . 'k',
        default                  => number_format($number, is_float($number) ? $precision : 0),
    };

    // Strip unnecessary trailing zeros after decimal point (e.g., "1.0k" -> "1k")
    $formatted = preg_replace('/(\.\d*?)0+([kMB])$/', '$1$2', $formatted);
    $formatted = preg_replace('/\.([kMB])$/', '$1', $formatted);

    return ($negative ? '-' : '') . $formatted;
}