<?php
/**
 * Created by PhpStorm.
 * User: Artem
 * Date: 14.10.2017
 * Time: 20:12
 */

namespace Proxy;


class TeamSpeakComandParser {

	public static function Login( string $string ): ?array {
		$data = null;

		if ( preg_match( '/^login(.*?)/', $string, $matches, PREG_OFFSET_CAPTURE, 0 ) ) {
			if ( preg_match( '/client_login_name=(.*)? client_login_password=(.*)?$/', trim( $string ), $matches, PREG_OFFSET_CAPTURE, 0 ) ) {
				$data[] = $matches[1][0];
				$data[] = $matches[2][0];
			}
		}

		return $data;
	}
}