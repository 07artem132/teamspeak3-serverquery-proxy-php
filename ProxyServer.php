<?php
/**
 * Created by PhpStorm.
 * User: Artem
 * Date: 14.10.2017
 * Time: 16:09
 */

namespace Proxy;

use \swoole_server;
use Swoole\Server as SwooleServer;

include __DIR__ . '/log.php';
include __DIR__ . '/TeamSpeakComandParser.php';
include __DIR__ . '/TeamSpeakComandBild.php';
include __DIR__ . '/UserVerification.php';

class  Server {
	function __construct() {
		$this->Backend[0]['login']  = 'serveradmin';
		$this->Backend[0]['passwd'] = 'JmJnbUKv';
		$this->Backend[0]['ip']     = '92.63.203.197';
		$this->Backend[0]['port']   = 55000;

		$this->Backend[1]['login']  = 'serveradmin';
		$this->Backend[1]['passwd'] = 'Qn3B0Nlm';
		$this->Backend[1]['ip']     = '92.63.203.135';
		$this->Backend[1]['port']   = 55000;

		$this->Backend[2]['login']  = 'serveradmin';
		$this->Backend[2]['passwd'] = 'y8tO3u1iBL5T';
		$this->Backend[2]['ip']     = '92.63.203.196';
		$this->Backend[2]['port']   = 55000;

		$this->Backend[3]['login']  = 'serveradmin';
		$this->Backend[3]['passwd'] = 'wu7kadrI';
		$this->Backend[3]['ip']     = '92.63.203.190';
		$this->Backend[3]['port']   = 55000;

		$this->Backend[4]['login']  = 'serveradmin';
		$this->Backend[4]['passwd'] = 'HzWcQXv2ZUn8';
		$this->Backend[4]['ip']     = '92.63.203.195';
		$this->Backend[4]['port']   = 55000;

		$this->Backend[5]['login']  = 'serveradmin';
		$this->Backend[5]['passwd'] = 'www.ry123456';
		$this->Backend[5]['ip']     = '92.63.203.175';
		$this->Backend[5]['port']   = 55000;

		$this->Backend[6]['login']  = 'serveradmin';
		$this->Backend[6]['passwd'] = 'yXRUqMEz';
		$this->Backend[6]['ip']     = '92.63.203.198';
		$this->Backend[6]['port']   = 55000;

		$this->Backend[7]['login']  = 'serveradmin';
		$this->Backend[7]['passwd'] = 'www.ry123456';
		$this->Backend[7]['ip']     = '92.63.203.151';
		$this->Backend[7]['port']   = 55000;

		$this->Backend[8]['login']  = 'serveradmin';
		$this->Backend[8]['passwd'] = 'www.ry123456';
		$this->Backend[8]['ip']     = '92.63.203.152';
		$this->Backend[8]['port']   = 55000;

		$this->Backend[9]['login']  = 'serveradmin';
		$this->Backend[9]['passwd'] = 'www.ry123456';
		$this->Backend[9]['ip']     = '92.63.203.153';
		$this->Backend[9]['port']   = 55000;

		$this->Backend[10]['login']  = 'serveradmin';
		$this->Backend[10]['passwd'] = 'www.ry123456';
		$this->Backend[10]['ip']     = '92.63.203.147';
		$this->Backend[10]['port']   = 55000;

		$this->Backend[11]['login']  = 'serveradmin';
		$this->Backend[11]['passwd'] = '1htKTTQL';
		$this->Backend[11]['ip']     = '92.63.203.173';
		$this->Backend[11]['port']   = 55000;

		$this->Backend[12]['login']  = 'serveradmin';
		$this->Backend[12]['passwd'] = 'Kh+Kb1wC';
		$this->Backend[12]['ip']     = '92.63.203.188';
		$this->Backend[12]['port']   = 55000;

		$this->Backend[13]['login']  = 'serveradmin';
		$this->Backend[13]['passwd'] = 'YlTGWgHj';
		$this->Backend[13]['ip']     = '92.63.203.155';
		$this->Backend[13]['port']   = 55000;

		$this->Backend[14]['login']  = 'serveradmin';
		$this->Backend[14]['passwd'] = 'cNJ+ER58';
		$this->Backend[14]['ip']     = '92.63.203.189';
		$this->Backend[14]['port']   = 55000;

		$this->Backend[15]['login']  = 'serveradmin';
		$this->Backend[15]['passwd'] = 'X9Mk2WqQ';
		$this->Backend[15]['ip']     = '92.63.203.187';
		$this->Backend[15]['port']   = 55000;

		$this->Backend[16]['login']  = 'serveradmin';
		$this->Backend[16]['passwd'] = 'SserviQuerYAthPResContSel';
		$this->Backend[16]['ip']     = '92.63.203.34';
		$this->Backend[16]['port']   = 55000;

		$this->Backend[17]['login']  = 'serveradmin';
		$this->Backend[17]['passwd'] = 'SserviQuerYAthPResContSel';
		$this->Backend[17]['ip']     = '92.63.203.36';
		$this->Backend[17]['port']   = 55000;

	}

	private $ListingIP = '0.0.0.0';
	private $ListingStartRange = 9000;

	private $Server;
	private $ServerMode = SWOOLE_BASE;
	private $ServerType = SWOOLE_SOCK_TCP;

	protected $frontends;
	protected $backends;

	/**
	 * @var array Массив конфигураций для подключения к бекенду
	 */
	private $Backend = [];

	private $Client = [];

	public function run() {
		$Server = new SwooleServer( $this->ListingIP, $this->ListingStartRange, $this->ServerMode, $this->ServerType );

		if ( count( $this->Backend ) > 1 ) {
			for ( $i = 1; $i < count( $this->Backend ); $i ++ ) {
				$Server->addlistener( $this->ListingIP, $this->ListingStartRange + $i, $this->ServerType );
			}
		}

		$Server->set( array(
			'worker_num'         => 1, //worker process num
			'open_tcp_keepalive' => 1,
			'log_file'           => __DIR__ . '/demon.log',
			'log_level'          => 0,
			//'daemonize'          => 0,
		) );

		$Server->on( 'WorkerStart', array( $this, 'onStart' ) );
		$Server->on( 'Receive', array( $this, 'onReceive' ) );
		$Server->on( 'Close', array( $this, 'onClose' ) );
		$Server->on( 'WorkerStop', array( $this, 'onShutdown' ) );
		$Server->on( 'connect', array( $this, 'onConnect' ) );

		$Server->start();
	}

	function onConnect( swoole_server $Server, $fd, $from_id ): void {
		$this->Client[ $fd ]['ip']              = $Server->connection_info( $fd )['remote_ip'];
		$this->Client[ $fd ]['login']           = 'no login';
		$this->Client[ $fd ]['IsLogin']         = false;
		$this->Client[ $fd ]['ServerPort']      = $Server->connection_info( $fd )['server_port'];
		$this->Client[ $fd ]['ConnectServerID'] = $this->Client[ $fd ]['ServerPort'] - $this->ListingStartRange;

		Log::info( 'Подключен клиент с IP: ' . $this->Client[ $fd ]['ip'] );
	}

	function onStart( $Server ) {
		$this->Server = $Server;
		Log::info( "Запуск сервера, версия Swoole [" . SWOOLE_VERSION . "]" );
		Log::info( "Listen ip: " . $this->ListingIP );

		$ListingPort = (string) $this->ListingStartRange;

		if ( count( $this->Backend ) > 1 ) {
			for ( $i = 1; $i < count( $this->Backend ); $i ++ ) {
				$ListingPort .= ',' . (string) ( $this->ListingStartRange + $i );
			}
		}

		Log::info( "Listen port: " . $ListingPort );
	}

	function onShutdown( $Server ) {
		Log::info( "Завершение работы сервера" );
	}

	function onClose( $Server, $fd, $from_id ) {
		if ( isset( $this->frontends[ $fd ] ) ) {
			$backend_socket          = $this->frontends[ $fd ];
			$backend_socket->closing = true;
			$backend_socket->close();
			unset( $this->backends[ $backend_socket->sock ] );
			unset( $this->frontends[ $fd ] );
		}

		Log::info( 'Отключен клиент с IP: ' . $this->Client[ $fd ]['ip'] );
	}

	function onReceive( $Server, $fd, $from_id, $data ) {

		Log::RecvData(
			$this->Backend[ $this->Client[ $fd ]['ConnectServerID'] ]['ip'],
			$this->Client[ $fd ]['ip'],
			$this->Client[ $fd ]['login'],
			$data
		);

		if ( ! $this->Client[ $fd ]['IsLogin'] ) {
			if ( ( $LoginData = TeamSpeakComandParser::Login( $data ) ) != null ) {
				$this->Client[ $fd ]['IsLogin'] = true;
				$this->Client[ $fd ]['login']   = (string) $LoginData[0];

				if (
					UserVerification::VerifiLoginPasswd( $LoginData[0], $LoginData[1] ) ||
					UserVerification::VerifiAccesInstance( $LoginData[0], $this->Backend[ $this->Client[ $fd ]['ConnectServerID'] ]['ip'] )
				) {
					$data = TeamSpeakComandBild::Login(
						$this->Backend[ $this->Client[ $fd ]['ConnectServerID'] ]['login'],
						$this->Backend[ $this->Client[ $fd ]['ConnectServerID'] ]['passwd']
					);
				}
			}
		}

		if ( ! isset( $this->frontends[ $fd ] ) ) {
			$socket          = new \swoole_client( SWOOLE_SOCK_TCP, SWOOLE_SOCK_ASYNC );
			$socket->closing = false;
			$socket->on( 'connect', function ( \swoole_client $socket ) use ( $data ) {
				$socket->send( $data );
			} );

			$socket->on( 'error', function ( \swoole_client $socket ) use ( $fd ) {
				echo "ERROR: connect to backend server failed\n";
				$this->Server->send( $fd, "backend server not connected. please try reconnect." );
				$this->Server->close( $fd );
			} );

			$socket->on( 'close', function ( \swoole_client $socket ) use ( $fd ) {
				unset( $this->backends[ $socket->sock ] );
				unset( $this->frontends[ $fd ] );
				if ( ! $socket->closing ) {
					$this->Server->close( $fd );
				}
			} );

			$socket->on( 'receive', function ( \swoole_client $socket, $_data ) use ( $fd ) {
				$this->Server->send( $fd, $_data );
			} );

			if ( $socket->connect( $this->Backend[ $this->Client[ $fd ]['ConnectServerID'] ]['ip'], $this->Backend[ $this->Client[ $fd ]['ConnectServerID'] ]['port'] ) ) {
				$this->backends[ $socket->sock ] = $fd;
				$this->frontends[ $fd ]          = $socket;
			} else {
				echo "ERROR: cannot connect to backend server.\n";
				$this->Server->send( $fd, "backend server not connected. please try reconnect." );
				$this->Server->close( $fd );
			}
		} else {
			/**
			 * @var $socket swoole_client
			 */
			$socket = $this->frontends[ $fd ];
			$socket->send( $data );
		}
	}
}

$Server = new \Proxy\Server();
$Server->run();