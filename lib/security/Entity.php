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

include_once "Permission.php";

/**
 * Created by The Ranger (ranger@risk.ee) on 2016-12-11
 *
 */
abstract class Entity {

	const DEFAULT = "Default";

	protected $name;
	protected $permission;

	public function __construct(string $name = Entity::DEFAULT, $permission = Permission::NONE) {
		$this->name = $name;
		$this->permission = $permission;
	}

	public function getName(): string {
		return $this->name;
	}

	public function getPermission(): int {
		return $this->permission;
	}

	public function isAccessible(): bool {
		return $this->permission == Permission::READ;
	}
}
