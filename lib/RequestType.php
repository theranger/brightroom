<?php

/**
 * Created by The Ranger (ranger@risk.ee) on 2016-10-12
 *
 */
abstract class RequestType {
	const UNKNOWN			= 0;
	const IMAGE_FOLDER		= 1;
	const CACHE_FOLDER		= 2;
	const IMAGE_FILE		= 3;
	const PASSWORD_FILE		= 4;
	const ACCESS_FILE		= 5;
	const VETO_FILE			= 6;
}
