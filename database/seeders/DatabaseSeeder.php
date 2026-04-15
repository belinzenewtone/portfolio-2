<?php

namespace Database\Seeders;

use App\Models\Education;
use App\Models\Profile;
use App\Models\WorkExperience;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        Profile::create([
            'name' => 'Belinze (Newtone) Ojing',
            'title' => 'Software and Cloud Engineer',
            'subtitle' => 'Software · Cloud · Data',
            'location' => 'Nairobi County, Kenya',
            'bio' => 'Software and Data Engineer with a strong foundation in programming and problem-solving. Eager to learn and grow, adaptable to new challenges, and open to exploring different domains to expand skills and contribute across projects, currently embarking on networking technologies and data pipelines.',
            'email' => 'belinze.newtone@gmail.com',
            'github_url' => 'https://github.com/belinzenewtone',
            'linkedin_url' => 'https://www.linkedin.com/in/belinze-ojing-1b4738197/',
            'current_company' => 'Jamii Telecommunications Ltd',
        ]);

        $works = [
            [
                'company' => 'Jamii Telecommunications Ltd',
                'role' => 'Software Associate',
                'description' => 'Working as a Software Associate at Jamii Telecommunications Ltd, contributing to software development and infrastructure projects.',
                'period_start' => '2024-10-01',
                'period_end' => null,
                'is_current' => true,
                'location' => 'On-site',
                'employment_type' => 'Full-time',
                'sort_order' => 1,
            ],
            [
                'company' => 'The Catholic University of Eastern Africa',
                'role' => 'Full-stack Developer',
                'description' => 'Assisted lecturers in computer science courses with lab sessions and helped organize workshops and coding boot camps. Provided technical support to faculty, staff, and students, troubleshooting hardware, software, networks, and IT infrastructure. Documented work, wrote reports, and presented findings to supervisors.',
                'period_start' => '2023-05-01',
                'period_end' => '2023-08-31',
                'is_current' => false,
                'location' => 'Nairobi County, Kenya · On-site',
                'employment_type' => 'Internship',
                'sort_order' => 2,
            ],
            [
                'company' => 'Kenya Forest Service',
                'role' => 'IT Support Specialist',
                'description' => 'Worked with in-house developers maintaining the corporate website and adding features like automated employee report generation. Handled OS activation, software installation. Troubleshot network elements. Improved company efficiency by 20%+ by maintaining the database and creating awareness of the information input system.',
                'period_start' => '2022-05-01',
                'period_end' => '2022-08-31',
                'is_current' => false,
                'location' => 'Nairobi County, Kenya · On-site',
                'employment_type' => 'Internship',
                'sort_order' => 3,
            ],
        ];

        foreach ($works as $work) {
            WorkExperience::create($work);
        }

        $educations = [
            [
                'institution' => 'The Catholic University of Eastern Africa',
                'degree' => 'Bachelor of Science',
                'field' => 'Computer Science',
                'description' => 'Headed the Department of Computer and Information Science and ensured effective administration of lectures, projects, and fieldwork. Coordinated tech events and career talks in tech. Handled grievances and suggestions on improving the department.',
                'period_start' => '2019-03-01',
                'period_end' => '2023-10-31',
                'sort_order' => 1,
            ],
            [
                'institution' => 'Agoro Sare High School',
                'degree' => 'High School Certificate',
                'field' => 'General Studies',
                'description' => 'Skills: Team Leadership and Problem Solving.',
                'period_start' => '2015-01-01',
                'period_end' => '2018-12-31',
                'sort_order' => 2,
            ],
        ];

        foreach ($educations as $education) {
            Education::create($education);
        }
    }
}
