<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of validationTest
 *
 * @author monika
 */
class validationTest extends PHPUnit_Framework_TestCase{
	protected $obj;
	
	public function setUp() {
		_elgg_services()->setValue('session', new ElggSession(new Elgg_Http_MockSessionStorage));
		$this->obj = $this->getMockForAbstractClass('izapVideo');
		$reflection = new ReflectionClass('izapVideo');
		$method = $reflection->getMethod('initializeAttributes');
		if(method_exists($method, 'setAccessible')){
			$method->setAccessible(True);
			$method->invokeArgs($this->obj, array());
		}
	}

	public function testOffserverValidaion(){
		
	}
}
