<?php

namespace Rescue\Tests\Http;

use PHPUnit\Framework\TestCase;
use Rescue\Http\Exception\BadRequestException;
use Rescue\Http\Exception\ForbiddenException;
use Rescue\Http\Exception\HttpExceptionInterface;
use Rescue\Http\Exception\MethodNotAllowedException;
use Rescue\Http\Exception\NotFoundException;
use Rescue\Http\Exception\UnauthorizedException;

final class ExceptionTest extends TestCase
{
    /**
     * @throws HttpExceptionInterface
     */
    public function testBadRequest(): void
    {
        $this->expectExceptionMessage('Bad Request');
        $this->expectExceptionCode(400);
        throw new BadRequestException();
    }

    /**
     * @throws HttpExceptionInterface
     */
    public function testUnauthorized(): void
    {
        $this->expectExceptionMessage('Unauthorized');
        $this->expectExceptionCode(401);
        throw new UnauthorizedException();
    }

    /**
     * @throws HttpExceptionInterface
     */
    public function testForbidden(): void
    {
        $this->expectExceptionMessage('Forbidden');
        $this->expectExceptionCode(403);
        throw new ForbiddenException();
    }

    /**
     * @throws HttpExceptionInterface
     */
    public function testHttpNotFound(): void
    {
        $this->expectExceptionMessage('Page not found');
        $this->expectExceptionCode(404);
        throw new NotFoundException();
    }

    /**
     * @throws HttpExceptionInterface
     */
    public function testMethodNotAllowed(): void
    {
        $this->expectExceptionMessage('Method Not Allowed');
        $this->expectExceptionCode(405);
        throw new MethodNotAllowedException();
    }
}
