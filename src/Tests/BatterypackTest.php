<?php
namespace Drupal\recycle\Tests;

use Drupal\simpletest\WebTestBase;

/**
 * Test Batterypack Controller actions
 *
 * @group recycle
 */
class BatterypackTest extends WebTestBase
{
    /**
     * Modules to enable.
     *
     * @var array
     */
    public static $modules = array('recycle');

    /**
     * The installation profile to use with this test.
     *
     * We need the 'minimal' profile in order to make sure the Tool block is available.
     *
     * @var string
     */
    protected $profile = 'minimal';

    /**
     * Check statistics feature
     */
    public function testStatistics()
    {
        $this->submitForm('AA', 4);
        $this->submitForm('AAA', 3);
        $this->submitForm('AA', 1);

        $this->drupalGet('/recycle/statistics');

        $elements = $this->xpath("//tr[td[normalize-space(text())='AA'] and td[normalize-space(text())='5']]");
        $this->assertEqual(count($elements), 1);
        $elements = $this->xpath("//tr[td[normalize-space(text())='AAA'] and td[normalize-space(text())='3']]");
        $this->assertEqual(count($elements), 1);
    }

    /**
     * @param $type
     * @param $amount
     */
    protected function submitForm($type, $amount)
    {
        $this->drupalPostForm('/batterypack/new', array(
            'type' => $type,
            'amount' => $amount,
        ), t('Save'));
    }
}
