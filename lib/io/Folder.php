<?php

include_once "DirectoryEntry.php";

/**
 * Created by The Ranger (ranger@risk.ee) on 2016-11-05
 *
 */
class Folder extends DirectoryEntry {

	private $cachedContents = array();

	public function getPath(): string {
		return $this->path;
	}

	public function exists(): bool {
		return is_dir($this->path);
	}

	/**
	 * @return DirectoryEntry[]
	 */
	public function getContents(): array {
		//If we are accessing the same URL, show cached results
		if (!empty($this->cachedContents)) return $this->cachedContents;

		$dh = opendir($this->path);
		if ($dh == false) return array();

		while (($entry = readdir($dh)) !== false) {
			if ($entry[0] == '.') continue;

			if (is_dir($this->path . "/" . $entry)) {
				$this->cachedContents[] = new Folder($this->base, $this->url . "/" . $entry);
				continue;
			}

			if (is_file($this->path . "/" . $entry)) {
				$this->cachedContents[] = new File($this, $entry);
				continue;
			}
		}

		closedir($dh);

		usort($this->cachedContents, array($this, "sortDirectories"));
		return $this->cachedContents;
	}

	public function create(int $perms = 0): bool {
		if (!mkdir($this->path)) return false;

		if ($perms == 0) return true;
		return chmod($this->path, $perms);
	}

	public function remove(): bool {
		$dh = opendir($this->path);
		if ($dh == false) return false;

		while (($entry = readdir($dh)) !== false) {
			if ($entry == '.' || $entry == '..') continue;
			if (is_dir($this->path . "/" . $entry)) {
				(new Folder($this->path, $entry))->remove();
				continue;
			}

			if (!unlink($this->path . "/" . $entry)) {
				closedir($dh);
				return false;
			}
		}

		closedir($dh);
		return rmdir($this->path);
	}

	private function sortDirectories(DirectoryEntry $a, DirectoryEntry $b): int {
		if ($a->isDirectory() && $b->isDirectory()) return strcasecmp($a->getName(), $b->getName());
		if ($a->isDirectory()) return -1;
		if ($b->isDirectory()) return 1;

		return 0;
	}
}
