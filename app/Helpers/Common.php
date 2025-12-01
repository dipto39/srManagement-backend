<?php 

// Helper function to create a URL-friendly slug from a string

function makeSlug($string)
{
    return strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $string)));
}

// Helper function to generate a random code
function randomCode($length = 8)
{
    return strtoupper(substr(str_shuffle("0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, $length));
}

// Helper function to format a date
function formatDate($date, $format = 'Y-m-d H:i:s')
{
    return \Carbon\Carbon::parse($date)->format($format);
}

// Helper function to clean a string by removing special characters
function cleanText($text)
{
    return preg_replace('/[^A-Za-z0-9\s]/', '', $text);
}


