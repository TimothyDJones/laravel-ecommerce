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
}

