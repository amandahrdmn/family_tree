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
    public function getParent(string $type): PersonInterface
    {
        if ($type === 'mother') {
            return $this->mother;
        } elseif ($type === 'father') {
            return $this->father;
        }
    }

    public function getParentName()
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
        $msg = [
            "The family member wasn't found in this tree.",
            "This tree has a " . $name .
            ". Please see the search list if you wish to determine how they are related."
        ];

        $search_list = [];
        $personName = $this->getName();
        $search_list[] = $personName;

        if ($personName === $name) {
            $found = true;

            return [$found, $msg[1], $search_list];
        } else {
            try {
                $mother = $this->getParent('mother');
                [$found, $msg, $search_list_mother] = $mother->searchForFamilyMemberDepth($name);
                $search_list = array_merge($search_list, $search_list_mother);
            } catch(\Throwable $e) {}

            if (!$found) {
                try {
                    $father = $this->getParent('father');
                    [$found, $msg, $search_list_father] = $father->searchForFamilyMemberDepth($name);
                    $search_list = array_merge($search_list, $search_list_father);
                } catch(\Throwable $e) {}
            }

            $msg = gettype($msg) === 'array' ? $msg[0] : $msg;
        }

        return [$found, $msg, $search_list];
    }

    public function searchForFamilyMemberBreadth($name) {
        $found = false;
        $msg = [
            "The family member wasn't found in this tree.",
            "This tree has a " . $name .
            ". Please see the search list if you wish to determine how they are related."
        ];
        $search_list_names = [];
        $search_list_people[] = $this;

        $i = 0;
        while ($i < count($search_list_people)) {
            $person = $search_list_people[$i];

            if ($i === 0) {
                $personName = $person->getName();
                $search_list_names[] = $personName;

                $found = $personName === $name;
            }

            if (!$found) {
                $parents = [];
                $parentTypes = ['mother', 'father'];

                foreach ($parentTypes as $parentType) {
                    try {
                        $parent = $person->getParent($parentType);
                        $parents[] = $parent;
                        $parentName = $parent->getName();
                        $search_list_names[] = $parentName;

                        $found = $parentName === $name;

                    } catch(\Throwable $e) {}

                    if ($found) {
                        break;
                    }
                }

                $search_list_people = array_merge($search_list_people, $parents);
            }

            $i++;
        }
        return [$found, $msg[intval($found)], $search_list_names];
    }
}
