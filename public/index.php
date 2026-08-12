<?php

require_once dirname(__DIR__)."/app/core/Database.php";
require_once dirname(__DIR__)."/app/core/SessionManager.php";

init_session();

require_once dirname(__DIR__)."/app/core/Router.php";