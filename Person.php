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

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return PersonInterface
     */
    public function getMother(): PersonInterface
    {
        return $this->mother;
    }

    /**
     * @return PersonInterface
     */
    public function getFather(): PersonInterface
    {
        return $this->father;
    }

    public function getMotherName()
    {
        try {
            return $this->mother->getName();
        } catch(\Throwable $e) {
            return null;
        }
    }

    public function getFatherName()
    {
        try {
            return $this->father->getName();
        } catch(\Throwable $e) {
            return null;
        }
    }

    public function searchForFamilyMemberDepth(string $name): array
    {
        $found = false;
        $search_list = [];
        $personName = $this->getName();
        $search_list[] = [$personName];

        if ($personName === $name) {
            $found = true;

            return [$found, $search_list];
        } else {
            try {
                $mother = $this->getMother();
            } catch(\Throwable $e) {
                $mother = null;
            }

            if ($mother != null) {
                [$found, $search_list_mother] = $mother->searchForFamilyMemberDepth($name);
                $search_list = array_merge($search_list, $search_list_mother);
            }

            if (!$found) {
                try {
                    $father = $this->getFather();
                } catch(\Throwable $e) {
                    $father = null;
                }

                if ($father != null) {
                    [$found, $search_list_father] = $father->searchForFamilyMemberDepth($name);
                    $search_list = array_merge($search_list, $search_list_father);
                }
            }
        }

        return [$found, $search_list];
    }

    public function searchForFamilyMemberBreadth($name) {
        $found = false;
        $search_list = [];
        $personName = $this->getName();
        $search_list[] = [$personName];

        if ($personName === $name) {
            $found = true;

            return [$found, $search_list];
        } else {
            try {
                $mother = $this->getMother();
            } catch(\Throwable $e) {
                $mother = null;
            }
        }

        return [$found, $search_list];
    }
}
