<?php

return array(
    'current_workshop_year' => 2015,
    'last_pickup_order_date' => '2015-03-15',
    'last_preorder_discount_date' => '2015-03-18',
    'last_free_cd_discount_date' => '2015-04-21',
    'preorder_discount' => 0.10,
    'shipping_options' => array(
        'ship_together' => 'Ship CDs and DVDs together', 
        'ship_separately' => 'Ship CDs and DVDs separately', 
        'ship_dvd_only' => 'Pick up CDs at Workshop/Ship DVDs', 
        'ship_cd' => 'Ship CDs',
        'pickup' => 'Pick up CDs at Workshop',
        'ship_dvd' => 'Ship DVDs',
        'mp3_only' => 'MP3s only',
    ),
    'minimum_shipping_charge' => 4.0,
    'maximum_shipping_charge' => 10.0,
    'free_cd_count' => 6,
    'unit_price_list' => array(
        'CD' => 7.0,
        'DVD' => 12.0,
        'MP3' => 3.0,
    ),
    'paypal_acct_email' => 'orders@workshopmultimedia.com',
    'dummy_customer_password' => '-999',
    's3_bucket_list' => array(
        2015 => 'workshop-2015',
        2014 => 'workshop-2014',
        2013 => 'workshop-2013',
        2012 => 'workshop-2012',
        2011 => 'workshop-2011',
        2010 => 'workshop-2010',
        2009 => 'workshop-2009',
        2008 => 'workshop-2008',
        'free' => 'workshop-free',
    ),
    's3_file_name_format' => array(
        2008 => array('heading' => 'Tulsa_Workshop_2008_', 'ext' => '_128kbps.mp3'),
        2009 => array('heading' => 'Tulsa_Workshop_2009_', 'ext' => '_128kbps.mp3'),
        2010 => array('heading' => 'Tulsa_Workshop_2010_', 'ext' => '_128kbps.mp3'),
        2011 => array('heading' => 'Tulsa_Workshop_2011_', 'ext' => '_64kbps.mp3'),
        2012 => array('heading' => 'Tulsa_Workshop_2012_', 'ext' => '_64kbps.mp3'),
        2013 => array('heading' => 'Tulsa_Workshop_2013_', 'ext' => '_64kbps.mp3'),
        2014 => array('heading' => '', 'ext' => '_64kbps.mp3'),
        2015 => array('heading' => '', 'ext' => '_64kbps.mp3'),
    ),
    'mp3_download_link_expiry' => 3,  // Days
);

