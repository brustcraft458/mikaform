<?php

if (!function_exists('formatPhone')) {
    function formatPhone($phone)
    {
        // Check if the phone number starts with '08'
        if (strpos($phone, '08') === 0) {
            // Replace '08' with '628'
            return '628' . substr($phone, 2);
        }

        // Return the phone number unchanged if it doesn't start with '08'
        return $phone;
    }
}
