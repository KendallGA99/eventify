<?php
declare(strict_types=1);
namespace Eventify\Domain;
use RuntimeException;
final class BusinessError extends RuntimeException
{
    public function __construct(string $message, public readonly int $httpStatus = 422) { parent::__construct($message); }
}
