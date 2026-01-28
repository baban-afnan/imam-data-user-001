<?php

namespace App\Helpers;

class removePartsWithDataHelper
{
    function removePartsWithData($sentence) {
    // Regular expression to match any word or sequence containing 'data'
    $pattern = '/\b\w*data\w*\b/i';
    
    // Replace matches with an empty string
    $result = preg_replace($pattern, '', $sentence);
    
    // Remove extra spaces
    $result = preg_replace('/\s+/', ' ', $result);
    
    return trim($result);
}

}
