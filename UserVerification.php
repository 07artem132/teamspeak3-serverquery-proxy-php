<?php
/**
 * Created by PhpStorm.
 * User: Artem
 * Date: 15.10.2017
 * Time: 0:52
 */

namespace Proxy;


class UserVerification {

	private static $AccountList = [
		'test' => [
			'login'   => 'test',
			'passwd'  => '222222',
			'IpAcces' => [
				'127.0.0.1',
				'127.0.0.2'		
				]
		],
		'test2'   => [ //
			'login'   => 'test2',
			'passwd'  => '11111',
			'IpAcces' => [
				'127.0.0.1',
				'127.0.0.2'
			]
		]
	];

	public static function VerifiLoginPasswd( $login, $passwd ) {
		if ( array_key_exists( $login, self::$AccountList ) ) {
			if ( self::$AccountList[ $login ]['passwd'] === $passwd ) {
				return true;
			}
		}

		return false;
	}

	public static function VerifiAccesInstance( $login, $ip ) {
		if ( array_key_exists( $login, self::$AccountList ) ) {
			if ( array_key_exists( $ip, self::$AccountList[ $login ]['IpAcces'] ) ) {
				return true;
			}
		}

		return false;
	}
}