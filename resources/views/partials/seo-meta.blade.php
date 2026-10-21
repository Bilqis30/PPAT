@php
    $siteName = 'Kantor PPAT Iwan Kurniawan, SH., MH., M.Kn';
    $pageTitle = $title ?? 'Kantor PPAT Iwan Kurniawan, SH., MH., M.Kn - PPAT Resmi Kab. Muara Enim';
    $metaDescription = $description ?? 'Kantor Resmi Pejabat Pembuat Akta Tanah (PPAT) Iwan Kurniawan, SH., MH., M.Kn di Kabupaten Muara Enim. Melayani Akta Jual Beli (AJB), Hibah, Waris, APHB, APHT, dan pengurusan sertifikat tanah BPN.';
    $metaKeywords = $keywords ?? 'PPAT Muara Enim, Kantor PPAT Muara Enim, Notaris PPAT Muara Enim, PPAT Iwan Kurniawan, Akta Jual Beli Muara Enim, AJB Tanah Muara Enim, Akta Hibah Tanah, Pembagian Hak Bersama, Pembuatan Sertifikat BPN Muara Enim, Jasa PPAT Terpercaya';
    $canonicalUrl = $canonical ?? url()->current();
    $ogImage = $image ?? asset('images/hero-kantor-ppat.jpg');
    $ogType = $type ?? 'website';
@endphp

<!-- Primary SEO Meta Tags -->
<title>{{ $pageTitle }}</title>
<meta name="title" content="{{ $pageTitle }}">
<meta name="description" content="{{ $metaDescription }}">
<meta name="keywords" content="{{ $metaKeywords }}">
<meta name="author" content="PPAT Iwan Kurniawan, SH., MH., M.Kn">
<meta name="robots" content="index, follow, max-image-preview:large, max-snippet:-1, max-video-preview:-1">
<meta name="googlebot" content="index, follow, max-snippet:-1, max-image-preview:large, max-video-preview:-1">
<link rel="canonical" href="{{ $canonicalUrl }}">

<!-- Geo & Local SEO Tags (Khusus Target Pencarian: Muara Enim, Sumatera Selatan) -->
<meta name="geo.region" content="ID-SS">
<meta name="geo.placename" content="Muara Enim">
<meta name="geo.position" content="-3.6525;103.7745">
<meta name="ICBM" content="-3.6525, 103.7745">

<!-- Open Graph / Facebook / WhatsApp / Telegram Preview -->
<meta property="og:type" content="{{ $ogType }}">
<meta property="og:url" content="{{ $canonicalUrl }}">
<meta property="og:title" content="{{ $pageTitle }}">
<meta property="og:description" content="{{ $metaDescription }}">
<meta property="og:image" content="{{ $ogImage }}">
<meta property="og:image:alt" content="{{ $pageTitle }}">
<meta property="og:site_name" content="{{ $siteName }}">
<meta property="og:locale" content="id_ID">

<!-- Twitter Card -->
<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:url" content="{{ $canonicalUrl }}">
<meta name="twitter:title" content="{{ $pageTitle }}">
<meta name="twitter:description" content="{{ $metaDescription }}">
<meta name="twitter:image" content="{{ $ogImage }}">

<!-- Favicon & Touch Icon -->
<link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
<link rel="apple-touch-icon" href="{{ asset('images/logo-ppat.jpg') }}">

<!-- Schema.org JSON-LD (LocalBusiness, LegalService, WebSite, Breadcrumbs & Articles) -->
<script type="application/ld+json">
{
  "@@context": "https://schema.org",
  "@@graph": [
    {
      "@@type": ["LegalService", "LocalBusiness", "ProfessionalService"],
      "@@id": "{{ route('home') }}#organization",
      "name": "Kantor PPAT Iwan Kurniawan, SH., MH., M.Kn",
      "alternateName": ["PPAT Muara Enim Iwan Kurniawan", "Kantor Notaris & PPAT Iwan Kurniawan Muara Enim", "PPAT Iwan Kurniawan"],
      "url": "{{ route('home') }}",
      "logo": "{{ asset('images/logo-ppat.jpg') }}",
      "image": "{{ asset('images/hero-kantor-ppat.jpg') }}",
      "description": "Kantor Pejabat Pembuat Akta Tanah (PPAT) resmi Kabupaten Muara Enim berdasarkan SK Menteri ATR/BPN RI No. 37/SK-HR.03.04.PPAT/III/2023. Melayani pembuatan Akta Jual Beli (AJB), Hibah, APHB, APHT, dan pengurusan sertifikat tanah.",
      "telephone": "+6281273934303",
      "email": "Iwannotppat1@gmail.com",
      "priceRange": "$$",
      "address": {
        "@@type": "PostalAddress",
        "streetAddress": "Jl. Tembesu No. 2 RT.01/03 Pasar I Pelita Sari (Depan SMAN 1 Muara Enim)",
        "addressLocality": "Muara Enim",
        "addressRegion": "Sumatera Selatan",
        "postalCode": "31311",
        "addressCountry": "ID"
      },
      "geo": {
        "@@type": "GeoCoordinates",
        "latitude": -3.6525,
        "longitude": 103.7745
      },
      "hasMap": "https://maps.google.com/?q=Jl.+Tembesu+No.+2+Pasar+I+Pelita+Sari+Muara+Enim",
      "areaServed": {
        "@@type": "AdministrativeArea",
        "name": "Kabupaten Muara Enim"
      },
      "openingHoursSpecification": [
        {
          "@@type": "OpeningHoursSpecification",
          "dayOfWeek": ["Monday", "Tuesday", "Wednesday", "Thursday", "Friday"],
          "opens": "08:00",
          "closes": "16:30"
        }
      ],
      "founder": {
        "@@type": "Person",
        "name": "Iwan Kurniawan, SH., MH., M.Kn",
        "jobTitle": "Pejabat Pembuat Akta Tanah (PPAT)"
      }
    },
    {
      "@@type": "WebSite",
      "@@id": "{{ route('home') }}#website",
      "url": "{{ route('home') }}",
      "name": "Kantor PPAT Iwan Kurniawan Muara Enim",
      "description": "Website Resmi Kantor PPAT Iwan Kurniawan, SH., MH., M.Kn Kabupaten Muara Enim",
      "publisher": {
        "@@id": "{{ route('home') }}#organization"
      },
      "inLanguage": "id-ID"
    }
    @if(isset($breadcrumb) && is_array($breadcrumb))
    ,{
      "@@type": "BreadcrumbList",
      "itemListElement": [
        @foreach($breadcrumb as $index => $item)
        {
          "@@type": "ListItem",
          "position": {{ $index + 1 }},
          "name": "{{ $item['name'] }}",
          "item": "{{ $item['url'] }}"
        }{{ !$loop->last ? ',' : '' }}
        @endforeach
      ]
    }
    @endif
    @if(isset($articleSchema) && is_array($articleSchema))
    ,{
      "@@type": "Article",
      "headline": "{{ addslashes($articleSchema['headline']) }}",
      "description": "{{ addslashes($articleSchema['description']) }}",
      "image": "{{ $articleSchema['image'] }}",
      "datePublished": "{{ $articleSchema['datePublished'] }}",
      "dateModified": "{{ $articleSchema['dateModified'] }}",
      "mainEntityOfPage": "{{ $canonicalUrl }}",
      "author": {
        "@@type": "Person",
        "name": "{{ addslashes($articleSchema['author'] ?? 'PPAT Iwan Kurniawan, SH., MH., M.Kn') }}"
      },
      "publisher": {
        "@@id": "{{ route('home') }}#organization"
      }
    }
    @endif
  ]
}
</script>
