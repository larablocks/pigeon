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
        $swiftmailer = new SwiftMailer($this->getMailerMock(), $this->getLayoutMock(), $this->getConfigMock(), $this->getLoggerMock());

        $this->assertEquals($swiftmailer, $swiftmailer->pretend());
        $this->assertEquals($swiftmailer, $swiftmailer->pretend(false));
        $this->assertFalse($swiftmailer->pretend(2));
    }

    public function testMessageWithLayoutCanBeSent()
    {
        $mailer = $this->getMailerMock();
        $mailer->shouldReceive('pretend')->twice()->andReturn(true);
        $mailer->shouldReceive('send')->once()->andReturn(true);

        $layout = $this->getLayoutMock();
        $layout->shouldReceive('getViewLayout')->once()->andReturn('emails.layouts.default');
        $layout->shouldReceive('getMessageVariables')->once()->andReturn([]);
        $layout->shouldReceive('clearVariables')->once();

        $swiftmailer = new SwiftMailer($mailer, $layout, $this->getConfigMock(), $this->getLoggerMock());

        $this->assertTrue($swiftmailer->send());
    }

    public function testRawMessageCanBeSent()
    {
        $mailer = $this->getMailerMock();
        $mailer->shouldReceive('pretend')->twice()->andReturn(true);
        $mailer->shouldReceive('raw')->once()->andReturn(true);

        $layout = $this->getLayoutMock();
        $layout->shouldReceive('clearVariables')->once();

        $swiftmailer = new SwiftMailer($mailer, $layout, $this->getConfigMock(), $this->getLoggerMock());

        $this->assertTrue($swiftmailer->send('Raw Message'));
    }

    public function testTypeDefaultSet()
    {
        $swiftmailer = new SwiftMailer($this->getMailerMock(), $this->getLayoutMock(), $this->getConfigMock(), $this->getLoggerMock());

        $this->assertEquals($swiftmailer, $swiftmailer->type('default'));
        $this->assertEquals('default', $swiftmailer->getType());

    }

    public function testCustomTypeSet()
    {
        $config = m::mock('Illuminate\Config\Repository');
        $config->shouldReceive('get')->once()->andReturn([
            'layout' => 'emails.layouts.default',
            'template' => 'emails.templates.default',
            'subject' => 'Pigeon Delivery',
            'to' => [],
            'cc' => [],
            'bcc' => [],
            'message_variables' => []
        ]);

        $config->shouldReceive('get')->once()->andReturn([
            'layout' => 'emails.layouts.custom',
            'template' => 'emails.templates.custom',
            'subject' => 'Custom Type',
            'to' => [],
            'cc' => [],
            'bcc' => [],
            'message_variables' => ['custom' => 'Test Custom']
        ]);


        $config->shouldReceive('get')->once()->andReturn([
           'bad_variables' => 'test'
        ]);

        $swiftmailer = new SwiftMailer($this->getMailerMock(), $this->getLayoutMock(), $config, $this->getLoggerMock());

        // Test Correct Custom Configs
        $this->assertEquals($swiftmailer, $swiftmailer->type('custom'));
        $this->assertEquals('custom', $swiftmailer->getType());
        $this->assertAttributeEquals('Custom Type', 'subject', $swiftmailer);

        // Test Custom Config with bad option
        $this->assertEquals($swiftmailer, $swiftmailer->type('bad_custom'));
    }

    public function testSwiftMailerPropertySetting()
    {
        $swiftmailer = new SwiftMailer($this->getMailerMock(), $this->getLayoutMock(), $this->getConfigMock(), $this->getLoggerMock());

        // Test to
        $swiftmailer->to('john.doe@domain.com');
        $this->assertAttributeEquals(['john.doe@domain.com'], 'to', $swiftmailer);

        $swiftmailer->to(['jane.doe@domain.com','fred.doe@gmail.com']);
        $this->assertAttributeEquals(['john.doe@domain.com', 'jane.doe@domain.com','fred.doe@gmail.com'], 'to', $swiftmailer);

        // Test cc
        $swiftmailer->cc('john.doe@domain.com');
        $this->assertAttributeEquals(['john.doe@domain.com'], 'cc', $swiftmailer);

        $swiftmailer->cc(['jane.doe@domain.com','fred.doe@gmail.com']);
        $this->assertAttributeEquals(['john.doe@domain.com', 'jane.doe@domain.com','fred.doe@gmail.com'], 'cc', $swiftmailer);

        // Test bcc
        $swiftmailer->bcc('john.doe@domain.com');
        $this->assertAttributeEquals(['john.doe@domain.com'], 'bcc', $swiftmailer);

        $swiftmailer->bcc(['jane.doe@domain.com','fred.doe@gmail.com']);
        $this->assertAttributeEquals(['john.doe@domain.com', 'jane.doe@domain.com','fred.doe@gmail.com'], 'bcc', $swiftmailer);

        // Test replyTo
        $swiftmailer->replyTo('contact@domain.com');
        $this->assertAttributeEquals('contact@domain.com', 'reply_to', $swiftmailer);

        // Test subject
        $swiftmailer->subject('Test Subject');
        $this->assertAttributeEquals('Test Subject', 'subject', $swiftmailer);

        // Test attach
        $swiftmailer->attach('/public/pdf/test1.pdf');
        $this->assertAttributeEquals([0 => ['path' => '/public/pdf/test1.pdf', 'options' => []]], 'attachments', $swiftmailer);

        $swiftmailer->attach('/public/pdf/test2.pdf', ['as' => 'My Test PDF', 'mime' => 'pdf']);
        $this->assertAttributeEquals(
            [
                0 => ['path' => '/public/pdf/test1.pdf', 'options' => []],
                1 => ['path' => '/public/pdf/test2.pdf', 'options' => ['as' => 'My Test PDF', 'mime' => 'pdf']]
            ], 'attachments', $swiftmailer);

        $swiftmailer->attach([ ['path' => '/public/pdf/test3.pdf', 'options' => ['as' => 'My Test PDF']], ['path' => '/public/pdf/test4.pdf'] ]);
        $this->assertAttributeEquals(
            [
                0 => ['path' => '/public/pdf/test1.pdf', 'options' => []],
                1 => ['path' => '/public/pdf/test2.pdf', 'options' => ['as' => 'My Test PDF', 'mime' => 'pdf']],
                2 => ['path' => '/public/pdf/test3.pdf', 'options' => ['as' => 'My Test PDF']],
                3 => ['path' => '/public/pdf/test4.pdf', 'options' => []]
            ], 'attachments', $swiftmailer);

    }

    public function testSettingLayoutPropertiesInSwiftMailer()
    {
        $layout = $this->getLayoutMock();
        $layout->shouldReceive('setViewLayout')->zeroOrMoreTimes();
        $layout->shouldReceive('setViewTemplate')->zeroOrMoreTimes();
        $layout->shouldReceive('includeVariables')->zeroOrMoreTimes();
        $layout->shouldReceive('clearVariables')->once();

        $swiftmailer = new SwiftMailer($this->getMailerMock(), $layout, $this->getConfigMock(), $this->getLoggerMock());

        // Test Setting Layout
        $this->assertEquals($swiftmailer, $swiftmailer->layout('emails.layouts.default'));

        // Test Setting Template
        $this->assertEquals($swiftmailer, $swiftmailer->template('emails.templates.default'));

        // Test Setting Message Variables
        $this->assertEquals($swiftmailer, $swiftmailer->pass(['variableOne' => 'One', 'variableTwo' => 'two']));

        // Clearing Message Variables
        $this->assertEquals($swiftmailer, $swiftmailer->clear());
    }

    private function getMailerMock()
    {
        return m::mock('Illuminate\Mail\Mailer');
    }

    private function getConfigMock()
    {
        $config = m::mock('Illuminate\Config\Repository');
        $config->shouldReceive('get')->zeroOrMoreTimes()->andReturn([
            'layout' => 'emails.layouts.default',
            'template' => 'emails.templates.default',
            'subject' => 'Pigeon Delivery',
            'to' => [],
            'cc' => [],
            'bcc' => [],
            'message_variables' => []
        ]);

        return $config;
    }

    private function getLayoutMock()
    {
        $layout = m::mock('Larablocks\Pigeon\MessageLayout');
        $layout->shouldReceive('setViewLayout')->zeroOrMoreTimes();
        $layout->shouldReceive('setViewTemplate')->zeroOrMoreTimes();
        $layout->shouldReceive('includeVariables')->zeroOrMoreTimes();

        return $layout;
    }

    private function getLoggerMock()
    {
        return m::mock('Illuminate\Log\Writer');
    }
}