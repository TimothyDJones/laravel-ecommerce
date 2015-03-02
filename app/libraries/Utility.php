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
                throw new \Exception("No data returned by web server from " . $url . '.');
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
}

