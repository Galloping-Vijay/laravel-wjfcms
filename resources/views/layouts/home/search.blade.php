<div class="search">
    <form action="/" method="post">
        <input name="keytitle" class="input_text" value="" style="color: rgb(153, 153, 153);" placeholder="请输入搜索关键词" type="text">
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        <input name="Submit" class="input_submit" value="搜索" type="submit">
    </form>
</div>