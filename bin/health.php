<?php

declare(strict_types=1);

require dirname(__DIR__) . '/app/bootstrap.php';
exit((new Eventify\Http\HealthController())->ready() ? 0 : 1);
