<?php


require 'PersonInterface.php';


class Person implements PersonInterface
{
    private $name;
    private $mother;
    private $father;

    /**
     * Person constructor.
     * @param $name
     * @param $mother
     * @param $father
     */
    public function __construct(string $name, PersonInterface $mother = null, PersonInterface $father = null)
    {
        $this->name = $name;
        $this->mother = $mother;
        $this->father = $father;
    }

    public function addMother(PersonInterface $mother)
    {
        $this->mother = $mother;
    }

    public function addFather(PersonInterface $father)
    {
        $this->father = $father;
    }
}
