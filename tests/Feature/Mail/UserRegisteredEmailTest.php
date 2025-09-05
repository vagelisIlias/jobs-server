<?php

declare(strict_types=1);

namespace Tests\Feature\Mail;

use Tests\TestCase;
use App\Mail\UserRegisteredEmail;
use Illuminate\Support\Facades\Mail;

class UserRegisteredEmailTest extends TestCase
{
    public function test_user_registered_email_builds_and_renders()
    {
        $userName = 'test';
        $mailable = new UserRegisteredEmail($userName);
        $this->assertEquals('User Registered', $mailable->envelope()->subject);
        $rendered = $mailable->render();
        $this->assertStringContainsString($userName, $rendered);
    }

    public function test_user_registered_email_sent()
    {
        Mail::fake();

        $userName = 'test';
        Mail::to('test@example.com')->send(new UserRegisteredEmail($userName));

        Mail::assertSent(UserRegisteredEmail::class, function ($email) use ($userName){
            return $email->user_name === $userName && $email->hasTo('test@example.com');
        });
    }
}
