<?php

namespace Database\Seeders;

use App\Models\BlogPost;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class BlogPostSeeder extends Seeder
{
    public function run(): void
    {
        $posts = [
            [
                'title' => 'Building a Custom WordPress Theme from Scratch',
                'slug' => 'building-custom-wordpress-theme',
                'category' => 'WordPress',
                'published_at' => now()->subDays(2),
                'content' => <<<'MD'
# Building a Custom WordPress Theme from Scratch

Creating a custom WordPress theme gives you full control over design and functionality. Here is how I approach it.

## Why Build Custom?

Pre-built themes often come with bloat you don't need. A custom theme means:

- **Clean, optimized code** - no unused features
- **Faster load times** - only what you need
- **Full design control** - pixel-perfect results
- **Better security** - fewer third-party vulnerabilities

## Project Structure

A well-organized WordPress theme follows this structure:

```
theme-name/
  style.css
  functions.php
  index.php
  header.php
  footer.php
  page-templates/
  template-parts/
  assets/
    css/
    js/
    images/
```

## Key Steps

1. **Set up the theme skeleton** - Create `style.css` with theme header, `functions.php` to enqueue assets
2. **Register menus and sidebars** - Use `register_nav_menus()` and `register_sidebar()`
3. **Create template parts** - Reusable components for cards, loops, etc.
4. **Build with ACF** - Advanced Custom Fields makes flexible page templates effortless
5. **Add Gutenberg blocks** - Custom blocks let editors build pages visually

## Performance Tips

- Lazy load images with `loading="lazy"`
- Defer non-critical JavaScript
- Use `wp_enqueue_style()` properly
- Minify and combine assets for production

Building custom themes is rewarding work. The result is a site that is fast, maintainable, and exactly what the client needs.
MD,
            ],
            [
                'title' => 'Laravel + Inertia.js + React: The Perfect Stack',
                'slug' => 'laravel-inertia-react-perfect-stack',
                'category' => 'Full Stack',
                'published_at' => now()->subDays(5),
                'content' => <<<'MD'
# Laravel + Inertia.js + React: The Perfect Stack

After building projects with both traditional Laravel Blade and SPA architectures, I found the sweet spot with Inertia.js.

## The Problem with SPAs

Traditional SPAs (React/Vue + API) require:

- Separate API route definitions
- Authentication token management
- CORS configuration
- Client-side routing setup
- Loading states for every API call

## Why Inertia.js Changes Everything

Inertia acts as a bridge between Laravel and React. You get:

- **Server-side routing** - standard Laravel routes
- **Zero API layer** - no separate API needed
- **Shared data** - pass props from controllers directly
- **Full page visits** - SEO-friendly, no loading spinners
- **Client-side navigation** - SPA-like smooth transitions

## How It Works

### 1. Controller returns Inertia render

```php
public function index()
{
    $projects = Project::latest()->paginate(12);
    return Inertia::render('Projects/Index', [
        'projects' => $projects,
    ]);
}
```

### 2. React page receives props

```jsx
export default function Index({ projects }) {
    return (
        <div>
            {projects.data.map(project => (
                <ProjectCard key={project.id} project={project} />
            ))}
        </div>
    );
}
```

### 3. Links use Inertia components

```jsx
import { Link } from '@inertiajs/react';

<Link href="/projects/123">View Project</Link>
```

## My Experience

I have built a portfolio site, a booking platform, and a real estate listing app with this stack. It dramatically reduces development time while maintaining the simplicity of server-side rendering.
MD,
            ],
            [
                'title' => 'WooCommerce: Custom Payment Gateway Integration',
                'slug' => 'woocommerce-custom-payment-gateway',
                'category' => 'WordPress',
                'published_at' => now()->subDays(8),
                'content' => <<<'MD'
# WooCommerce: Custom Payment Gateway Integration

Integrating a custom payment gateway in WooCommerce requires understanding the WooCommerce payment API. Here is my step-by-step guide.

## Setting Up the Gateway Class

Every payment gateway extends `WC_Payment_Gateway`:

```php
class WC_Gateway_Custom extends WC_Payment_Gateway {
    public function __construct() {
        $this->id = 'custom_gateway';
        $this->has_fields = false;
        $this->init_form_fields();
        $this->init_settings();
    }
}
```

## Key Methods

- `process_payment()` - Handle the actual payment
- `process_refund()` - Handle refund requests
- `thankyou_page()` - Custom thank you page
- `payment_fields()` - Display payment form fields

## Security Best Practices

1. **Never store raw card data** - Use tokenization
2. **Validate server-side** - Don't trust client validation
3. **Use HTTPS everywhere** - Encrypt all communication
4. **Log transactions** - Keep audit trails
5. **Handle webhooks securely** - Verify signatures

## Testing

Always test with:

- Successful payments
- Failed payments
- Refunds
- Subscriptions (if applicable)
- Currency edge cases

Custom payment gateways give you flexibility when off-the-shelf solutions don't fit your business model.
MD,
            ],
            [
                'title' => 'REST API Design Best Practices with Laravel',
                'slug' => 'rest-api-design-best-practices-laravel',
                'category' => 'Full Stack',
                'published_at' => now()->subDays(12),
                'content' => <<<'MD'
# REST API Design Best Practices with Laravel

A well-designed API is the backbone of any modern application. Here are the practices I follow when building APIs with Laravel.

## Resource Naming

Use nouns, not verbs:

```
GET    /api/projects       (list)
GET    /api/projects/1     (show)
POST   /api/projects       (create)
PUT    /api/projects/1     (update)
DELETE /api/projects/1     (delete)
```

## Response Format

Consistent JSON structure:

```json
{
    "success": true,
    "data": { },
    "meta": {
        "current_page": 1,
        "last_page": 5,
        "per_page": 15
    }
}
```

## Authentication

- Use **Laravel Sanctum** for API tokens
- Implement rate limiting per user/IP
- Use middleware groups for different auth levels

## Error Handling

Return meaningful HTTP status codes:

- `200` - Success
- `201` - Created
- `400` - Bad Request
- `401` - Unauthorized
- `403` - Forbidden
- `404` - Not Found
- `422` - Validation Error
- `500` - Server Error

## Pagination

Always paginate large datasets:

```php
$projects = Project::paginate(15);
return response()->json($projects);
```

## Versioning

Version your API from the start:

```
/api/v1/projects
/api/v2/projects
```

These practices ensure your API is predictable, maintainable, and developer-friendly.
MD,
            ],
            [
                'title' => 'WordPress Performance Optimization Guide',
                'slug' => 'wordpress-performance-optimization',
                'category' => 'WordPress',
                'published_at' => now()->subDays(15),
                'content' => <<<'MD'
# WordPress Performance Optimization Guide

Site speed directly impacts user experience and SEO rankings. Here is how I optimize WordPress sites to load in under 2 seconds.

## Essential Optimizations

### 1. Caching

- **Page caching** - WP Super Cache or W3 Total Cache
- **Object caching** - Redis or Memcached
- **Browser caching** - Set proper cache headers

### 2. Image Optimization

- Use WebP format instead of JPEG/PNG
- Implement lazy loading with `loading="lazy"`
- Serve responsive images with `srcset`
- Compress images before upload

### 3. Database Optimization

- Clean post revisions regularly
- Remove spam comments
- Optimize database tables
- Use transients API for caching queries

### 4. Asset Optimization

- Minify CSS and JavaScript
- Combine files where possible
- Load critical CSS inline
- Defer non-essential scripts

### 5. Hosting

- Use PHP 8.1+ (2-3x faster than PHP 7.x)
- Choose quality hosting (Cloudways, SpinupWP)
- Use CDN for static assets
- Enable HTTP/2

## Measuring Results

Use these tools:

- **Google PageSpeed Insights** - Core Web Vitals
- **GTmetrix** - Detailed performance reports
- **Pingdom** - Load time from different locations

A well-optimized WordPress site can achieve 95+ on PageSpeed Insights consistently.
MD,
            ],
            [
                'title' => 'Deploying Laravel Apps with Docker and Nginx',
                'slug' => 'deploying-laravel-docker-nginx',
                'category' => 'DevOps',
                'published_at' => now()->subDays(18),
                'content' => <<<'MD'
# Deploying Laravel Apps with Docker and Nginx

Containerized deployments make Laravel applications consistent across environments. Here is my production deployment workflow.

## Docker Setup

### docker-compose.yml

```yaml
services:
  app:
    build: .
    volumes:
      - .:/var/www/html
    depends_on:
      - db

  nginx:
    image: nginx:alpine
    ports:
      - "80:80"
    volumes:
      - ./nginx.conf:/etc/nginx/conf.d/default.conf

  db:
    image: mysql:8.0
    environment:
      MYSQL_DATABASE: kektech
      MYSQL_ROOT_PASSWORD: secret
```

## Nginx Configuration

```nginx
server {
    listen 80;
    root /var/www/html/public;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        fastcgi_pass app:9000;
        fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
    }
}
```

## Production Checklist

1. **Set `APP_ENV=production`** and `APP_DEBUG=false`
2. **Run `composer install --optimize-autoloader --no-dev`**
3. **Cache config, routes, and views**
4. **Set proper file permissions** (755 for dirs, 644 for files)
5. **Configure SSL with Let's Encrypt**
6. **Set up automated backups**
7. **Monitor with Laravel Telescope or Pail**

## Benefits

- **Consistent environments** - Same setup dev to production
- **Easy scaling** - Add containers as needed
- **Quick rollbacks** - Revert to previous Docker image
- **Isolation** - Each service runs independently

Docker has transformed how I deploy Laravel applications.
MD,
            ],
        ];

        foreach ($posts as $post) {
            BlogPost::create($post);
        }
    }
}
