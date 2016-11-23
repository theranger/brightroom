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

include_once "net/ContentType.php";

class ExifParser {

	private $exifData;
	private $iptcData;

	public function __construct(File $imageFile) {
		if ($imageFile->getType() != ContentType::JPEG) return;

		$this->exifData = @exif_read_data($imageFile->getPath());
		getimagesize($imageFile->getPath(), $info);
		if (isset($info["APP13"])) $this->iptcData = iptcparse($info["APP13"]);
	}

	public function hasExif(): bool {
		if (!isset($this->exifData)) return false;

		$keys = array_intersect_key($this->exifData, array_flip(InfoField::$fields));
		return isset($this->exifData) && !empty($keys);
	}

	public function getFileSize(): int {
		if (!isset($this->exifData["FileSize"])) return -1;

		return $this->exifData["FileSize"];
	}

	public function getOrientation(): int {
		if (!key_exists("Orientation", $this->exifData)) return 0;

		switch ($this->exifData["Orientation"]) {
			case 6:
				return -90;
			case 8:
				return 90;
			default:
				return 0;
		}
	}

	public function getComment(): string {
		if (!isset($this->exifData["Comments"])) return "";

		return $this->exifData["Comments"];
	}

	public function getTitle(): string {
		if (!isset($this->iptcData["2#005"])) return "";

		return $this->iptcData["2#005"][0];
	}

	public function getDescription(): string {
		if (isset($this->exifData["ImageDescription"])) return $this->exifData["ImageDescription"];
		if (isset($this->exifData["UserComment"])) return $this->exifData["UserComment"];
		return "";
	}

	public function getData(): array {
		if (!isset($this->exifData)) return array();
		return $this->exifData;
	}
}
