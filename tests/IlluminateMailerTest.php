<?php namespace Larablocks\Pigeon\Tests;

use Larablocks\Pigeon\IlluminateMailer;
use Mockery as m;
use PHPUnit_Framework_TestCase;

class IlluminateMailerTest extends PHPUnit_Framework_TestCase
{

    public function tearDown()
    {
        m::close();
    }

    public function testPretendCanBeSet()
    {
        $mailer = new IlluminateMailer($this->getMailerMock(), $this->getLayoutMock(), $this->getConfigMock(), $this->getLoggerMock());

        $this->assertEquals($mailer, $mailer->pretend());
        $this->assertEquals($mailer, $mailer->pretend(false));
        $this->assertFalse($mailer->pretend(2));
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

        $mailer = new IlluminateMailer($mailer, $layout, $this->getConfigMock(), $this->getLoggerMock());

        $this->assertTrue($mailer->send());
    }

    public function testRawMessageCanBeSent()
    {
        $mailer = $this->getMailerMock();
        $mailer->shouldReceive('pretend')->twice()->andReturn(true);
        $mailer->shouldReceive('raw')->once()->andReturn(true);

        $layout = $this->getLayoutMock();
        $layout->shouldReceive('clearVariables')->once();

        $mailer = new IlluminateMailer($mailer, $layout, $this->getConfigMock(), $this->getLoggerMock());

        $this->assertTrue($mailer->send('Raw Message'));
    }

    public function testTypeDefaultSet()
    {
        $mailer = new IlluminateMailer($this->getMailerMock(), $this->getLayoutMock(), $this->getConfigMock(), $this->getLoggerMock());

        $this->assertEquals($mailer, $mailer->type('default'));
        $this->assertEquals('default', $mailer->getType());

    }

    public function testCustomTypeSet()
    {
        $config = m::mock('Illuminate\Config\Repository');
        $config->shouldReceive('get')->once()->andReturn([
            'to' => [],
            'cc' => [],
            'bcc' => [],
            'replyTo' => [],
            'from' => [],
            'sender' => [],
            'attachments' => [],
            'subject' => 'Pigeon Delivery',
            'layout' => 'emails.layouts.default',
            'template' => 'emails.templates.default',
            'message_variables' => []
        ]);

        $config->shouldReceive('get')->once()->andReturn([
            'to' => [],
            'cc' => [],
            'bcc' => [],
            'replyTo' => [],
            'from' => [],
            'sender' => [],
            'attachments' => [],
            'subject' => 'Custom Subject',
            'layout' => 'emails.layouts.custom',
            'template' => 'emails.templates.custom',
            'message_variables' => ['custom' => 'Test Custom']
        ]);


        $config->shouldReceive('get')->once()->andReturn([
           'bad_variables' => 'test'
        ]);

        $mailer = new IlluminateMailer($this->getMailerMock(), $this->getLayoutMock(), $config, $this->getLoggerMock());

        // Test Correct Custom Configs
        $this->assertEquals($mailer, $mailer->type('custom'));
        $this->assertEquals('custom', $mailer->getType());
        $this->assertAttributeEquals('Custom Subject', 'subject', $mailer);

        // Test Custom Config with bad option
        $this->assertEquals($mailer, $mailer->type('bad_custom'));
    }

    public function testSwiftMailerPropertySetting()
    {
        $mailer = new IlluminateMailer($this->getMailerMock(), $this->getLayoutMock(), $this->getConfigMock(), $this->getLoggerMock());

        // Test to
        $mailer->to('john.doe@domain.com');
        $this->assertAttributeEquals(['john.doe@domain.com' => null], 'to', $mailer);

        $mailer->to('jim.doe@domain.com', 'Jim Doe');
        $this->assertAttributeEquals(['john.doe@domain.com' => null, 'jim.doe@domain.com' => 'Jim Doe'], 'to', $mailer);

        $mailer->to(['jane.doe@domain.com' => 'Jane Doe', 'fred.doe@gmail.com']);
        $this->assertAttributeEquals(['john.doe@domain.com' => null, 'jim.doe@domain.com' => 'Jim Doe', 'jane.doe@domain.com' => 'Jane Doe', 'fred.doe@gmail.com' => null], 'to', $mailer);

        // Test cc
        $mailer->cc('john.doe@domain.com');
        $this->assertAttributeEquals(['john.doe@domain.com' => null], 'cc', $mailer);

        $mailer->cc('jim.doe@domain.com', 'Jim Doe');
        $this->assertAttributeEquals(['john.doe@domain.com' => null, 'jim.doe@domain.com' => 'Jim Doe'], 'cc', $mailer);

        $mailer->cc(['jane.doe@domain.com' => 'Jane Doe', 'fred.doe@gmail.com']);
        $this->assertAttributeEquals(['john.doe@domain.com' => null, 'jim.doe@domain.com' => 'Jim Doe', 'jane.doe@domain.com' => 'Jane Doe', 'fred.doe@gmail.com' => null], 'cc', $mailer);

        // Test bcc
        $mailer->bcc('john.doe@domain.com');
        $this->assertAttributeEquals(['john.doe@domain.com' => null], 'bcc', $mailer);

        $mailer->bcc('jim.doe@domain.com', 'Jim Doe');
        $this->assertAttributeEquals(['john.doe@domain.com' => null, 'jim.doe@domain.com' => 'Jim Doe'], 'bcc', $mailer);

        $mailer->bcc(['jane.doe@domain.com' => 'Jane Doe', 'fred.doe@gmail.com']);
        $this->assertAttributeEquals(['john.doe@domain.com' => null, 'jim.doe@domain.com' => 'Jim Doe', 'jane.doe@domain.com' => 'Jane Doe', 'fred.doe@gmail.com' => null], 'bcc', $mailer);

        // Test replyTo
        $mailer->replyTo('john.doe@domain.com');
        $this->assertAttributeEquals(['john.doe@domain.com' => null], 'reply_to', $mailer);

        $mailer->replyTo('jim.doe@domain.com', 'Jim Doe');
        $this->assertAttributeEquals(['john.doe@domain.com' => null, 'jim.doe@domain.com' => 'Jim Doe'], 'reply_to', $mailer);

        $mailer->replyTo(['jane.doe@domain.com' => 'Jane Doe', 'fred.doe@gmail.com']);
        $this->assertAttributeEquals(['john.doe@domain.com' => null, 'jim.doe@domain.com' => 'Jim Doe', 'jane.doe@domain.com' => 'Jane Doe', 'fred.doe@gmail.com' => null], 'reply_to', $mailer);

        // Test from
        $mailer->from('john.doe@domain.com');
        $this->assertAttributeEquals(['john.doe@domain.com' => null], 'from', $mailer);

        $mailer->from('jim.doe@domain.com', 'Jim Doe');
        $this->assertAttributeEquals(['john.doe@domain.com' => null, 'jim.doe@domain.com' => 'Jim Doe'], 'from', $mailer);

        $mailer->from(['jane.doe@domain.com' => 'Jane Doe', 'fred.doe@gmail.com']);
        $this->assertAttributeEquals(['john.doe@domain.com' => null, 'jim.doe@domain.com' => 'Jim Doe', 'jane.doe@domain.com' => 'Jane Doe', 'fred.doe@gmail.com' => null], 'from', $mailer);

        // Test sender
        $mailer->sender('john.doe@domain.com');
        $this->assertAttributeEquals(['john.doe@domain.com' => null], 'sender', $mailer);

        $mailer->sender('jim.doe@domain.com', 'Jim Doe');
        $this->assertAttributeEquals(['john.doe@domain.com' => null, 'jim.doe@domain.com' => 'Jim Doe'], 'sender', $mailer);

        $mailer->sender(['jane.doe@domain.com' => 'Jane Doe', 'fred.doe@gmail.com']);
        $this->assertAttributeEquals(['john.doe@domain.com' => null, 'jim.doe@domain.com' => 'Jim Doe', 'jane.doe@domain.com' => 'Jane Doe', 'fred.doe@gmail.com' => null], 'sender', $mailer);

        // Test subject
        $mailer->subject('Test Subject');
        $this->assertAttributeEquals('Test Subject', 'subject', $mailer);

        // Test attach
        $mailer->attach('/public/pdf/test1.pdf');
        $this->assertAttributeEquals([0 => ['path' => '/public/pdf/test1.pdf', 'options' => []]], 'attachments', $mailer);

        $mailer->attach('/public/pdf/test2.pdf', ['as' => 'My Test PDF', 'mime' => 'pdf']);
        $this->assertAttributeEquals(
            [
                0 => ['path' => '/public/pdf/test1.pdf', 'options' => []],
                1 => ['path' => '/public/pdf/test2.pdf', 'options' => ['as' => 'My Test PDF', 'mime' => 'pdf']]
            ], 'attachments', $mailer);

        $mailer->attach([ ['path' => '/public/pdf/test3.pdf', 'options' => ['as' => 'My Test PDF']], ['path' => '/public/pdf/test4.pdf'] ]);
        $this->assertAttributeEquals(
            [
                0 => ['path' => '/public/pdf/test1.pdf', 'options' => []],
                1 => ['path' => '/public/pdf/test2.pdf', 'options' => ['as' => 'My Test PDF', 'mime' => 'pdf']],
                2 => ['path' => '/public/pdf/test3.pdf', 'options' => ['as' => 'My Test PDF']],
                3 => ['path' => '/public/pdf/test4.pdf', 'options' => []]
            ], 'attachments', $mailer);

    }

    public function testSettingLayoutPropertiesInSwiftMailer()
    {
        $layout = $this->getLayoutMock();
        $layout->shouldReceive('setViewLayout')->zeroOrMoreTimes();
        $layout->shouldReceive('setViewTemplate')->zeroOrMoreTimes();
        $layout->shouldReceive('includeVariables')->zeroOrMoreTimes();
        $layout->shouldReceive('clearVariables')->once();

        $mailer = new IlluminateMailer($this->getMailerMock(), $layout, $this->getConfigMock(), $this->getLoggerMock());

        // Test Setting Layout
        $this->assertEquals($mailer, $mailer->layout('emails.layouts.default'));

        // Test Setting Template
        $this->assertEquals($mailer, $mailer->template('emails.templates.default'));

        // Test Setting Message Variables
        $this->assertEquals($mailer, $mailer->pass(['variableOne' => 'One', 'variableTwo' => 'two']));

        // Clearing Message Variables
        $this->assertEquals($mailer, $mailer->clear());
    }

    private function getMailerMock()
    {
        return m::mock('Illuminate\Mail\Mailer');
    }

    private function getConfigMock()
    {
        $config = m::mock('Illuminate\Config\Repository');
        $config->shouldReceive('get')->zeroOrMoreTimes()->andReturn([
            'to' => [],
            'cc' => [],
            'bcc' => [],
            'replyTo' => [],
            'from' => [],
            'sender' => [],
            'attachments' => [],
            'subject' => 'Pigeon Delivery',
            'layout' => 'emails.layouts.default',
            'template' => 'emails.templates.default',
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