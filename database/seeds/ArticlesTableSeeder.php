<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ArticlesTableSeeder extends Seeder
{
    /**
     * Description:
     * User: Vijay
     * Date: 2019/6/25
     * Time: 21:08
     */
    public function run()
    {
        $data = [
            [
                'category_id' => 1,
                'title' => '记录生活，记录成长',
                'author' => 'Vijay',
                'slug' => 'php',
                'content' => '&lt;p&gt;&lt;/p&gt;&lt;div class=&quot;about_text text_about&quot;&gt;
                &lt;h1&gt;记录生活，记录成长。&lt;/h1&gt;
                &lt;p&gt;时间过得太快，不去留意，不去记录，不去总结。只会使人活得麻木和安逸，等哪一天你想回忆时，你会发现，回忆是那么模糊...&lt;/p&gt;&lt;p&gt;每一份经历，当你把每一次成长，每一段时光都值得被记录，它们将会是你未来的财富。&lt;/p&gt;
            &lt;/div&gt;&lt;p&gt;&lt;/p&gt;',
                'description' => '时间过得太快，不去留意，不去记录，不去总结。只会使人活得麻木和安逸，等哪一天你想回忆时，你会发现，回忆是那么模糊...每一份经历，当你把每一次成长，每一段时光都值得被记录，它们将会是你未来的财富。',
                'keywords' => 'laravel',
                'cover' => '',
                'is_top' => 1,
                'status' => 1,
                'click' => 666,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
                'deleted_at' => null,
            ]
        ];
        DB::table('articles')->insert($data);
    }
}
