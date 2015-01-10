<?php

    class CustomersTableSeeder extends DatabaseSeeder {
        public function run() {
            $customers = array(
                array(
                    'last_name'     => 'Jones',
                    'first_name'    => 'Tim',
                    'telephone1'    => '(918) 555 1212',
                    'email'         => 'tdjones@example.com',
                    'password'      => Hash::make('password'),
                    'admin_ind'     => TRUE,
                ),
                array(
                    'last_name'     => 'User',
                    'first_name'    => 'Joe',
                    'telephone1'    => '(918) 555 1212',
                    'email'         => 'joe@example.com',
                    'password'      => Hash::make('password'),
                    'admin_ind'     => FALSE,
                ),
                array(
                    'last_name'     => 'vincent',
                    'first_name'    => 'jan michael',
                    'telephone1'    => '(918) 555 7890',
                    'email'         => 'jmvincent@example.com',
                    'password'      => Hash::make('password'),
                    'admin_ind'     => FALSE,
                ),                
            );
            
            $addresses = array(
                array(
                    'addr1'         => '123 My Street',
                    'city'          => 'Owasso',
                    'state'         => 'OK',
                    'postal_code'   => '74055',
                    'country'       => 'USA',
                ),
                array(
                    'addr1'         => '123 Joe\'s Street',
                    'city'          => 'Owasso',
                    'state'         => 'OK',
                    'postal_code'   => '74055',
                    'country'       => 'USA',
                ),
                array(
                    'addr1'         => '456 Oak Street',
                    'city'          => 'Tulsa',
                    'state'         => 'OK',
                    'postal_code'   => '74102',
                    'country'       => 'USA',
                ),
                array(
                    'addr1'         => '888 Yellow Lane',
                    'addr2'         => 'c/o Sacramento Church of Christ',
                    'city'          => 'Sacramento',
                    'state'         => 'CA',
                    'postal_code'   => '99999',
                    'country'       => 'USA',
                ),
            );
            
            foreach ( $customers as $customer ) {
                
                $customer['password_confirmation'] = $customer['password'];
                $newcust = Customer::create($customer);
                //var_dump($customer);
                
                // Choose a random address from the list and insert it for the new customer.
                $address = $addresses[mt_rand(0, count($addresses) - 1)];
                $address['customer_id'] = $newcust->id;
                Address::create($address);
            }
        }
    }

