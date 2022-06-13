<?php
declare(strict_types=1);

namespace Mail;

use Generator;
use poggit\libasynql\DataConnector;
use poggit\libasynql\libasynql;
use SOFe\AwaitGenerator\Await;

class SqliteProvider{

	public const INIT = "mail.init";
	public const REGISTER = "mail.register";
	public const REMOVE = "mail.remove";
	public const SELECT_ALL = "mail.select.all";
	public const SELECT_FROM = "mail.select.from";
	public const SELECT_TO = "mail.select.to";
	public const SELECT_ID = "mail.select.id";
	public const SELECT_UNREAD = "mail.select.unread";
	public const UPDATE_ISREAD = "mail.update.isread";
	public const UPDATE_ISCLAIMED = "mail.update.isclaimed";
	public const UPDATE_IS_DELETED_BY_FROM = "mail.update.isdeletedbyfrom";
	public const UPDATE_IS_DELETED_BY_TO = "mail.update.isdeletedbyto";
	private DataConnector $db;
	private Loader $loader;

	public function __construct(Loader $loader){
		$this->loader = $loader;
	}

	public function init() : void{
		$this->db = libasynql::create($this->getLoader(), $this->getLoader()->getConfig()->get("database"), [
			"sqlite" => "sqlite.sql"
		]);
		$this->db->executeGeneric(self::INIT);
	}

	private function getLoader() : Loader{
		return $this->loader;
	}

	public function close() : void{
		if(isset($this->db)) $this->db->close();
	}

	public function selectAll() : Generator{
		return yield $this->asyncSelect(self::SELECT_ALL, []);
	}

	public function asyncSelect(string $query, array $args) : Generator{
		$this->db->executeSelect($query, $args, yield, yield Await::REJECT);
		return yield Await::ONCE;
	}

	public function selectFrom(string $name) : Generator{
		return yield $this->asyncSelect(self::SELECT_FROM, [
			"name" => base64_encode($name)
		]);
	}

	public function selectTo(string $name) : Generator{
		return yield $this->asyncSelect(self::SELECT_TO, [
			"name" => base64_encode($name)
		]);
	}

	public function selectId(int $id){
		return yield $this->asyncSelect(self::SELECT_ID, [
			"id" => $id
		]);
	}

	public function selectUnread(string $name) : Generator{
		return yield $this->asyncSelect(self::SELECT_UNREAD, [
			"name" => base64_encode($name)
		]);
	}

	public function updateIsRead(int $id, bool $value) : void{
		$this->db->executeChange(self::UPDATE_ISREAD, [
			"id" => $id,
			"isread" => $value
		]);
	}

	public function updateIsClaimed(int $id, bool $value) : void{
		$this->db->executeChange(self::UPDATE_ISCLAIMED, [
			"id" => $id,
			"isclaimed" => $value
		]);
	}

	public function updateIsDeletedByFrom(int $id, bool $value) : void{
		$this->db->executeChange(self::UPDATE_IS_DELETED_BY_FROM, [
			"id" => $id,
			"isdeletedbyfrom" => $value
		]);
	}

	public function updateIsDeletedByTo(int $id, bool $value) : void{
		$this->db->executeChange(self::UPDATE_IS_DELETED_BY_TO, [
			"id" => $id,
			"isdeletedbyto" => $value
		]);
	}

	public function remove(int $id) : void{
		$this->db->executeChange(self::REMOVE, [
			"id" => $id,
		]);
	}

	public function register(Mail $mail) : void{
		$this->db->executeChange(self::REGISTER, [
			"from" => base64_encode($mail->getFrom()),
			"to" => base64_encode($mail->getTo()),
			"title" => base64_encode($mail->getTitle()),
			"msg" => base64_encode($mail->getMsg()),
			"items" => $mail->getItems(),
			"time" => time()
		]);
	}
}