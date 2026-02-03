@extends('layouts.home')
@section('header')
<style>
    /* 现代化文章归档页样式 */
    .archive-container {
        background: #fff;
        border-radius: 12px;
        box-shadow: 0 2px 12px rgba(0,0,0,0.06);
        padding: 30px;
        margin-top: 20px;
    }

    .archive-header {
        text-align: center;
        margin-bottom: 40px;
        padding-bottom: 20px;
        border-bottom: 2px solid #f0f0f0;
    }

    .archive-header h2 {
        font-size: 24px;
        font-weight: 600;
        color: #333;
        margin-bottom: 8px;
    }

    .archive-header p {
        color: #999;
        font-size: 14px;
    }

    .archive-header .count {
        display: inline-block;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: #fff;
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 13px;
        margin-left: 10px;
    }

    /* 时间线样式 */
    .timeline {
        position: relative;
        padding-left: 30px;
    }

    .timeline:before {
        content: '';
        position: absolute;
        left: 0;
        top: 0;
        bottom: 0;
        width: 3px;
        background: linear-gradient(180deg, #667eea 0%, #764ba2 50%, #e0e0e0 100%);
        border-radius: 3px;
    }

    .timeline-year {
        position: relative;
        margin-bottom: 30px;
    }

    .timeline-year-header {
        display: flex;
        align-items: center;
        margin-bottom: 20px;
        cursor: pointer;
        user-select: none;
    }

    .timeline-year-header:hover .year-badge {
        transform: scale(1.05);
        box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
    }

    .year-badge {
        position: absolute;
        left: -38px;
        top: 50%;
        transform: translateY(-50%);
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: #fff;
        width: 50px;
        height: 50px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 600;
        font-size: 13px;
        box-shadow: 0 2px 8px rgba(102, 126, 234, 0.3);
        transition: all 0.3s ease;
        z-index: 1;
    }

    .year-title {
        font-size: 18px;
        font-weight: 600;
        color: #333;
        margin-left: 20px;
        display: flex;
        align-items: center;
    }

    .year-title .article-count {
        background: #f0f0f0;
        color: #666;
        padding: 2px 10px;
        border-radius: 12px;
        font-size: 12px;
        margin-left: 10px;
    }

    .year-title .toggle-icon {
        margin-left: 10px;
        font-size: 16px;
        color: #999;
        transition: transform 0.3s ease;
    }

    .timeline-year.collapsed .toggle-icon {
        transform: rotate(-90deg);
    }

    .timeline-year.collapsed .timeline-articles {
        display: none;
    }

    /* 文章列表 */
    .timeline-articles {
        transition: all 0.3s ease;
    }

    .archive-article {
        display: flex;
        align-items: center;
        padding: 15px 20px;
        margin-bottom: 12px;
        background: #f8f9fa;
        border-radius: 10px;
        transition: all 0.3s ease;
        cursor: pointer;
        position: relative;
        overflow: hidden;
    }

    .archive-article:before {
        content: '';
        position: absolute;
        left: 0;
        top: 0;
        bottom: 0;
        width: 4px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        opacity: 0;
        transition: opacity 0.3s ease;
    }

    .archive-article:hover {
        background: #fff;
        box-shadow: 0 4px 16px rgba(0,0,0,0.1);
        transform: translateX(5px);
    }

    .archive-article:hover:before {
        opacity: 1;
    }

    .article-date {
        flex-shrink: 0;
        text-align: center;
        margin-right: 20px;
        min-width: 60px;
    }

    .article-date .day {
        font-size: 22px;
        font-weight: 700;
        color: #667eea;
        line-height: 1;
    }

    .article-date .month {
        font-size: 12px;
        color: #999;
        text-transform: uppercase;
    }

    .article-info {
        flex: 1;
        min-width: 0;
    }

    .article-title {
        font-size: 15px;
        font-weight: 500;
        color: #333;
        margin-bottom: 6px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        transition: color 0.3s ease;
    }

    .archive-article:hover .article-title {
        color: #667eea;
    }

    .article-meta {
        display: flex;
        align-items: center;
        gap: 15px;
        font-size: 12px;
        color: #999;
    }

    .article-meta span {
        display: flex;
        align-items: center;
        gap: 4px;
    }

    .article-meta .view-count:before {
        content: '👁';
    }

    .article-meta .category-tag {
        background: #e8f0fe;
        color: #667eea;
        padding: 2px 8px;
        border-radius: 4px;
    }

    /* 统计卡片 */
    .archive-stats {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 15px;
        margin-bottom: 30px;
    }

    .stat-card {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 10px;
        padding: 20px;
        text-align: center;
        color: #fff;
        transition: transform 0.3s ease;
    }

    .stat-card:nth-child(2) {
        background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
    }

    .stat-card:nth-child(3) {
        background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
    }

    .stat-card:hover {
        transform: translateY(-5px);
    }

    .stat-card .number {
        font-size: 28px;
        font-weight: 700;
        margin-bottom: 5px;
    }

    .stat-card .label {
        font-size: 13px;
        opacity: 0.9;
    }

    /* 空状态 */
    .archive-empty {
        text-align: center;
        padding: 60px 20px;
        color: #999;
    }

    .archive-empty .icon {
        font-size: 60px;
        margin-bottom: 20px;
        opacity: 0.5;
    }

    /* 移动端适配 */
    @media only screen and (max-width: 767px) {
        .archive-container {
            padding: 20px 15px;
            border-radius: 8px;
        }

        .archive-header h2 {
            font-size: 20px;
        }

        .timeline {
            padding-left: 25px;
        }

        .year-badge {
            width: 40px;
            height: 40px;
            left: -32px;
            font-size: 11px;
        }

        .archive-article {
            padding: 12px 15px;
        }

        .article-date {
            min-width: 50px;
            margin-right: 15px;
        }

        .article-date .day {
            font-size: 18px;
        }

        .article-title {
            font-size: 14px;
        }

        .archive-stats {
            grid-template-columns: 1fr;
            gap: 10px;
        }

        .stat-card {
            padding: 15px;
        }

        .stat-card .number {
            font-size: 24px;
        }
    }
</style>
@endsection
@section('title', '文章归档 | '.\App\Models\SystemConfig::getConfigCache('seo_title'))
@section('content')
<div style="width: 100%;height: 76px;"></div>
<article>
    <h1 class="t_nav"><span>文章归档</span></h1>
    <div class="blogs">
        <div class="archive-container">
            <!-- 统计卡片 -->
            <div class="archive-stats">
                @php
                    $totalArticles = 0;
                    foreach($data as $item) {
                        $totalArticles += count($item['art'] ?? []);
                    }
                @endphp
                <div class="stat-card">
                    <div class="number">{{ $totalArticles }}</div>
                    <div class="label">篇文章</div>
                </div>
                <div class="stat-card">
                    <div class="number">{{ count($data) }}</div>
                    <div class="label">个归档</div>
                </div>
                <div class="stat-card">
                    <div class="number">📚</div>
                    <div class="label">知识沉淀</div>
                </div>
            </div>

            <!-- 时间线 -->
            <div class="timeline">
                @foreach($data as $item)
                @php
                    // 提取年份数字或首字
                    $yearText = $item['name'] ?? '';
                    if (preg_match('/\d{4}/', $yearText, $matches)) {
                        $yearDisplay = $matches[0];
                    } else {
                        // 如果没有年份数字，显示前两个中文字符或首字母
                        $yearDisplay = mb_substr($yearText, 0, 2, 'UTF-8');
                    }
                @endphp
                <div class="timeline-year">
                    <div class="timeline-year-header" onclick="toggleYear(this)">
                        <div class="year-badge" style="{{ !preg_match('/\d{4}/', $item['name'] ?? '') ? 'font-size: 14px;' : '' }}">{{ $yearDisplay }}</div>
                        <div class="year-title">
                            {{ $item['name'] }}
                            <span class="article-count">{{ count($item['art'] ?? []) }} 篇</span>
                            <span class="toggle-icon">▼</span>
                        </div>
                    </div>
                    <div class="timeline-articles">
                        @foreach($item['art'] as $art)
                        <a href="/article/{{ $art['id'] }}" class="archive-article">
                            <div class="article-date">
                                <div class="day">{{ isset($art['created_at']) ? \Carbon\Carbon::parse($art['created_at'])->format('d') : '01' }}</div>
                                <div class="month">{{ isset($art['created_at']) ? \Carbon\Carbon::parse($art['created_at'])->format('m') : '01' }}</div>
                            </div>
                            <div class="article-info">
                                <div class="article-title">{{ $art['title'] }}</div>
                                <div class="article-meta">
                                    <span class="view-count">{{ $art['click'] ?? 0 }}</span>
                                    @if(isset($art['category_name']))
                                    <span class="category-tag">{{ $art['category_name'] }}</span>
                                    @endif
                                </div>
                            </div>
                        </a>
                        @endforeach
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
    <div class="sidebar">
        @component('./layouts/home/search')
        @endcomponent
        @component('./layouts/home/ad')
        @endcomponent
        @component('./layouts/home/hot')
        @endcomponent
        @component('./layouts/home/tag')
        @endcomponent
        @component('./layouts/home/publicwx')
        @endcomponent
    </div>
</article>
@endsection
@section('script')
@parent
<script>
    // 折叠/展开年份
    function toggleYear(element) {
        const yearSection = element.closest('.timeline-year');
        yearSection.classList.toggle('collapsed');
    }

    $(function() {
        // 添加入场动画
        $('.archive-article').each(function(index) {
            $(this).css('opacity', '0').css('transform', 'translateY(20px)');
            setTimeout(() => {
                $(this).css('transition', 'all 0.5s ease').css('opacity', '1').css('transform', 'translateY(0)');
            }, index * 50);
        });

        // 统计数字动画
        $('.stat-card .number').each(function() {
            const $this = $(this);
            const target = parseInt($this.text());
            if (!isNaN(target)) {
                $({ count: 0 }).animate(
                    { count: target },
                    {
                        duration: 1500,
                        easing: 'swing',
                        step: function() {
                            $this.text(Math.floor(this.count));
                        },
                        complete: function() {
                            $this.text(target);
                        }
                    }
                );
            }
        });
    });
</script>
@endsection