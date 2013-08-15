<?php

/**
 * CronnableLogs
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 *
 * @property Cronnables $Cronnables
 *
 * @package    ##PACKAGE##
 * @subpackage ##SUBPACKAGE##
 * @author     ##NAME## <##EMAIL##>
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
class CronnableLogs extends BaseCronnableLogs
{
  public function setUp() {
    parent::setUp();
    $this->hasOne('Cronnables', array(
        'local' => 'cronnable_id',
        'foreign' => 'id'));
  }

  public function assignDefaultValues($overwrite = false) {
    parent::assignDefaultValues($overwrite);

    $this->execution = date("Y-m-d H:i:s");
  }
}