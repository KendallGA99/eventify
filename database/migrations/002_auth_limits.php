<?php
declare(strict_types=1);
return [<<<'SQL'
CREATE TABLE auth_limits (
    bucket CHAR(64) CHARACTER SET ascii COLLATE ascii_bin PRIMARY KEY,
    window_started BIGINT UNSIGNED NOT NULL,
    attempts INT UNSIGNED NOT NULL DEFAULT 0,
    INDEX ix_auth_limits_window (window_started)
) ENGINE=InnoDB
SQL];
