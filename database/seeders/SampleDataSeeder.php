<?php

namespace Database\Seeders;

use App\Models\Project;
use App\Models\Skill;
use App\Models\Testimonial;
use Illuminate\Database\Seeder;

class SampleDataSeeder extends Seeder
{
    public function run(): void
    {
        // Skills
        $skills = [
            ['name' => 'PHP', 'category' => 'Backend', 'icon' => 'code', 'level' => 90],
            ['name' => 'Laravel', 'category' => 'Backend', 'icon' => 'code', 'level' => 85],
            ['name' => 'MySQL', 'category' => 'Backend', 'icon' => 'database', 'level' => 80],
            ['name' => 'WordPress', 'category' => 'CMS', 'icon' => 'globe', 'level' => 95],
            ['name' => 'WooCommerce', 'category' => 'CMS', 'icon' => 'globe', 'level' => 88],
            ['name' => 'ACF (Advanced Custom Fields)', 'category' => 'CMS', 'icon' => 'layers', 'level' => 90],
            ['name' => 'React', 'category' => 'Frontend', 'icon' => 'code', 'level' => 78],
            ['name' => 'JavaScript', 'category' => 'Frontend', 'icon' => 'code', 'level' => 85],
            ['name' => 'TypeScript', 'category' => 'Frontend', 'icon' => 'code', 'level' => 75],
            ['name' => 'HTML5 & CSS3', 'category' => 'Frontend', 'icon' => 'palette', 'level' => 95],
            ['name' => 'Tailwind CSS', 'category' => 'Frontend', 'icon' => 'palette', 'level' => 88],
            ['name' => 'Node.js', 'category' => 'Backend', 'icon' => 'server', 'level' => 75],
            ['name' => 'REST APIs', 'category' => 'Backend', 'icon' => 'globe', 'level' => 85],
            ['name' => 'Git & GitHub', 'category' => 'Tools', 'icon' => 'terminal', 'level' => 88],
            ['name' => 'Docker', 'category' => 'Tools', 'icon' => 'server', 'level' => 70],
            ['name' => 'Linux / Ubuntu', 'category' => 'DevOps', 'icon' => 'terminal', 'level' => 80],
            ['name' => 'Nginx / Apache', 'category' => 'DevOps', 'icon' => 'server', 'level' => 78],
            ['name' => 'Inertia.js', 'category' => 'Frontend', 'icon' => 'code', 'level' => 82],
        ];

        foreach ($skills as $skill) {
            Skill::create($skill);
        }

        // Projects
        $projects = [
            [
                'title' => 'E-Commerce WordPress Store',
                'slug' => 'ecommerce-wordpress-store',
                'description' => "A fully functional e-commerce website built with WordPress and WooCommerce. Features include custom product pages, advanced filtering, AJAX cart, payment gateway integration (Stripe & PayPal), order management dashboard, and inventory tracking.\n\nBuilt a custom WooCommerce theme with responsive design, optimized for SEO and fast load times. Integrated ACF for flexible content management and custom post types for product categories.",
                'images' => [],
                'tech_stack' => ['WordPress', 'WooCommerce', 'PHP', 'ACF', 'JavaScript', 'jQuery', 'MySQL', 'Stripe API'],
                'live_url' => 'https://example-store.com',
                'github_url' => null,
                'featured' => true,
            ],
            [
                'title' => 'Restaurant Booking Platform',
                'slug' => 'restaurant-booking-platform',
                'description' => "A full-stack restaurant reservation system built with Laravel and React via Inertia.js. Customers can browse restaurants, view menus, and make real-time table reservations.\n\nAdmin panel for restaurant owners to manage tables, reservations, menus, and view analytics. Features include email notifications, calendar view, and capacity management.",
                'images' => [],
                'tech_stack' => ['Laravel', 'React', 'Inertia.js', 'MySQL', 'Tailwind CSS', 'REST APIs'],
                'live_url' => 'https://example-booking.com',
                'github_url' => 'https://github.com/sizu/restaurant-booking',
                'featured' => true,
            ],
            [
                'title' => 'Corporate WordPress Website',
                'slug' => 'corporate-wordpress-website',
                'description' => "A professional corporate website for a tech company, built on WordPress with a fully custom theme. Includes a dynamic homepage with ACF-powered sections, team member profiles, case studies, and a blog.\n\nImplemented custom Gutenberg blocks for drag-and-drop page editing, multilingual support with WPML, and performance optimization achieving 95+ PageSpeed score.",
                'images' => [],
                'tech_stack' => ['WordPress', 'PHP', 'ACF', 'Gutenberg', 'CSS3', 'WPML', 'JavaScript'],
                'live_url' => 'https://example-corp.com',
                'github_url' => null,
                'featured' => true,
            ],
            [
                'title' => 'Real Estate Listing Platform',
                'slug' => 'real-estate-listing-platform',
                'description' => "A Laravel-based real estate platform with advanced property search, filtering by location, price, type, and amenities. Features include user registration, saved searches, property alerts, and agent dashboards.\n\nIntegrated Google Maps API for interactive property maps and geolocation search. Built with Inertia.js + React for a seamless SPA-like experience.",
                'images' => [],
                'tech_stack' => ['Laravel', 'React', 'Inertia.js', 'MySQL', 'Google Maps API', 'Tailwind CSS'],
                'live_url' => 'https://example-realty.com',
                'github_url' => 'https://github.com/sizu/real-estate',
                'featured' => false,
            ],
            [
                'title' => 'WordPress Learning Management System',
                'slug' => 'wordpress-lms',
                'description' => "An online learning platform built with WordPress and LearnDash. Features course management, video lessons, quizzes, certificates, student progress tracking, and payment integration.\n\nCustom-designed student dashboard, instructor panel, and certificate generation system. WooCommerce integration for course purchases and subscription plans.",
                'images' => [],
                'tech_stack' => ['WordPress', 'LearnDash', 'WooCommerce', 'PHP', 'JavaScript', 'ACF', 'Stripe'],
                'live_url' => 'https://example-lms.com',
                'github_url' => null,
                'featured' => false,
            ],
            [
                'title' => 'Portfolio CMS with Markdown Blog',
                'slug' => 'portfolio-cms-markdown-blog',
                'description' => "The very portfolio website you are viewing! A custom CMS built with Laravel 13, Inertia.js, and React. Features a public portfolio, blog with markdown support, skills showcase, testimonials, and a contact form with email notifications.\n\nAdmin dashboard for managing all content, media uploads, site settings, and SEO configuration. Built with a dark theme design system.",
                'images' => [],
                'tech_stack' => ['Laravel', 'React', 'Inertia.js', 'Tailwind CSS', 'MySQL', 'Vite'],
                'live_url' => 'https://kektech.me',
                'github_url' => 'https://github.com/sizu/kektech',
                'featured' => false,
            ],
        ];

        foreach ($projects as $project) {
            Project::create($project);
        }

        // Testimonials
        $testimonials = [
            [
                'name' => 'Sarah Johnson',
                'role' => 'Marketing Director',
                'company' => 'TechCorp Inc.',
                'content' => 'Mohammad delivered an outstanding e-commerce platform that exceeded our expectations. His WordPress expertise and attention to detail resulted in a site that increased our online sales by 40%. Highly recommend his work!',
                'avatar' => null,
                'rating' => 5,
            ],
            [
                'name' => 'Ahmad Rahman',
                'role' => 'CEO',
                'company' => 'StartupHub',
                'content' => 'Working with KekTech was a fantastic experience. The Laravel application was delivered on time with clean, maintainable code. The real estate platform he built handles thousands of listings effortlessly.',
                'avatar' => null,
                'rating' => 5,
            ],
            [
                'name' => 'Emily Chen',
                'role' => 'Product Manager',
                'company' => 'EduTech Solutions',
                'content' => 'The WordPress LMS we commissioned was complex, but Mohammad handled it with ease. From custom course management to payment integration, everything worked perfectly from day one.',
                'avatar' => null,
                'rating' => 5,
            ],
            [
                'name' => 'David Park',
                'role' => 'Founder',
                'company' => 'RestaurantHub',
                'content' => 'Our booking platform handles 500+ reservations daily without issues. The Laravel + React stack was a great choice, and the admin panel is incredibly intuitive. Great full-stack work!',
                'avatar' => null,
                'rating' => 4,
            ],
        ];

        foreach ($testimonials as $testimonial) {
            Testimonial::create($testimonial);
        }
    }
}
