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

// phpunit --filter PatientMergeTest unit/components/PatientMergeTest.php
// 
// /var/www/openeyes/protected/tests>phpunit --filter PatientMerge unit/components/PatientMergeTest.php

class PatientMergeTest extends CDbTestCase
{
    public $fixtures = array(
            'patients' => 'Patient',
            'episodes' => 'Episode',
            'events' => 'Event',
            'firms' => 'Firm',
            'service_subspecialty_assignment' => 'ServiceSubspecialtyAssignment',
            'services' => 'Service',
            'specialties' => 'Specialty',
            'patient_allergy_assignment' => 'patientAllergyAssignment',
            'secondary_diagnosis' => 'secondaryDiagnosis',
            'previous_operation' => 'previousOperation',
    );

    public function setUp()
    {
        parent::setUp();
    }

    public function testComparePatientDetails()
    {
        $merge_handler = new PatientMerge();

        $primary_patient = $this->patients('patient7');
        $secondary_patient = $this->patients('patient8');

        $result = $merge_handler->comparePatientDetails($primary_patient, $secondary_patient);

        $this->assertTrue(is_array($result));
        $this->assertFalse($result['is_conflict'], 'Personal details should be the same at this point.');
        $this->assertEmpty($result['details']);

        // Change the dob and gender 
        $primary_patient->gender = 'M';
        $primary_patient->dob = '1981-12-21';

        $primary_patient->save();

        $result = $merge_handler->comparePatientDetails($primary_patient, $secondary_patient);

        $this->assertTrue($result['is_conflict'], 'Personal details should NOT be the same. Both DOB and Gender are different at this point.');

        $this->assertEquals($result['details'][0]['column'], 'dob');
        $this->assertEquals($result['details'][0]['primary'], '1981-12-21');
        $this->assertEquals($result['details'][0]['secondary'], '1977-01-01');

        $this->assertEquals($result['details'][1]['column'], 'gender');
        $this->assertEquals($result['details'][1]['primary'], 'M');
        $this->assertEquals($result['details'][1]['secondary'], 'F');
    }

    public function testUpdateEpisodesWhenPrimaryHasNoEpisodes()
    {
        $merge_handler = new PatientMerge();

        $primary_patient = $this->patients('patient7');
        $secondary_patient = $this->patients('patient8');

        $episode7 = $this->episodes('episode7');
        $episode7->patient_id = 1;
        $episode7->save();

        $episode8 = $this->episodes('episode8');
        $episode8->patient_id = 1;
        $episode8->save();

        $primary_patient->refresh();

        // primary has no episodes
        $this->assertEquals(count($primary_patient->episodes), 0);

        // at this pont the primary patient has no episodes and the secondary has

        // move the episodes , (secondary INTO primary)
        $result = $merge_handler->updateEpisodes($primary_patient, $secondary_patient);

        $this->assertTrue($result);

        $episode9 = $this->episodes('episode9');
        $this->assertEquals($episode9->patient_id, 7);

        $episode10 = $this->episodes('episode10');
        $this->assertEquals($episode10->patient_id, 7);

        $secondary_patient->refresh();

        // secondary has no episodes
        $this->assertEquals(count($secondary_patient->episodes), 0);
    }

    public function testUpdateEpisodesWhenBothHaveEpisodesNoConflict()
    {
        $merge_handler = new PatientMerge();

        $primary_patient = $this->patients('patient7');
        $secondary_patient = $this->patients('patient8');

        // this episode conflicts with episode7, so assign it to a different user to avoid the conflict
        $eposode9 = $this->episodes('episode9');
        $eposode9->patient_id = 1;
        $eposode9->save();

        $secondary_patient->refresh();

        // now primary has Episode7 and Episode8
        //secondary has Episode 10

        $eposode7 = $this->episodes('episode7');
        $this->assertEquals($eposode7->patient_id, 7);

        $eposode8 = $this->episodes('episode8');
        $this->assertEquals($eposode8->patient_id, 7);

        $eposode10 = $this->episodes('episode10');
        $this->assertEquals($eposode10->patient_id, 8);

        $this->assertEquals(2, count($primary_patient->episodes));
        $this->assertEquals(1, count($secondary_patient->episodes));

        $result = $merge_handler->updateEpisodes($primary_patient, $secondary_patient);

        $this->assertTrue($result);

        $eposode7->refresh();
        $eposode8->refresh();
        $eposode10->refresh();

        $this->assertEquals($eposode7->patient_id, 7);
        $this->assertEquals($eposode8->patient_id, 7);
        $this->assertEquals($eposode10->patient_id, 7);
    }

    /**
     * We have to keep the episode with greater status
     * so if the Secondary Episode has greater status we flag it as deleted
     */
    public function testUpdateEpisodesWhenBothHaveEpisodesConflict_secondaryEpisodeHasLessStatus()
    {
        $merge_handler = new PatientMerge();

        // $primary_patient has episode7 and episode8
        $primary_patient = $this->patients('patient7'); // episode7

        // $secondary_patient has episode9, episode10
        $secondary_patient = $this->patients('patient8'); //episode9

        $episode7 = $this->episodes('episode7');
        $episode7->episode_status_id = 5;
        $episode7->start_date = date('Y-m-d', strtotime('-30 days'));
        $episode7->end_date = date('Y-m-d', strtotime('-15 days'));
        $episode7->save();

        $episode9 = $this->episodes('episode9');
        $episode9->episode_status_id = 2;
        $episode9->start_date = date('Y-m-d', strtotime('-20 days'));
        $episode9->end_date = date('Y-m-d', strtotime('-10 days'));
        $episode9->save();
        
        $this->assertTrue($episode7->status->order > $episode9->status->order);


        $this->assertEquals(count($primary_patient->episodes), 2);
        $this->assertEquals(count($secondary_patient->episodes), 2);

        // move the episodes , (secondary INTO primary)
        $result = $merge_handler->updateEpisodes($primary_patient, $secondary_patient);

        $episode7->refresh();
        $this->assertEquals( date('Y-m-d 00:00:00', strtotime('-30 days')), $episode7->start_date );
        $this->assertEquals( date('Y-m-d 00:00:00', strtotime('-10 days')), $episode7->end_date );

        $this->assertTrue($result, 'Merge result FALSE.');

        $this->assertEquals(count($primary_patient->episodes), 2);

        $event16 = $this->events('event16');
        $this->assertEquals($event16->episode_id, 9);

        $event17 = $this->events('event17');
        $this->assertEquals($event17->episode_id, 9);

        $episode8 = $this->episodes('episode8');
        $episode8->refresh();
        $this->assertEquals($episode8->patient_id, 7); // has not changed

        $episode9 = $this->episodes('episode9');
        $episode9->refresh();
        $this->assertEquals($episode9->patient_id, 7); // will be deleted

        $event20 = $this->events('event20');
        $this->assertEquals($event20->episode_id, 9);

        $event21 = $this->events('event21');
        $this->assertEquals($event21->episode_id, 9);

        $episode10 = $this->episodes('episode10');
        $episode10->refresh();
        $this->assertEquals($episode10->patient_id, 7);

        $secondary_patient->refresh();
        $this->assertEquals(count($secondary_patient->episodes), 0);

        $primary_patient->refresh();
        $this->assertEquals(count($primary_patient->episodes), 3);
    }

    public function testUpdateEpisodesWhenBothHaveEpisodesConflict_primaryEpisodeHasLessStatus()
    {
        $merge_handler = new PatientMerge();

        // $primary_patient has episode7 and episode8
        $primary_patient = $this->patients('patient7');

        // $secondary_patient has episode9, episode10
        $secondary_patient = $this->patients('patient8');

        // conflicting episodes :
        // episode7 <-> episode9

        $episode7 = $this->episodes('episode7');
        $episode7->episode_status_id = 2;
        $episode7->start_date = date('Y-m-d', strtotime('-20 days'));
        $episode7->end_date = date('Y-m-d', strtotime('-10 days'));
        $episode7->save();

        $episode9 = $this->episodes('episode9');
        $episode9->episode_status_id = 5;
        $episode9->start_date = date('Y-m-d', strtotime('-30 days'));
        $episode9->end_date = null;
        $episode9->save();
        
        $this->assertTrue($episode7->status->order < $episode9->status->order);

        $this->assertEquals(count($primary_patient->episodes), 2);
        $this->assertEquals(count($secondary_patient->episodes), 2);

        $result = $merge_handler->updateEpisodes($primary_patient, $secondary_patient);

        $episode9->refresh();
        $this->assertEquals( date('Y-m-d 00:00:00', strtotime('-30 days')), $episode9->start_date );
        $this->assertEquals( null, $episode9->end_date );

        $this->assertTrue($result, 'Merge result FALSE.');

        $event16 = $this->events('event16');
        $this->assertEquals($event16->episode_id, 7);

        $event17 = $this->events('event17');
        $this->assertEquals($event17->episode_id, 7);

        $event20 = $this->events('event20');
        $this->assertEquals($event20->episode_id, 7);

        $event21 = $this->events('event21');
        $this->assertEquals($event21->episode_id, 7);

        $episode7->refresh();
        $this->assertEquals(count($episode7->events), 4);

        $episode10 = $this->episodes('episode10');
        $this->assertEquals($episode10->patient_id, 7);

        $this->assertEquals($episode7->patient_id, 7);

        $episode8 = $this->episodes('episode8');
        $this->assertEquals($episode8->patient_id, 7);

        $secondary_patient->refresh();
        $this->assertEquals(count($secondary_patient->episodes), 0);

        $primary_patient->refresh();
        $this->assertEquals(count($primary_patient->episodes), 3);
    }

    public function testUpdateLegacyEpisodes_primaryNoLegacyEpisodes()
    {
        $merge_handler = new PatientMerge();

        // $primary_patient has episode7 and episode8
        $primary_patient = $this->patients('patient7');

        // $secondary_patient has episode9, episode10
        $secondary_patient = $this->patients('patient8');

        // Lets modify the episodes to have a legacy episode

        $episode7 = $this->episodes('episode7');
        $episode9 = $this->episodes('episode9');

        // Case : Secondary has legacy episode, Primary doesent have
        $episode9->legacy = 1;
        $episode9->save();
        $this->assertEquals(1, $episode9->legacy);

        $primary_patient->refresh();

        $result = $merge_handler->updateLegacyEpisodes($primary_patient, $secondary_patient);

        $this->assertTrue($result, 'Merge result FALSE.');

        // test the legacy
        $episode9->refresh();
        $episode9 = $this->episodes('episode9');
        $this->assertEquals($episode9->patient_id, 7);

        $secondary_patient->refresh();
        $this->assertEquals(count($secondary_patient->legacyepisodes), 0);

        $primary_patient->refresh();
        $this->assertEquals(count($primary_patient->legacyepisodes), 1);
    }

    public function testUpdateLegacyEpisodes_bothHaveLegacyEpisodes_secondaryOlder()
    {
        $merge_handler = new PatientMerge();

        // $primary_patient has episode7 and episode8
        $primary_patient = $this->patients('patient7');

        // $secondary_patient has episode9, episode10
        $secondary_patient = $this->patients('patient8');

        // Lets modify the episodes to have a legacy episode

        $episode7 = $this->episodes('episode7');
        $episode9 = $this->episodes('episode9');

        // Case : Both Primary and Secondary have legacy episode, so we keep the older episode and move the events
        $episode7->legacy = 1;
        $episode7->created_date = date('Y-m-d', strtotime('-15 days'));
        $episode7->save();
        $this->assertEquals(1, $episode7->legacy);

        $episode9->legacy = 1;
        $episode9->created_date = date('Y-m-d', strtotime('-30 days'));
        $episode9->save();
        $this->assertEquals(1, $episode9->legacy);

        $this->assertTrue($episode7->created_date > $episode9->created_date);

        $result = $merge_handler->updateLegacyEpisodes($primary_patient, $secondary_patient);

        $this->assertTrue($result, 'Merge result FALSE.');

        $event16 = $this->events('event16');
        $this->assertEquals($event16->episode_id, 9);

        $event17 = $this->events('event17');
        $this->assertEquals($event17->episode_id, 9);

        $episode9 = $this->episodes('episode9');
        $episode9->refresh();
        $this->assertEquals($episode9->patient_id, 7);
        $this->assertEquals(count($episode9->events), 4);

        $primary_patient->refresh();
        $this->assertEquals(count($primary_patient->legacyepisodes), 1);
        $this->assertEquals(count($primary_patient->legacyepisodes[0]->events), 4);

        $secondary_patient->refresh();
        $this->assertEquals(count($secondary_patient->legacyepisodes), 0);
    }

    public function testUpdateLegacyEpisodes_bothHaveLegacyEpisodes_primaryOlder()
    {
        $merge_handler = new PatientMerge();

        // $primary_patient has episode7 and episode8
        $primary_patient = $this->patients('patient7');

        // $secondary_patient has episode9, episode10
        $secondary_patient = $this->patients('patient8');

        // Lets modify the episodes to have a legacy episode

        $episode7 = $this->episodes('episode7');
        $episode9 = $this->episodes('episode9');

        // Case : Both Primary and Secondary have legacy episode, so we keep the older episode and move the events
        $episode7->legacy = 1;
        $episode7->save();
        $this->assertEquals(1, $episode7->legacy);

        $episode9->legacy = 1;
        $episode9->save();
        $this->assertEquals(1, $episode9->legacy);

        $this->assertTrue($episode7->created_date < $episode9->created_date);

        $result = $merge_handler->updateLegacyEpisodes($primary_patient, $secondary_patient);

        $this->assertTrue($result, 'Merge result FALSE.');

        $event20 = $this->events('event20');
        $this->assertEquals($event20->episode_id, 7);

        $event21 = $this->events('event21');
        $this->assertEquals($event20->episode_id, 7);

        $episode7->refresh();
        $this->assertEquals(count($episode7->events), 4);

        $episode9->refresh();
        $this->assertEquals(count($episode9->events), 0);

        $primary_patient->refresh();
        $this->assertEquals(count($primary_patient->legacyepisodes), 1);

        $secondary_patient->refresh();
        $this->assertEquals(count($secondary_patient->legacyepisodes), 0);
    }

    public function testUpdateAllergyAssignments_primaryHasNoAllergyAssignments()
    {
        $merge_handler = new PatientMerge();

        $assignment1 = $this->patient_allergy_assignment('assignment1');
        $assignment2 = $this->patient_allergy_assignment('assignment2');

        $assignment1->patient_id = 1;
        $assignment1->save();

        $primary_patient = $this->patients('patient7');
        $secondary_patient = $this->patients('patient8');

        $this->assertEquals(count($primary_patient->allergyAssignments), 0);
        $this->assertEquals(count($secondary_patient->allergyAssignments), 1);

        $result = $merge_handler->updateAllergyAssignments($primary_patient, $secondary_patient);

        $primary_patient->refresh();
        $secondary_patient->refresh();

        $this->assertTrue($result, 'Update Allergy Assigmant FAILED.');

        $assignment2->refresh();
        $this->assertEquals($assignment2->patient_id, 7);

        $this->assertEquals(count($primary_patient->allergyAssignments), 1);
        $this->assertEquals(count($secondary_patient->allergyAssignments), 0);
    }

    public function testUpdateAllergyAssignments_bothHaveDifferentAllergyAssignments()
    {
        $merge_handler = new PatientMerge();

        $assignment1 = $this->patient_allergy_assignment('assignment1');
        $assignment2 = $this->patient_allergy_assignment('assignment2');

        $primary_patient = $this->patients('patient7');
        $secondary_patient = $this->patients('patient8');

        $this->assertEquals(count($primary_patient->allergyAssignments), 1);
        $this->assertEquals(count($secondary_patient->allergyAssignments), 1);

        $result = $merge_handler->updateAllergyAssignments($primary_patient, $secondary_patient);

        $primary_patient->refresh();
        $secondary_patient->refresh();

        $this->assertTrue($result, 'Update Allergy Assigmant FAILED.');

        $assignment1->refresh();
        $this->assertEquals($assignment1->patient_id, 7);

        $assignment2->refresh();
        $this->assertEquals($assignment2->patient_id, 7);

        $this->assertEquals(count($primary_patient->allergyAssignments), 2);
        $this->assertEquals(count($secondary_patient->allergyAssignments), 0);
    }

    public function testUpdateAllergyAssignments_bothHaveSameAllergyAssignments()
    {
        $merge_handler = new PatientMerge();

        $assignment1 = $this->patient_allergy_assignment('assignment1');
        $assignment2 = $this->patient_allergy_assignment('assignment2');

        $assignment2->allergy_id = 1;
        $assignment2->save();
        $assignment2->refresh();

        $this->assertEquals($assignment1->allergy_id, $assignment2->allergy_id);

        $primary_patient = $this->patients('patient7');
        $secondary_patient = $this->patients('patient8');

        $this->assertEquals(count($primary_patient->allergyAssignments), 1);
        $this->assertEquals(count($secondary_patient->allergyAssignments), 1);

        $result = $merge_handler->updateAllergyAssignments($primary_patient, $secondary_patient);

        $primary_patient->refresh();
        $secondary_patient->refresh();

        $assignment1->refresh();
        $this->assertEquals($assignment1->patient_id, 7);

        $this->assertEquals($assignment1->comments, 'comment 1 ; comment 2');
        $this->assertEquals($assignment1->other, 'other 1 ; other 2');
    }

    public function testUpdateRiskAssignments()
    {
    }

    public function testUpdatePreviousOperations()
    {
        $merge_handler = new PatientMerge();
        
        $primary_patient = $this->patients('patient7');
        $secondary_patient = $this->patients('patient8');
        
        $previous_operation1 = $this->previous_operation('previousOperation1');
        
        $previous_operation1->patient_id = 8;
        $previous_operation1->save();
        $previous_operation1->refresh();
        
        // Before we update the Previous Operations we check if the patient id is equals to the secondary patient id
        $this->assertEquals(8, $previous_operation1->patient_id);
        
        $primary_patient->refresh();
        $secondary_patient->refresh();
        $this->assertEquals(0, count($primary_patient->previousOperations) );
        $this->assertEquals(1, count($secondary_patient->previousOperations) );
        
        $merge_handler->updatePreviousOperations($primary_patient, $secondary_patient->previousOperations);
        
        $primary_patient->refresh();
        $secondary_patient->refresh();
        $this->assertTrue(is_array($secondary_patient->previousOperations));
        
        $this->assertEquals(0, count($secondary_patient->previousOperations) );
        $this->assertEquals(1, count($primary_patient->previousOperations) );
        
        $previous_operation1->refresh();
        
        $this->assertEquals(7, $previous_operation1->patient_id);
    }
    
    public function testUpdateOphthalmicDiagnoses()
    {
        $merge_handler = new PatientMerge();
        
        $primary_patient = $this->patients('patient7');
        $secondary_patient = $this->patients('patient8');
        
        $secondary_diagnoses8 = $this->secondary_diagnosis('secondaryDiagnoses8');
        $secondary_diagnoses8->patient_id = 8;
        $secondary_diagnoses8->save();
        $secondary_diagnoses8->refresh();
        
        // Before we update the Ophthalmic Diagnoses we check if the patient id is equals to the secondary patient id
        $this->assertEquals(8, $secondary_diagnoses8->patient_id);
        
        $secondary_patient->refresh();
        $this->assertTrue(is_array($secondary_patient->ophthalmicDiagnoses) );
        $this->assertEquals(1, count($secondary_patient->ophthalmicDiagnoses) );
        
        $merge_handler->updateOphthalmicDiagnoses($primary_patient, $secondary_patient->ophthalmicDiagnoses);
        
        $secondary_diagnoses8->refresh();
        $secondary_patient->refresh();
        
        $this->assertEquals(0, count($secondary_patient->ophthalmicDiagnoses) );
        
        $this->assertEquals(7, $secondary_diagnoses8->patient_id);
        
        
    }
    
    public function testUpdateSystemicDiagnoses()
    {
        $merge_handler = new PatientMerge();
        
        $primary_patient = $this->patients('patient7');
        $secondary_patient = $this->patients('patient8');
        
        $secondary_diagnoses8 = $this->secondary_diagnosis('secondaryDiagnoses8');
        $secondary_diagnoses8->patient_id = 8;
        $secondary_diagnoses8->disorder_id = 5;
        $secondary_diagnoses8->save();
        $secondary_diagnoses8->refresh();
        
        

        // Befor we update the Ophthalmic Diagnoses we check if the patient id is equals to the secondary patient id
        $this->assertEquals(8, $secondary_diagnoses8->patient_id);
        $this->assertEquals(5, $secondary_diagnoses8->disorder_id);
        
        $secondary_patient->refresh();
        $this->assertTrue(is_array($secondary_patient->systemicDiagnoses) );
        $this->assertEquals(1, count($secondary_patient->systemicDiagnoses) );
        
        $merge_handler->updateOphthalmicDiagnoses($primary_patient, $secondary_patient->systemicDiagnoses);
        
        $secondary_diagnoses8->refresh();
        
        $this->assertEquals(7, $secondary_diagnoses8->patient_id);
        
        $this->assertEquals(0, count($secondary_patient->systemicDiagnoses) );
        $this->assertEquals(1, count($primary_patient->systemicDiagnoses) );
    }
    
    public function testGetTwoEpisodesStartEndDate()
    {
        $merge_handler = new PatientMerge();
        
        $episode7 = $this->episodes('episode7');
        $episode7->start_date = date('Y-m-d', strtotime('-30 days'));
        $episode7->end_date = date('Y-m-d', strtotime('-15 days'));
        $episode7->save();
        
        $episode9 = $this->episodes('episode9');
        $episode9->start_date = date('Y-m-d', strtotime('-20 days'));
        $episode9->end_date = date('Y-m-d', strtotime('-10 days'));
        $episode9->save();
        
        list($start_date, $end_date) = $merge_handler->getTwoEpisodesStartEndDate($episode7, $episode9);
        
        $this->assertEquals($start_date,  $episode7->start_date);
        $this->assertEquals($end_date,  $episode9->end_date);
        
        /******/
        
        $episode7->start_date = date('Y-m-d', strtotime('-20 days'));
        $episode7->save();
        
        $episode9->start_date = date('Y-m-d', strtotime('-30 days'));
        $episode9->save();
        
        list($start_date, $end_date) = $merge_handler->getTwoEpisodesStartEndDate($episode7, $episode9);
        
        $this->assertEquals($start_date,  $episode9->start_date);
        $this->assertEquals($end_date,  $episode9->end_date);
        
        /******/
        
        $episode7->end_date = null;
        $episode7->save();
        
        list($start_date, $end_date) = $merge_handler->getTwoEpisodesStartEndDate($episode7, $episode9);
        
        $this->assertEquals($start_date,  $episode9->start_date);
        $this->assertEquals($end_date,  null);
        
        /******/
        
        $episode7->end_date = date('Y-m-d', strtotime('-15 days'));
        $episode7->save();
        
        $episode9->end_date = null;
        $episode9->save();
        
        list($start_date, $end_date) = $merge_handler->getTwoEpisodesStartEndDate($episode7, $episode9);
        
        $this->assertEquals($start_date,  $episode9->start_date);
        $this->assertEquals($end_date,  null);
        
        /******/
        
        $episode7->end_date = date('Y-m-d', strtotime('-10 days'));
        $episode7->save();
        
        $episode9->end_date = date('Y-m-d', strtotime('-15 days'));
        $episode9->save();
        
        list($start_date, $end_date) = $merge_handler->getTwoEpisodesStartEndDate($episode7, $episode9);
        
        $this->assertEquals($start_date,  $episode9->start_date);
        $this->assertEquals($end_date,  $episode7->end_date);

    }

    public function testIsSecondaryPatientDeleted()
    {
    }

    public function testUpdateEpisodesPatientId()
    {
    }

    public function testUpdateEventsEpisodeId()
    {
    }

    public function testLoad()
    {
    }

    public function testMerge()
    {
    }

    public function tearDown()
    {
        parent::tearDown();
    }
}
