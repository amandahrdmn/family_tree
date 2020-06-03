<?php


require 'Person.php';


$paternal_grandmother = new Person('paternal grandmother');
$paternal_grandfather = new Person('paternal grandfather');
$maternal_grandmother = new Person('maternal grandmother');
$maternal_grandfather = new Person('maternal grandfather');

$mother = new Person('mother', $maternal_grandmother, $maternal_grandfather);
$father = new Person('father', $paternal_grandmother, $paternal_grandfather);

$child = new Person('child', $mother, $father);


$search_result_depth = $child->searchForFamilyMemberDepth('paternal grandfather');
$search_result_breadth = $child->searchForFamilyMemberBreadth('paternal grandfather');

print("<pre>".print_r($search_result_depth,true)."</pre>");
print("<pre>".print_r($search_result_breadth,true)."</pre>");