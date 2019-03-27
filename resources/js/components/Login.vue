<template>
    <div>
        <div class="alert alert-danger" v-if="error">
            <p>出错了，请检查邮箱/密码是否正确</p>
        </div>
        <form autocomplete="off" @submit.prevent="login" method="post">
            <div class="form-group">
                <label for="email">邮箱</label>
                <input type="email" id="email" class="form-control" placeholder="user@example.com" v-model="email" required>
            </div>
            <div class="form-group">
                <label for="password">密码</label>
                <input type="password" id="password" class="form-control" v-model="password" required>
            </div>
            <button type="submit" class="btn btn-default">登录</button>
        </form>
    </div>
</template>