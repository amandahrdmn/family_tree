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
        $msg = "The family member wasn't found in this tree.";
        $search_list[] = $this->getName();

        if (end($search_list) === $name) {

            return [
                'success' => true,
                'msg' => "This tree has a " . $name .
                    ". Please see the search list if you wish to determine how they are related.",
                'data' => $search_list
            ];
        } else {
            try {
                ['success' => $found,
                    'msg' => $msg,
                    'data' => $search_list_mother] =
                    $this->getMother()->searchForFamilyMemberDepth($name);

                $search_list = array_merge($search_list, $search_list_mother);

                if ($found) {

                    return [
                        'success' => 1,
                        'msg' => $msg,
                        'data' => $search_list
                    ];
                }
            } catch(\Throwable $e) {}

            try {
                ['success' => $found,
                    'msg' => $msg,
                    'data' => $search_list_father] =
                    $this->getFather()->searchForFamilyMemberDepth($name);
                $search_list = array_merge($search_list, $search_list_father);

                if ($found) {

                    return [
                        'success' => 1,
                        'msg' => $msg,
                        'data' => $search_list
                    ];
                }
            } catch(\Throwable $e) {}
        }

        return [
            'success' => 0,
            'msg' => $msg,
            'data' => $search_list
        ];
    }

    public function searchForFamilyMemberBreadth($name) {
        $msg = "This tree has a " . $name .
            ". Please see the search list if you wish to determine how they are related.";
        $search_list_names = [];
        $search_list_people[] = $this;

        $i = 0;
        while ($i < count($search_list_people)) {
            $person = $search_list_people[$i];

            if ($i === 0) {
                $search_list_names[] = $person->getName();

                if (end($search_list_names) === $name) {

                    return  [
                        'success' => true,
                        'msg' => $msg,
                        'data' => $search_list_names
                    ];
                }
            }

            try {
                $search_list_names[] = $person->getMother()->getName();
                if (end($search_list_names) === $name) {

                    return [
                        'success' => true,
                        'msg' => $msg,
                        'data' => $search_list_names
                    ];
                }

                $parents[] = $person->getMother();
            } catch(\Throwable $e) {}

            try {
                $search_list_names[] = $person->getFather()->getName();
                if (end($search_list_names) === $name) {

                    return [
                        'success' => true,
                        'msg' => $msg,
                        'data' => $search_list_names
                    ];
                }

                $parents[] = $person->getFather();
            } catch(\Throwable $e) {}

            $search_list_people = array_merge($search_list_people, $parents);

            $i++;
        }

        return [
            'success' => false,
            'msg' => "The family member wasn't found in this tree.",
            'data' => []
        ];
    }
}
