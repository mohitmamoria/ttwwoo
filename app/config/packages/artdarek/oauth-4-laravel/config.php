<?php 

return array( 
	
	/*
	|--------------------------------------------------------------------------
	| oAuth Config
	|--------------------------------------------------------------------------
	*/

	/**
	 * Storage
	 */
	'storage' => 'Session', 

	/**
	 * Consumers
	 */
	'consumers' => array(

		/**
		 * Facebook
		 */
        'Facebook' => array(
            'client_id'     => '1419109584969080',
            'client_secret' => '2a53ef5b018f55f116da1728b1ca437a',
            'scope'         => array('email', 'photo_upload', 'publish_actions'),
        ),

	)

);