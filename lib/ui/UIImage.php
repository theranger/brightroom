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

include_once "img/InfoField.php";

/**
 * Created by The Ranger (ranger@risk.ee) on 2016-11-18
 *
 */
class UIImage {

	private static $file;
	private static $next;
	private static $previous;
	private static $exifParser;

	public function __construct(File $file, ExifParser $exifParser) {
		self::$file = $file;
		self::$exifParser = $exifParser;

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

	public static function PrintTitle() {
		print self::$exifParser->getTitle();
	}

	public static function HasTitle(): bool {
		return !empty(self::$exifParser->getTitle());
	}

	public static function HasExif(): bool {
		return self::$exifParser->hasExif();
	}

	public static function PrintExif() {
		$exifInfo = self::$exifParser->getData();
		if (empty($exifInfo)) return;

		print '<table>';

		foreach ($exifInfo as $key => $value) {
			if (empty($value)) continue;

			switch ($key) {
				case InfoField::MAKE:
					print '<tr><th>Manufacturer</th>';
					break;

				case InfoField::MODEL:
					print '<tr><th>Model</th>';
					break;

				case InfoField::EXPOSURE:
					print '<tr><th>Exposure time</th>';
					break;

				case InfoField::APERTURE:
					print '<tr><th>Aperture</th>';
					break;

				case InfoField::ISO:
					print '<tr><th>ISO Speed</th>';
					break;

				case InfoField::SHOT_TIME:
					print '<tr><th>Date Taken</th>';
					break;

				default:
					continue 2;
			}

			print '<td>'.$value.'</td></tr>';
		}

		print '</table>';
	}
}
