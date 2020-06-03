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

    public function searchForFamilyMemberDepth(string $name): array
    {
        $found = false;
        $msg = [
            "The family member wasn't found in this tree.",
            "This tree has a " . $name .
            ". Please see the search list if you wish to determine how they are related."
        ];
        $personName = $this->getName();
        $search_list[] = $personName;

        if ($personName === $name) {
            $found = true;

            return [
                'success' => $found,
                'msg' => $msg[1],
                'data' => $search_list
            ];
        } else {
            try {
                $mother = $this->getMother();
                ['success' => $found,
                    'msg' => $msg,
                    'data' => $search_list_mother] =
                    $mother->searchForFamilyMemberDepth($name);

                $search_list = array_merge($search_list, $search_list_mother);

                if ($found) {

                    return [
                        'success' => $found,
                        'msg' => $msg,
                        'data' => $search_list
                    ];
                }
            } catch(\Throwable $e) {}

            try {
                $father = $this->getFather();
                ['success' => $found,
                    'msg' => $msg,
                    'data' => $search_list_father] =
                    $father->searchForFamilyMemberDepth($name);
                $search_list = array_merge($search_list, $search_list_father);

                if ($found) {

                    return [
                        'success' => $found,
                        'msg' => $msg,
                        'data' => $search_list
                    ];
                }
            } catch(\Throwable $e) {}
        }

        return [
            'success' => $found,
            'msg' => $msg[intval($found)],
            'data' => $search_list
        ];
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

                if ($personName === $name) {

                    return  [
                        'success' => true,
                        'msg' => $msg[1],
                        'data' => $search_list_names
                    ];
                }
            }

            if (!$found) {
                $parents = [];

                try {
                    $mother = $person->getMother();
                    $parentName = $mother->getName();
                    $search_list_names[] = $parentName;

                    if ($parentName === $name) {

                        return [
                            'success' => true,
                            'msg' => $msg[1],
                            'data' => $search_list_names
                        ];
                    }

                    $parents[] = $mother;
                } catch(\Throwable $e) {}

                try {
                    $father = $person->getFather();
                    $parentName = $father->getName();
                    $search_list_names[] = $parentName;

                    if ($parentName === $name) {

                        return [
                            'success' => true,
                            'msg' => $msg[1],
                            'data' => $search_list_names
                        ];
                    }

                    $parents[] = $father;
                } catch(\Throwable $e) {}


                $search_list_people = array_merge($search_list_people, $parents);
            }

            $i++;
        }

        return [
            'success' => false,
            'msg' => $msg[0],
            'data' => []
        ];
    }
}
