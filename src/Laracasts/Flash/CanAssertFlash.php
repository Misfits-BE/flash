<?php 

namespace Laracasts\Flash; 

trait CanAssertFlash 
{
    /**
     * PHPUnit assertion for checking that a flash session is present.
     * 
     * @param  string       $level 
     * @param  string       $message
     * @param  bool         $important
     * @param  null|string  $title
     * @param  mixed        $overlay
     * @return mixed
     */
    protected function assertFlash($level, $message, $important = false, $title = null, $overlay = false)
    {
        $expectedNotification = [
            'title' => $title, 'message' => $message, 'level' => $level, 'important' => $important, 'overlay' => $overlay
        ];

        $flashNotifications = json_decode(json_encode(session('flash_notification')), true);

        if (! $flashNotifications) {
            $this->fail('Failed asserting that a flash message was sent.'); 
        }

        $this->assertContains($expectedNotification, $flashNotifications, "Failed that the flash message '$message' is present.");
    }
}