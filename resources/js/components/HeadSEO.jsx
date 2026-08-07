import { Head } from '@inertiajs/react';

export default function HeadSEO({ title, description, image, url, type = 'website', publishedTime, modifiedTime }) {
    const siteName = 'KekTech';
    const fullTitle = title ? `${title} | ${siteName}` : siteName;
    const metaDescription = description || 'Full-stack developer portfolio — projects, skills, blog, and contact.';
    const metaImage = image || '/favicon.ico';
    const canonical = url || (typeof window !== 'undefined' ? window.location.href : '');

    return (
        <Head>
            <title>{fullTitle}</title>
            <meta name="description" content={metaDescription} />
            <link rel="canonical" href={canonical} />

            {/* Open Graph */}
            <meta property="og:type" content={type} />
            <meta property="og:title" content={fullTitle} />
            <meta property="og:description" content={metaDescription} />
            <meta property="og:image" content={metaImage} />
            <meta property="og:url" content={canonical} />
            <meta property="og:site_name" content={siteName} />

            {/* Twitter Card */}
            <meta name="twitter:card" content="summary_large_image" />
            <meta name="twitter:title" content={fullTitle} />
            <meta name="twitter:description" content={metaDescription} />
            <meta name="twitter:image" content={metaImage} />

            {publishedTime && <meta property="article:published_time" content={publishedTime} />}
            {modifiedTime && <meta property="article:modified_time" content={modifiedTime} />}

            {/* JSON-LD Structured Data */}
            <script type="application/ld+json" dangerouslySetInnerHTML={{ __html: JSON.stringify({
                '@context': 'https://schema.org',
                '@type': type === 'article' ? 'Article' : type === 'profile' ? 'Person' : 'WebSite',
                ...(type === 'article' ? {
                    headline: title,
                    description: metaDescription,
                    image: metaImage,
                    url: canonical,
                    datePublished: publishedTime,
                    dateModified: modifiedTime || publishedTime,
                    author: { '@type': 'Person', name: 'Mohammad Kudrat-E-Khuda' },
                } : type === 'profile' ? {
                    name: 'Mohammad Kudrat-E-Khuda',
                    jobTitle: 'Full-Stack Developer',
                    url: canonical,
                    image: metaImage,
                    description: metaDescription,
                    sameAs: [],
                } : {
                    name: siteName,
                    url: canonical,
                    description: metaDescription,
                })
            }) }} />
        </Head>
    );
}
