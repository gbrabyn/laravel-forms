<?php
namespace App\Repository;


class ProgrammingLanguagesRepository
{
    private $languages = [
        '1' => 'Assembly',
        '2' => 'Bash/Shell/PowerShell',
        '3' => 'C',
        '4' => 'C#',
        '5' => 'C++',
        '6' => 'Go',
        '7' => 'HTML/CSS',
        '8' => 'Java',
        '9' => 'JavaScript',
        '10' => 'Kotlin',
        '11' => 'Perl',
        '12' => 'PHP',
        '13' => 'Python',
        '14' => 'R',
        '15' => 'Ruby',
        '16' => 'SQL',
        '17' => 'Swift',
        '18' => 'TypeScript',
    ];

    public function getAll() : array
    {
        return $this->languages;
    }
}
