<?php

/**
 * Created by The Ranger (ranger@risk.ee) on 2016-10-14
 *
 */
abstract class ContentType {

	const JSON			= "application/json";
	const HTML			= "text/html";
	const PLAIN			= "text/plain";
	const JPEG			= "image/jpeg";

	private static $supportedTypes = array(
		ContentType::JSON,
		ContentType::JPEG,
		ContentType::HTML,
		ContentType::PLAIN
	);

	public static function getType(string $acceptHeader): string {
		$acceptHeader = explode(",", $acceptHeader);
		foreach ($acceptHeader as $value) {
			$key = array_search($value, ContentType::$supportedTypes);
			if ($key !== false) return ContentType::$supportedTypes[$key];
		}
		return ContentType::PLAIN;
	}
}
