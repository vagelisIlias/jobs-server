<?php

declare(strict_types=1);

namespace App\Listeners\UserRegistered;

use App\Notifications\SendEmail;
use App\Events\Api\UserRegistered\UserRegisteredEventMessage;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendUserRegisteredNotification implements ShouldQueue
{
    private const SUBJECT = 'Registration completed';
    private const STATUS = 'Active';
    private const MESSAGE = 'Your account has been successfully created';
    private const URL = '/';
    private const ACTION_TEXT = 'Visit the site to apply or add new jobs';
    private const LINE_TEXT = 'Thank you for joining our platform';
    private const TEAM = 'Regards Team of Devs';

    /**
     * Create the event listener.
     */
    public function __construct()
    {
    }

    /**
     * Handle the event.
     */
    public function handle(UserRegisteredEventMessage $event): void
    {
        $event->user->notify(new SendEmail(
            self::SUBJECT,
            self::STATUS,
            self::MESSAGE,
            self::URL,
            self::ACTION_TEXT,
            self::LINE_TEXT,
            self::TEAM
        ));
    }
}
