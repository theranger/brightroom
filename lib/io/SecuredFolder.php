<?php
/**
 * Copyright 2016 The Ranger <ranger@risk.ee>
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *     http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

declare(strict_types = 1);

include_once "Folder.php";
include_once "system/Settings.php";
include_once "security/User.php";
include_once "security/Group.php";

/**
 * Created by The Ranger (ranger@risk.ee) on 2016-12-11
 *
 */
class SecuredFolder extends Folder {

	/**
	 * @var Entity[]
	 */
	private $acl = array();
	private $settings;

	public function __construct(string $base, string $url, Settings $settings) {
		parent::__construct($base, $url);

		$this->settings = $settings;
		$accessFile = new File($this, $settings->accessFile);

		if (!$accessFile->open()) return;

		while($accessFile->hasNext()) {
			$entry = $accessFile->readLine();
			if (empty($entry)) continue;

			$this->acl[$entry] = new User($entry, Permission::READ);
		}

		$accessFile->close();
	}

	/**
	 * @return SecuredFolder[]
	 * @throws SystemException
	 */
	public function getFolders(): array {
		if (!empty($this->children)) return $this->children;

		$dh = opendir($this->path);
		if ($dh == false) return array();
		if (!method_exists("Normalizer", "normalize")) throw new SystemException("PHP 'intl' module not installed");

		while (($entry = readdir($dh)) !== false) {
			if ($entry[0] == '.') continue;

			if (!is_dir($this->path."/".$entry)) continue;
			$this->children[] = new SecuredFolder($this->base, $this->url."/".Normalizer::normalize($entry), $this->settings);
		}

		closedir($dh);
		return $this->children;
	}

	/**
	 * @return SecuredFolder
	 * @throws IOException
	 */
	public function parentSecuredFolder(): SecuredFolder {
		if (empty($this->url) || $this->url == "/") throw new IOException("Parent folder not found");
		return new SecuredFolder($this->base, dirname($this->url), $this->settings);
	}

	public function getACL(string $name): Entity {
		if (empty($this->acl)) return new Group(Entity::DEFAULT, Permission::READ);
		if (isset($this->acl[$name])) return $this->acl[$name];
		return new Group();
	}

	public function aggregateACL(SecuredFolder $securedFolder) {
		$this->acl = array_merge($this->acl, $securedFolder->acl);
	}
}
