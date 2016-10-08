<?php

/**
 * Created by The Ranger (ranger@risk.ee) on 2016-10-08
 *
 */
interface ImageRenderer {
	public function loadFile(string $path): resource;
	public function outputImage(string $fileName);
	public function getHandle(): resource;
	public function setHandle(resource $img);
}
