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

/**
 * Created by The Ranger (ranger@risk.ee) on 2016-11-18
 *
 */
class UIImage {

	private static $file;
	private static $next;
	private static $previous;

	public function __construct(File $file) {
		self::$file = $file;

		$entries = $file->getFolder()->getContents();
		foreach ($entries as $key => $value) {
			if ($value->getPath() != $file->getPath()) continue;

			if ($key > 0) self::$previous = $entries[$key - 1];
			if ($key < count($entries) - 1) self::$next = $entries[$key + 1];
		}
	}

	public static function PrintNextImageURL() {
		if (!isset(self::$next)) return ;
		print self::$next->getURL();
	}

	public static function hasNextImage(): bool {
		return isset(self::$next);
	}

	public static function PrintPreviousImageURL() {
		if (!isset(self::$previous)) return;
		print self::$previous->getURL();
	}

	public static function hasPreviousImage(): bool {
		return isset(self::$previous);
	}

	public static function PrintImageURL() {
		print self::$file->getURL();
	}
}
