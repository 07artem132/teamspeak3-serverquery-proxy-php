<?php
/**
 * Created by PhpStorm.
 * User: Artem
 * Date: 15.10.2017
 * Time: 0:44
 */

namespace Proxy;


class TeamSpeakComandBild {

	public static function Login( string $login, string $passwd ): string {
		return "login client_login_name=$login client_login_password=$passwd\r\n";
	}

}