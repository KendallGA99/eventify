<?php
declare(strict_types=1);
return [<<<'SQL'
CREATE TABLE weather_budget (
    scope VARCHAR(32) CHARACTER SET ascii COLLATE ascii_bin PRIMARY KEY,
    hour_window BIGINT UNSIGNED NOT NULL DEFAULT 0,
    minute_window BIGINT UNSIGNED NOT NULL DEFAULT 0,
    hour_requests TINYINT UNSIGNED NOT NULL DEFAULT 0,
    minute_requests TINYINT UNSIGNED NOT NULL DEFAULT 0,
    CHECK (hour_requests <= 30),
    CHECK (minute_requests <= 10)
) ENGINE=InnoDB
SQL];
