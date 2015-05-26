<?php

class Utility {
    
    
	/**
	 * Reduce length of string to fixed length with ellipsis ('...').
	 *
	 * @param  string  $string
         * @param  int $max_length
	 * @return truncated string
	 */    
        public static function truncateStringWithEllipsis($string, $max_length) {
            if (strlen($string) > ($max_length - 3)) {
                // Truncate string on word or line break.
                $parts = preg_split('/([\s\n\r]+)/', $string, null, PREG_SPLIT_DELIM_CAPTURE);
                $parts_count = count($parts);

                $length = 0;
                $last_part = 0;
                for (; $last_part < $parts_count; ++$last_part) {
                  $length += strlen($parts[$last_part]);
                  if ($length > ($max_length - 3)) { break; }
                }

                $string = implode(array_slice($parts, 0, $last_part)) . '...';
            }
            
            return $string;
        }
        
	/**
	 * Format value as currency, with two decimal places and
         * optional currency symbol.
	 *
	 * @param  float $value
         * @param  string $currencySymbol
	 * @return formatted string
	 */        
        public static function formatCurrency($value, $currencySymbol = '') {
            
        }
        
	/**
	 * Check to see if currently logged on user or specific user (customer)
         * is an administrative user (Customer::admin_ind == TRUE).
	 *
	 * @param  Customer $customer (optional)
	 * @return Boolean
	 */            
        public static function isAdminUser(Customer $customer = NULL) {
            
            // If no user passed in, then use the currently logged on user.
            if ( is_null($customer) ) {
                return (Auth::check() && Auth::user()->admin_ind);
            } else {
                return (User::find($customer->id)->admin_ind);
            }
        }

	/**
	 * Call ZIP Code web service (http://www.zippopotam.us/) to get
         * city/state for address.
	 *
	 * @param  string $postalCode
         * @param  string $countryCode (optional)
	 * @return mixed (array of locality data)
	 */          
        public static function getLocalityFromPostalCode($postalCode, $countryCode = 'US') {
            // See LaraGeo package (https://github.com/Fuhrmann/larageo-plugin/) for example
            // of how to handle web service responses.  :)
            
            $web_service_url_base = 'http://api.zippopotam.us/';
            
            $response = NULL;
            
            $url = $web_service_url_base . $countryCode . '/' . $postalCode;

            if (function_exists('curl_init')) {
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, $url);
                curl_setopt($ch, CURLOPT_TIMEOUT, 30);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                curl_setopt($ch, CURLOPT_USERAGENT, 'Workshop Multimedia');
                $response = curl_exec($ch);
                curl_close($ch);
            } elseif (ini_get('allow_url_fopen')) {
                $response = file_get_contents($url, 'r');
            } else {
                throw new \Exception('Utility::getLocalityFromPostalCode() requires the CURL PHP extension or allow_url_fopen set to 1!');
            }
            
            $response = json_decode($response, TRUE);  // Convert to array!
            if (empty($response)) {
                throw new \Exception("No data returned by web server from " . $url . '.', 100);
            }
            
            Log::debug('Response from Zippopotam.us web service for URL "' . $url . '":  ' . print_r($response, TRUE));

            return $response;
        }

	/**
	 * PHP work-alike/equivalent to Oracle NVL() function.
         * Allows user to specify a default value, when one is not specified.
	 *
	 * @param  string $val - Regular value, if provided.
         * @param  string $default (optional) - Default if $val is not specified.
	 * @return string 
	 */           
        public static function nvl($val, $default = '') {
            if ( empty($val)
                    || is_null($val)
                    || $val === '') {
                return $default;
            }
            
            return $val;
        }
        
        /**
         * Generates the default name used when downloading MP3 files.
         * 
         * @param Product $product - An instance of product.
         * @param string $prefix - Filename prefix to use; defaults to 'Tulsa_Workshop_'.
         * @return string Default file name
         */
        public static function generateDownloadFilename(Product $product, $prefix = 'Tulsa_Workshop_') {
            $filename = $prefix . 
                        $product->workshop_year . '_' . 
                        str_replace(' ', '_', $product->speaker_first_name) . '_' .
                        str_replace(' ', '_', $product->speaker_last_name) . '_' .
                        str_replace(' ', '_', $product->session_title) . '.mp3';
            
            // Strip out duplicate underscores.
            $filename = str_replace('___', '_', $filename);
            $filename = str_replace('__', '_', $filename);
            
            // Remove invalid characters from file name.
            $final_filename = '';
            for ( $i = 0; $i < strlen($filename); $i++ ) {
                if (preg_match('([0-9]|[a-z]|[A-Z]|_|\.)', $filename[$i])) {
                    $final_filename .= $filename[$i];
                }
            }
            
            return $final_filename;
        }
        
        
        /**
         * Generate AWS S3 URL for downloading MP3 file.
         * 
         * @param Product $product - An instance of product.
         * @param string $link_expiry - Duration of link validity; defaults to NULL (no expiry - AWS *PUBLIC* link).
         * @return string Default file name
         */
        public static function generateAwsS3Url(Product $product, $link_expiry = NULL) {
            
            Log::debug("generateAwsS3Url() - product attribute: " . print_r($product, TRUE));
            Log::debug("Attributes of 'product': " . $product->workshop_year . '_' 
                    . $product->speaker_first_name . '_'
                    . $product->speaker_last_name . '_'
                    . $product->session_title);
            
            $dl_filename = Utility::generateDownloadFilename($product);
            $s3Buckets = \Config::get('workshop.s3_bucket_list');

            $s3 = AWS::get('s3');
            if ( $link_expiry ) {
                $url = $s3->getObjectUrl(
                    $s3Buckets[$product->workshop_year],
                    $product->prod_code . '_64kbps.mp3',
                    $link_expiry,
                    array(
                        'ResponseContentDisposition' => 'attachment; filename="' . $dl_filename . '"',  // Force download
                        'ResponseContentType' => 'audio/mpeg, audio/x-mpeg, audio/x-mpeg-3, audio/mpeg3',      // MIME type of MP3 (RFC 3003)
                        'ResponseCacheControl' => 'no-cache',   // Prevent caching
                    )
                );
            } else {    // Unsigned link URL
                $url = $s3->getObjectUrl(
                    $s3Buckets['free'],     // Use the S3 bucket for free Workshop downloads.
                    $product->prod_code . '_64kbps.mp3'
                );
            }
            
            Log::debug("URL for product #" . $product->id . ": " . print_r($url, TRUE));
            
            return $url;
        }
}

