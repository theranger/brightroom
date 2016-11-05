<?php

/**
 * Created by The Ranger (ranger@risk.ee) on 2016-10-12
 *
 */
abstract class ResponseCode {
	const OK						= 200;
	const BAD_REQUEST				= 400;
	const UNAUTHORIZED				= 401;
	const NOT_FOUND 				= 404;
	const FORBIDDEN					= 403;
	const INTERNAL_SERVER_ERROR		= 500;
}
