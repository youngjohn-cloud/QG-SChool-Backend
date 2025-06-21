<?php

namespace Database\Seeders;

use App\Models\Admin;
use App\Models\Announcement;
use App\Models\Assignment;
use App\Models\Attendance;
use App\Models\Event;
use App\Models\Exam;
use App\Models\Lesson;
use App\Models\Level;
use App\Models\Qg_Class;
use App\Models\Result as ModelsResult;
use App\Models\Student;
use App\Models\Subject;
use App\Models\Teacher;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Admins 
        Admin::create([
            'email' => 'admin@example.com',
            'password' => bcrypt('password'),
            'firstname' => 'John',
            'lastname' => 'Doe',
        ]);

        // Users or parent or guardian
        for ($i = 1; $i <= 25; $i++) {
            User::create([
                'guardian_id' => 'guardian_id' . $i,
                'firstname' => 'firstname' . $i,
                'middlename' => 'middlename' . $i,
                'lastname' => 'lastname' . $i,
                'email' => 'user' . $i . '@example.com',
                'password' => bcrypt('password'),
                'phone' => '0901496036' . $i,
                'address' => 'address' . $i,
                'gender' => $i % 2 === 0 ? 'male' : 'female'
            ]);
        }

        // Students Model
        for ($i = 1; $i <= 50; $i++) {
            // Calculate grades for students
            $gradeId = ($i % 12) + 1;
            $level = $gradeId;
            $parentId = (ceil($i / 2) % 25) ?: 25;
            $student = new  Student([
                'student_id' => 'Student' . $i,
                'firstname' => 'Sfirstname' . $i,
                'lastname' => 'Slastname' . $i,
                'email' =>  'student' . $i . '@example.com',
                'password' => bcrypt('password'),
                'phone' => '0901496036' . $i,
                'gender' => $i % 2 === 0 ? 'male' : 'female',
                'home_address' => 'address' . $i,
                'dob' => Carbon::now()->subYears(10),
                'blood_type' => 'A+'
            ]);
            $student->guardian_id = $parentId;
            $student->level_id = $level;
            $student->class_id = $level;
            $student->save();
        }
        // Exams
        $exam_start_time = Carbon::now()->addHours(1);
        $exam_end_time = Carbon::now()->addHours(2);

        for ($i = 1; $i <= 30; $i++) {
            $exam =  new Exam([
                'title' => 'Exam' . $i,
                'startTime' => $exam_start_time,
                'endTime' => $exam_end_time
            ]);
            $exam->lesson_id = ($i % 30) + 1;
            $exam->save();
        }

        // Lessons
        for ($i = 1; $i <= 30; $i++) {
            $days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday'];
            $day = $days[array_rand($days)];

            $start_time = Carbon::now()->addHours(1);
            $end_time = Carbon::now()->addHours(3);

            $lessons = new Lesson([
                'name' => 'Lesson' . $i,
                'day' => $day,
                'start_time' => $start_time,
                'end_time' => $end_time,
            ]);
            // Connect lessons to a class, subject and teachers
            $lessons->class_id = ($i % 12) + 1;
            $lessons->subject_id = ($i % 43) + 1;
            $lessons->teacher_id = ($i % 15) + 1;
            $lessons->save();
        }

        // Results 
        for ($i = 1; $i <= 20; $i++) {
            $result = new ModelsResult([
                'score' => 90,
            ]);
            // Conditionally add exam_id or assignment_id
            if ($i <= 10) {
                $result->exam_id = $i;
                $result->assignment_id = null;
            } else {
                $result->exam_id = null;
                $result->assignment_id = $i - 10;
            }
            $result->student_id = $i;
            $result->save();
        }

        // Attendance
        for ($i = 1; $i <= 12; $i++) {
            $attendanceDate = Carbon::now();
            $attendance = new Attendance([
                'present' => true,
                'date' => $attendanceDate,
                'studentId' => 'student' . $i,
            ]);
            $attendance->student_id = $i;
            $attendance->lesson_id = ($i % 30) + 1;
            $attendance->save();
        }

        // subject Model
        // Define subjects for Primary School (Pry 1–6)
        $primarySubjects = [
            'English Studies',
            'Mathematics',
            'Basic Science and Technology',
            'Social Studies',
            'Civic Education',
            'Cultural and Creative Arts',
            'Physical and Health Education',
            'Computer Studies',
            'Home Economics',
            'Agricultural Science',
            'Religious Studies (Christian/Islamic)',
        ];

        // Define subjects for Junior Secondary School (JSS 1–3)
        $juniorSecondarySubjects = [
            'English Language',
            'Mathematics',
            'Basic Science',
            'Basic Technology',
            'Social Studies',
            'Civic Education',
            'Cultural and Creative Arts',
            'Physical and Health Education',
            'Computer Studies',
            'Home Economics',
            'Agricultural Science',
            'Business Studies',
            'Religious Studies (Christian/Islamic)',
            'French',
        ];

        // Define subjects for Senior Secondary School (SSS 1–3)
        $seniorSecondarySubjects = [
            'English Language',
            'Mathematics',
            'Physics',
            'Chemistry',
            'Biology',
            'Further Mathematics (optional)',
            'Geography',
            'Economics',
            'Government',
            'Literature in English',
            'Christian Religious Studies (CRS)',
            'Islamic Religious Studies (IRS)',
            'Agricultural Science',
            'Computer Studies',
            'Technical Drawing',
            'Food and Nutrition',
            'French ',
            'Music',
        ];
        //  Insert Primary School subjects
        foreach ($primarySubjects as $subjects) {
            Subject::create([
                'name' => $subjects,
                'level' => 'pry',
            ]);
        }
        //  Insert Primary School subjects
        foreach ($juniorSecondarySubjects as $subjects) {
            Subject::create([
                'name' => $subjects,
                'level' => 'jss',
            ]);
        }
        //  InsertJunior Secondary School subjects
        foreach ($seniorSecondarySubjects as $subjects) {
            Subject::create([
                'name' => $subjects,
                'level' => 'sss',
            ]);
        }
        // Levels for students
        // Define classes for each level
        function getClassLevels($i)
        {
            if ($i <= 6) {
                return 'Pry' . $i;
            } elseif ($i <= 9) {
                return 'JSS' . ($i - 6);
            } else {
                return 'SSS' . ($i - 9);
            }
        }
        for ($i = 1; $i <= 12; $i++) {
            $level = new Level();
            $level->name = getClassLevels($i);
            $level->save();
        }
        // Class Model
        for ($i = 1; $i <= 12; $i++) {
            $class = new Qg_Class();
            $class->name = getClassLevels($i);
            $class->level_id = $i;
            $class->teacher_id = $i;
            $class->capacity = rand(15, 20);
            $class->save();
        }

        // Teachers
        for ($i = 1; $i <= 15; $i++) {
            $teacher = new Teacher([
                'teacher_id' => 'teacherId' . $i,
                'firstname' => 'Tfirstname' . $i,
                'lastname' => 'Tlastname' . $i,
                'email' =>  'teacher' . $i . '@example.com',
                'password' => bcrypt('Tpassword'),
                'phone' => '0901496036' . $i,
                'gender' => $i % 2 === 0 ? 'male' : 'female',
                'address' => 'address' . $i,
                'blood_type' => 'A+',
                'dob' => Carbon::now()->subYears(30)->toDateString()
            ]);

            $teacher->save();
            // seed pivot table for teacher and subject
            $subjectId = ($i % 43) + 1;
            $teacher->subjects()->attach($subjectId);
        }

        // Assignments
        $assignment_start_time = Carbon::now()->addHours(1);
        $assignment_exam_end_time = Carbon::now()->addHours(1);

        for ($i = 1; $i <= 10; $i++) {
            $assignment = new Assignment([
                'title' => 'Assignment' . $i,
                'start_date' => $assignment_start_time,
                'due_date' => $assignment_exam_end_time
            ]);
            $assignment->lesson_id = ($i % 30) + 1;
            $assignment->save();
        }

        // Event
        $event_start_time = Carbon::now()->addHours(1);
        $event_exam_end_time = Carbon::now()->addHours(1);

        for ($i = 1; $i <= 5; $i++) {
            $events = new Event([
                'title' => 'title' . $i,
                'description' => 'description' . $i,
                'start_time' => $event_start_time,
                'end_time' => $event_exam_end_time,
            ]);
            $events->class_id = ($i % 12) + 1;
            $events->save();
        }

        // Announcement
        $announcementdate = Carbon::now();
        for ($i = 0; $i <= 5; $i++) {
            $announcement = new Announcement([
                'description' => 'Description for Announcement' . $i,
                'title' => 'Title for Announcement' . $i,
                'date' => $announcementdate,
            ]);
            $announcement->class_id = ($i % 12) + 1;
            $announcement->save();
        }
    }
}
