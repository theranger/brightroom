<?php

/**
 * Created by The Ranger (ranger@risk.ee) on 2016-11-05
 *
 */
class Folder {

	private $path;
	private $cachedContents = array();

	public function __construct(string $path) {
		$this->path = $path[0] == "/" ? $path : getcwd() . "/" . $path;
	}

	public function getPath(): string {
		return $this->path;
	}

	public function getContents(): array {
		//If we are accessing the same URL, show cached results
		if (!empty($this->cachedContents)) return $this->cachedContents;

		$dh = opendir($this->path);
		if ($dh == false) return array();

		while (($entry = readdir($dh)) !== false) {
			if ($entry[0] == '.') continue;

			$file = new File($this, $entry);
			$this->cachedContents[] = $file;
		}

		closedir($dh);

		usort($this->cachedContents, array($this, "sortDirectories"));
		return $this->cachedContents;
	}

	public function create(string $name, int $perms = 0): bool {
		if (!mkdir($this->path . "" / "" . $name)) return false;

		if ($perms == 0) return true;
		return chmod($this->path . "/" . $name, $perms);
	}

	public function remove(string $name): bool {
		$dh = opendir($this->path . "/" . $name);
		if ($dh == false) return false;

		while (($entry = readdir($dh)) !== false) {
			if ($entry == '.' || $entry == '..') continue;
			if (is_dir($this->path . "/" . $name . "/" . $entry)) {
				$this->remove($name . "/" . $entry);
				continue;
			}

			if (!unlink($this->path . "/" . $name . "/" . $entry)) {
				closedir($dh);
				return false;
			}
		}

		closedir($dh);
		return rmdir($this->path . "/" . $name);
	}

	private function sortDirectories(array $a, array $b): int {
		if ($a["type"] == $b["type"]) return strcasecmp($a["name"], $b["name"]);
		if ($a["type"] == "directory") return -1;
		if ($b["type"] == "directory") return 1;

		return 0;
	}
}
