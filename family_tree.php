<?php


require 'Person.php';


$paternal_grandmother = new Person('paternal grandmother');
$paternal_grandfather = new Person('paternal grandfather');
$maternal_grandmother = new Person('maternal grandmother');
$maternal_grandfather = new Person('maternal grandfather');

$mother = new Person('mother', $maternal_grandmother, $maternal_grandfather);
$father = new Person('father', $paternal_grandmother, $paternal_grandfather);

$child = new Person('child', $mother, $father);

var_dump($child);