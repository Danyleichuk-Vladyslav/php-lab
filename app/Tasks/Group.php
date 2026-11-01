<?php

declare(strict_types=1);

namespace App\Tasks;

class Group
{
    public function __construct(
        private array $students,
        public readonly int $group_id,
        public readonly string $name,
    ) {}

    public function addStudent(Student $student): void
    {
        $this->students[] = $student;
    }

    public function removeStudent(int $id): void
    {
        $this->students = array_values(array_filter(
            $this->students,
            static fn (Student $student): bool => $student->id !== $id,
        ));
    }

    public function searchStudent(string $name): ?Student
    {
        $needle = mb_strtolower($name);

        foreach ($this->students as $student) {
            $fullName = mb_strtolower($student->name.' '.$student->surname);

            if (mb_strtolower($student->name) === $needle || mb_strtolower($student->surname) === $needle || str_contains($fullName, $needle)) {
                return $student;
            }
        }

        return null;
    }

    public function students(): array
    {
        return $this->students;
    }
}
