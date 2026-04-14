<?php

namespace Tests\Unit\Domain\User;

use App\Domain\User\ValueObjects\Email;
use App\Domain\User\ValueObjects\UserId;
use PHPUnit\Framework\TestCase;

class ValueObjectsTest extends TestCase
{
    public function test_email_can_be_created_with_valid_value(): void
    {
        $email = new Email('test@example.com');

        $this->assertEquals('test@example.com', $email->value());
    }

    public function test_email_throws_exception_for_invalid_email(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid email format');

        new Email('not-an-email');
    }

    public function test_email_equals_comparison(): void
    {
        $email1 = new Email('test@example.com');
        $email2 = new Email('TEST@EXAMPLE.COM');
        $email3 = new Email('other@example.com');

        $this->assertTrue($email1->equals($email2)); // Case insensitive
        $this->assertFalse($email1->equals($email3));
    }

    public function test_user_id_can_be_created_with_auto_generated_uuid(): void
    {
        $userId = new UserId;

        $this->assertMatchesRegularExpression(
            '/^[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}$/i',
            $userId->value()
        );
    }

    public function test_user_id_can_be_created_with_valid_uuid(): void
    {
        $uuid = '550e8400-e29b-41d4-a716-446655440000';
        $userId = new UserId($uuid);

        $this->assertEquals($uuid, $userId->value());
    }

    public function test_user_id_throws_exception_for_invalid_uuid(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        new UserId('invalid-uuid');
    }
}
