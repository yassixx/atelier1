<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Tests;

use App\Entity\Formation;
use DateTime;
use PHPUnit\Framework\TestCase;

/**
 *  Tests unitaires sur la date de parution au format string
 *
 * @author intad
 */
class DateTest extends TestCase {

    public function testGetPublishedAtString() {
        $formation = new Formation();
        $formation->setPublishedAt(new DateTime("2021-01-04"));
        $this->assertEquals("04/01/2021", $formation->getPublishedAtString());
    }

}
