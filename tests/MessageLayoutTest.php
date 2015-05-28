<?php namespace Larablocks\Pigeon\Tests;

use Larablocks\Pigeon\MessageLayout;
use Mockery as m;
use PHPUnit_Framework_TestCase;

class MessageLayoutTest extends PHPUnit_Framework_TestCase
{

    public function tearDown()
    {
        m::close();
    }

    public function testSettingViewLayout()
    {
        $message_layout = new MessageLayout();
        $message_layout->setViewLayout('test.view.layout');

        $this->assertAttributeEquals('test.view.layout', 'view_layout', $message_layout);
        $this->assertEquals('test.view.layout', $message_layout->getViewLayout());
    }

    public function testSettingViewTemplate()
    {
        $message_layout = new MessageLayout();
        $message_layout->setViewTemplate('test.view.template');

        $this->assertAttributeEquals('test.view.template', 'view_template', $message_layout);
        $this->assertEquals('test.view.template', $message_layout->getViewTemplate());
    }

    public function testAddingAndClearingMessageVariables()
    {
        $message_layout = new MessageLayout();
        $message_layout->setViewTemplate('test.view.template');

        $message_layout->includeVariables(['key' => 'value']);

        // Test Setting Variables
        $this->assertAttributeEquals(['key' => 'value', $message_layout::TEMPLATE_VARIABLE => 'test.view.template'], 'message_variables', $message_layout);
        $this->assertEquals(['key' => 'value', $message_layout::TEMPLATE_VARIABLE => 'test.view.template'], $message_layout->getMessageVariables());

        $message_layout->clearVariables();

        $this->assertAttributeEquals([$message_layout::TEMPLATE_VARIABLE => 'test.view.template'], 'message_variables', $message_layout);
    }

}