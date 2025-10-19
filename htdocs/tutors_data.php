<?php

$COURSE_CATALOG = [
    [
        'id' => 'dse-english',
        'name' => 'DSE English',
        'description' => 'Master the DSE English curriculum with exam strategies and real past paper practice.'
    ],
    [
        'id' => 'ielts',
        'name' => 'IELTS Preparation',
        'description' => 'Target your desired IELTS band with focused speaking, listening, reading, and writing training.'
    ],
    [
        'id' => 'junior-english',
        'name' => 'Junior English',
        'description' => 'Build foundations in grammar, vocabulary, and confidence through interactive activities.'
    ],
];

$TUTORS = [
    [
        'slug' => 'timothy-lee',
        'name' => 'Timothy Lee',
        'credentials' => 'HKU BA & BEd in English',
        'photo' => 'https://i.pravatar.cc/300?img=12',
        'bio' => 'Timothy specializes in DSE English reading comprehension and writing structure. He has helped 100+ students improve by 2–3 levels within a year.',
        'courses' => ['dse-english', 'ielts']
    ],
    [
        'slug' => 'charlene-man',
        'name' => 'Charlene Man',
        'credentials' => "Master's from UK University",
        'photo' => 'https://i.pravatar.cc/300?img=47',
        'bio' => 'Charlene focuses on IELTS speaking and academic writing, with practical drills and proven templates for Band 7+.',
        'courses' => ['ielts']
    ],
    [
        'slug' => 'clara-li',
        'name' => 'Clara Li',
        'credentials' => 'HKU Master of Arts in English',
        'photo' => 'https://i.pravatar.cc/300?img=32',
        'bio' => 'Clara builds strong grammar and vocabulary foundations for juniors, using games and short projects.',
        'courses' => ['junior-english']
    ],
    [
        'slug' => 'jason-wong',
        'name' => 'Jason Wong',
        'credentials' => 'Cambridge CELTA Certified',
        'photo' => 'https://i.pravatar.cc/300?img=15',
        'bio' => 'Jason has a decade of experience preparing students for IELTS and UK boarding school interviews.',
        'courses' => ['ielts', 'junior-english']
    ],
    [
        'slug' => 'emily-chan',
        'name' => 'Emily Chan',
        'credentials' => 'HKU BEd (LangEd) English',
        'photo' => 'https://i.pravatar.cc/300?img=5',
        'bio' => 'Emily focuses on reading strategies and argumentative writing for DSE Paper 2 & 3.',
        'courses' => ['dse-english']
    ],
    [
        'slug' => 'nathan-ho',
        'name' => 'Nathan Ho',
        'credentials' => 'Trinity CertTESOL',
        'photo' => 'https://i.pravatar.cc/300?img=23',
        'bio' => 'Nathan designs speaking workshops that boost fluency and pronunciation for both DSE and IELTS.',
        'courses' => ['dse-english', 'ielts']
    ],
    [
        'slug' => 'sophia-ng',
        'name' => 'Sophia Ng',
        'credentials' => 'MA Applied Linguistics',
        'photo' => 'https://i.pravatar.cc/300?img=49',
        'bio' => 'Sophia brings linguistics insights into classroom practice, making grammar simple and memorable.',
        'courses' => ['junior-english', 'dse-english']
    ],
    [
        'slug' => 'daniel-cheung',
        'name' => 'Daniel Cheung',
        'credentials' => 'BA English Literature, UCL',
        'photo' => 'https://i.pravatar.cc/300?img=7',
        'bio' => 'Daniel mentors high-achievers aiming for top grades with deep reading and essay craft.',
        'courses' => ['dse-english']
    ],
];

function findTutorBySlug($slug, $tutors)
{
    foreach ($tutors as $tutor) {
        if ($tutor['slug'] === $slug) {
            return $tutor;
        }
    }
    return null;
}

function getCourseById($courseId, $catalog)
{
    foreach ($catalog as $course) {
        if ($course['id'] === $courseId) {
            return $course;
        }
    }
    return null;
}



