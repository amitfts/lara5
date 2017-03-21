{!! $xmlStart !!}
<urlset xmlns="http://www.google.com/schemas/sitemap/0.84"
        xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
        xsi:schemaLocation="http://www.google.com/schemas/sitemap/0.84
        http://www.google.com/schemas/sitemap/0.84/sitemap.xsd">
    <url>
        <loc>http://www.sameroute.in/</loc>
        <lastmod>{{$homepage_last_modified}}</lastmod>
        <changefreq>daily</changefreq>
        <priority>1</priority>
    </url>
    {!! $locations !!}
    <url>
        <loc>http://www.sameroute.in/contact-us</loc>
        <lastmod>2017-03-21</lastmod>
        <changefreq>yearly</changefreq>
        <priority>0.2</priority>
    </url> 
    <url>
        <loc>http://www.sameroute.in/auth/login</loc>
        <lastmod>2017-03-21</lastmod>
        <changefreq>yearly</changefreq>
        <priority>0.2</priority>
    </url> 
    <url>
        <loc>http://www.sameroute.in/auth/register</loc>
        <lastmod>2017-03-21</lastmod>
        <changefreq>yearly</changefreq>
        <priority>0.2</priority>
    </url> 
</urlset>