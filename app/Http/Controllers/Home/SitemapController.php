<?php

namespace App\Http\Controllers\Home;

use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\Category;
use App\Models\Tag;
use Carbon\Carbon;
use Illuminate\Support\Facades\Response;

/**
 * SEO Sitemap 控制器
 */
class SitemapController extends Controller
{
    /**
     * 生成 sitemap.xml
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $baseUrl = config('app.url');

        // 设置响应头
        $response = Response::make()->header('Content-Type', 'application/xml; charset=UTF-8');

        // 开始XML
        $this->startXml();

        // 添加静态页面
        $this->addUrl($baseUrl, '1.0', 'daily', '2024-01-01');
        $this->addUrl($baseUrl . '/category', '0.8', 'weekly', '2024-01-01');

        // 添加分类页面
        $categories = Category::query()->select('id', 'updated_at')->get();
        foreach ($categories as $category) {
            $this->addUrl(
                $baseUrl . '/category/' . $category->id,
                '0.8',
                'weekly',
                $category->updated_at ? $category->updated_at->format('Y-m-d') : date('Y-m-d')
            );
        }

        // 添加文章页面（只包含已发布的）
        $articles = Article::query()
            ->where('status', 1)
            ->select('id', 'updated_at', 'created_at')
            ->orderBy('created_at', 'desc')
            ->get();

        foreach ($articles as $article) {
            $this->addUrl(
                $baseUrl . '/article/' . $article->id,
                '0.9',
                'weekly',
                $article->updated_at ? $article->updated_at->format('Y-m-d') : $article->created_at->format('Y-m-d')
            );
        }

        // 添加标签页面
        $tags = Tag::query()->select('id', 'updated_at')->get();
        foreach ($tags as $tag) {
            $this->addUrl(
                $baseUrl . '/tag/' . $tag->id,
                '0.6',
                'monthly',
                $tag->updated_at ? $tag->updated_at->format('Y-m-d') : date('Y-m-d')
            );
        }

        // 结束XML
        $this->endXml();

        return $response;
    }

    /**
     * XML 开始标签
     */
    private function startXml()
    {
        echo '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
        echo '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"' . "\n";
        return '';
    }

    /**
     * XML 结束标签
     */
    private function endXml()
    {
        return '</urlset>';
    }

    /**
     * 添加 URL 到 sitemap
     *
     * @param string $loc
     * @param string $priority
     * @param string $changefreq
     * @param string $lastmod
     * @return string
     */
    private function addUrl($loc, $priority = '0.5', $changefreq = 'monthly', $lastmod = '')
    {
        echo "  <url>\n";
        echo "    <loc>{$loc}</loc>\n";
        if ($lastmod) {
            echo "    <lastmod>{$lastmod}</lastmod>\n";
        }
        echo "    <changefreq>{$changefreq}</changefreq>\n";
        echo "    <priority>{$priority}</priority>\n";
        echo "  </url>\n";
        return '';
    }
}
