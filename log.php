<?php

/**
 * Created by PhpStorm.
 * User: Artem
 * Date: 14.10.2017
 * Time: 16:10
 */

namespace Proxy;

class  Log {
	private static $logPath = __DIR__;

	private static $MaskMainLog = '| %20.20s | %-5.6s | %-120.120s' . PHP_EOL;
	private static $MaskProxyLog = '| %20.20s | %-15.15s | %-20.40s | %s' . PHP_EOL;

	public static function RecvData( string $ServerIp, string $ClientIP, string $login, string $message ): void {
		$data = sprintf( self::$MaskProxyLog, date( "Y-m-d H:i:s" ), $ClientIP, $login, trim( $message ) );
		self::WriteProxyInstanseRecvData( $ServerIp, $data );

		return;
	}

	public static function debug( string $message ): void {
		$data = sprintf( self::$MaskMainLog, date( "Y-m-d H:i:s" ), 'debug', $message );
		self::WriteMainLog( $data );

	}

	public static function info( string $message ): void {
		$data = sprintf( self::$MaskMainLog, date( "Y-m-d H:i:s" ), 'info', $message );
		self::WriteMainLog( $data );

	}

	public static function warn( string $message ): void {
		$data = sprintf( self::$MaskMainLog, date( "Y-m-d H:i:s" ), 'warn', $message );
		self::WriteMainLog( $data );

	}

	public static function error( string $message ): void {
		$data = sprintf( self::$MaskMainLog, date( "Y-m-d H:i:s" ), 'error', $message );
		self::WriteMainLog( $data );

	}

	private static function WriteProxyInstanseRecvData( $ServerIP, $data ) {
		file_put_contents( self::$logPath . '/' . $ServerIP, $data, FILE_APPEND | LOCK_EX );
		echo $data;
	}

	private static function WriteMainLog( $data ) {
		echo $data;
	}
}