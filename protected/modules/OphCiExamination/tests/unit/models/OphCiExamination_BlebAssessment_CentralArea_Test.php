<?php

/**
 * OpenEyes.
 *
 * (C) Moorfields Eye Hospital NHS Foundation Trust, 2008-2011
 * (C) OpenEyes Foundation, 2011-2013
 * This file is part of OpenEyes.
 * OpenEyes is free software: you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation, either version 3 of the License, or (at your option) any later version.
 * OpenEyes is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU General Public License for more details.
 * You should have received a copy of the GNU General Public License along with OpenEyes in a file titled COPYING. If not, see <http://www.gnu.org/licenses/>.
 *
 * @link http://www.openeyes.org.uk
 *
 * @author OpenEyes <info@openeyes.org.uk>
 * @copyright Copyright (c) 2008-2011, Moorfields Eye Hospital NHS Foundation Trust
 * @copyright Copyright (c) 2011-2013, OpenEyes Foundation
 * @license http://www.gnu.org/licenses/gpl-3.0.html The GNU General Public License V3.0
 */
class OphCiExamination_BlebAssessment_CentralArea_Test extends CDbTestCase
{
    /**
     * @var OphCiExamination_BlebAssessment_CentralArea
     */
    protected $model;
    public $fixtures = array(
    );

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        parent::setUp();
        $this->model = new \OEModule\OphCiExamination\models\OphCiExamination_BlebAssessment_CentralArea();
    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown()
    {
    }

    /**
     * @covers OEModule\OphCiExamination\models\OphCiExamination_BlebAssessment_CentralArea::model
     */
    public function testModel()
    {
        $this->assertEquals('OEModule\OphCiExamination\models\OphCiExamination_BlebAssessment_CentralArea', get_class($this->model), 'Class name should match model.');
    }

    /**
     * @covers OEModule\OphCiExamination\models\OphCiExamination_BlebAssessment_CentralArea::tableName
     */
    public function testTableName()
    {
        $this->assertEquals('ophciexamination_bleb_assessment_central_area', $this->model->tableName());
    }
}
