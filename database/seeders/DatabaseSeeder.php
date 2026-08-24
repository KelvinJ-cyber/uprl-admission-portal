<?php

namespace Database\Seeders;

use App\Models\Course;
use App\Models\CourseRequiredSubject;
use App\Models\CourseRequirement;
use App\Models\Department;
use App\Models\Faculty;
use App\Models\Staff;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Staff::factory(10)->create();

        Staff::create([
            'name' => 'Test Staff',
            'email' => 'test@example.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        $faculty = Faculty::create([
            'name' => 'Faculty of Communication and Media Studies',
            'code' => 'FCMS',
        ]);

        $prDepartment = Department::create([
            'faculty_id' => $faculty->id,
            'name' => 'Department of Public Relations',
            'code' => 'DPR',
        ]);

        $massCommDepartment = Department::create([
            'faculty_id' => $faculty->id,
            'name' => 'Department of Mass Communication',
            'code' => 'DMC',
        ]);

        $polSciFaculty = Faculty::create([
            'name' => 'Faculty of Social Sciences',
            'code' => 'FSS',
        ]);

        $polSciDepartment = Department::create([
            'faculty_id' => $polSciFaculty->id,
            'name' => 'Department of Political Science',
            'code' => 'DPS',
        ]);

        // Course 1: Bachelor of Public Relations (from spec example)
        $this->createCourseWithRequirements(
            faculty: $faculty,
            department: $prDepartment,
            name: 'Bachelor of Public Relations',
            code: 'BPR',
            minUtmeScore: 180,
            minCredits: 5,
            requiredSubjects: [
                ['subject_name' => 'English Language', 'minimum_grade' => 'C6', 'is_mandatory' => true],
                ['subject_name' => 'Literature in English', 'minimum_grade' => 'C6', 'is_mandatory' => true],
                ['subject_name' => 'Government', 'minimum_grade' => 'C6', 'is_mandatory' => true],
                ['subject_name' => 'CRS/IRS', 'minimum_grade' => 'C6', 'is_mandatory' => false],
                ['subject_name' => 'Economics', 'minimum_grade' => 'C6', 'is_mandatory' => false],
            ]
        );

        // Course 2: Bachelor of Mass Communication (similar profile, slightly lower cutoff)
        $this->createCourseWithRequirements(
            faculty: $faculty,
            department: $massCommDepartment,
            name: 'Bachelor of Mass Communication',
            code: 'BMC',
            minUtmeScore: 170,
            minCredits: 5,
            requiredSubjects: [
                ['subject_name' => 'English Language', 'minimum_grade' => 'C6', 'is_mandatory' => true],
                ['subject_name' => 'Literature in English', 'minimum_grade' => 'C6', 'is_mandatory' => true],
                ['subject_name' => 'Government', 'minimum_grade' => 'C6', 'is_mandatory' => false],
                ['subject_name' => 'Economics', 'minimum_grade' => 'C6', 'is_mandatory' => false],
            ]
        );

        // Course 3: Bachelor of Political Science (higher cutoff, different subjects)
        $this->createCourseWithRequirements(
            faculty: $polSciFaculty,
            department: $polSciDepartment,
            name: 'Bachelor of Political Science',
            code: 'BPS',
            minUtmeScore: 200,
            minCredits: 5,
            requiredSubjects: [
                ['subject_name' => 'English Language', 'minimum_grade' => 'C6', 'is_mandatory' => true],
                ['subject_name' => 'Government', 'minimum_grade' => 'B3', 'is_mandatory' => true],
                ['subject_name' => 'Mathematics', 'minimum_grade' => 'C6', 'is_mandatory' => true],
                ['subject_name' => 'Economics', 'minimum_grade' => 'C6', 'is_mandatory' => false],
            ]
        );
    }

    protected function createCourseWithRequirements(
        Faculty $faculty,
        Department $department,
        string $name,
        string $code,
        int $minUtmeScore,
        int $minCredits,
        array $requiredSubjects
    ): void {
        $course = Course::create([
            'faculty_id' => $faculty->id,
            'department_id' => $department->id,
            'name' => $name,
            'code' => $code,
            'min_utme_score' => $minUtmeScore,
        ]);

        CourseRequirement::create([
            'course_id' => $course->id,
            'utme_subject_1' => $requiredSubjects[0]['subject_name'] ?? 'English Language',
            'utme_subject_2' => $requiredSubjects[1]['subject_name'] ?? 'General Paper',
            'utme_subject_3' => $requiredSubjects[2]['subject_name'] ?? 'General Paper',
            'utme_subject_4' => $requiredSubjects[3]['subject_name'] ?? 'General Paper',
            'min_olevel_credits' => $minCredits,
        ]);

        foreach ($requiredSubjects as $subject) {
            CourseRequiredSubject::create(array_merge($subject, ['course_id' => $course->id]));
        }
    }
}
