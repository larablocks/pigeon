<?php namespace Larablocks\Pigeon\Tests;

use Larablocks\Pigeon\SwiftMailer;
use Mockery as m;
use PHPUnit_Framework_TestCase;

class SwiftMailerTest extends PHPUnit_Framework_TestCase
{

    public function tearDown()
    {
        m::close();
    }

    public function testPretendCanBeSet()
    {
        $mailer = m::mock('Illuminate\Mail\Mailer');

        $layout = m::mock('Larablocks\Pigeon\MessageLayout');

        $config = m::mock('Illuminate\Config\Repository');
        $config->shouldReceive('get')->once()->andReturn(['default' => [
            'layout' => 'emails.layouts.default',
            'template' => 'emails.templates.default',
            'subject' => 'Pigeon Delivery',
            'to' => [],
            'cc' => [],
            'bcc' => [],
            'message_variables' => []
        ]]);

        $swiftmailer = new SwiftMailer($mailer, $layout, $config);

        $this->assertEquals($swiftmailer, $swiftmailer->pretend());
        $this->assertEquals($swiftmailer, $swiftmailer->pretend(false));
        $this->assertFalse($swiftmailer->pretend(2));
    }

    public function testMessageWithLayoutCanBeSent()
    {
        $mailer = m::mock('Illuminate\Mail\Mailer');
        $mailer->shouldReceive('pretend')->twice()->andReturn(true);
        $mailer->shouldReceive('send')->once()->andReturn(true);

        $layout = m::mock('Larablocks\Pigeon\MessageLayout');
        $layout->shouldReceive('getViewLayout')->once()->andReturn('emails.layouts.default');
        $layout->shouldReceive('getMessageVariables')->once()->andReturn([]);

        $config = m::mock('Illuminate\Config\Repository');
        $config->shouldReceive('get')->once()->andReturn(['default' => [
            'layout' => 'emails.layouts.default',
            'template' => 'emails.templates.default',
            'subject' => 'Pigeon Delivery',
            'to' => [],
            'cc' => [],
            'bcc' => [],
            'message_variables' => []
        ]]);
        $config->shouldReceive('get')->once()->andReturn(true);

        $swiftmailer = new SwiftMailer($mailer, $layout, $config);

        $this->assertTrue($swiftmailer->send());
    }

    public function testRawMessageCanBeSent()
    {
        $mailer = m::mock('Illuminate\Mail\Mailer');
        $mailer->shouldReceive('pretend')->twice()->andReturn(true);
        $mailer->shouldReceive('raw')->once()->andReturn(true);

        $layout = m::mock('Larablocks\Pigeon\MessageLayout');

        $config = m::mock('Illuminate\Config\Repository');
        $config->shouldReceive('get')->once()->andReturn(['default' => [
            'layout' => 'emails.layouts.default',
            'template' => 'emails.templates.default',
            'subject' => 'Pigeon Delivery',
            'to' => [],
            'cc' => [],
            'bcc' => [],
            'message_variables' => []
        ]]);
        $config->shouldReceive('get')->once()->andReturn(true);

        $swiftmailer = new SwiftMailer($mailer, $layout, $config);

        $this->assertTrue($swiftmailer->send('Raw Message'));
    }

    public function testTypeDefaultSet()
    {
        $mailer = m::mock('Illuminate\Mail\Mailer');

        $layout = m::mock('Larablocks\Pigeon\MessageLayout');

        $config = m::mock('Illuminate\Config\Repository');
        $config->shouldReceive('get')->twice()->andReturn(['default' => [
            'layout' => 'emails.layouts.default',
            'template' => 'emails.templates.default',
            'subject' => 'Pigeon Delivery',
            'to' => [],
            'cc' => [],
            'bcc' => [],
            'message_variables' => []
        ]]);

        $swiftmailer = new SwiftMailer($mailer, $layout, $config);

        $this->assertEquals($swiftmailer, $swiftmailer->load('default'));
        $this->assertEquals('default', $swiftmailer->getType());

    }

    public function testCustomTypeSet()
    {
        $mailer = m::mock('Illuminate\Mail\Mailer');

        $layout = m::mock('Larablocks\Pigeon\MessageLayout');

        $config = m::mock('Illuminate\Config\Repository');
        $config->shouldReceive('get')->once()->andReturn(['default' => [
            'layout' => 'emails.layouts.default',
            'template' => 'emails.templates.default',
            'subject' => 'Pigeon Delivery',
            'to' => [],
            'cc' => [],
            'bcc' => [],
            'message_variables' => []
        ]]);

        $config->shouldReceive('get')->once()->andReturn(['custom' => [
            'layout' => 'emails.layouts.custom',
            'template' => 'emails.templates.custom',
            'subject' => 'Pigeon Test Type',
            'to' => [],
            'cc' => [],
            'bcc' => [],
            'message_variables' => []
        ]]);

        $swiftmailer = new SwiftMailer($mailer, $layout, $config);

        $this->assertEquals($swiftmailer, $swiftmailer->load('custom'));
        $this->assertEquals('custom', $swiftmailer->getType());

    }
}