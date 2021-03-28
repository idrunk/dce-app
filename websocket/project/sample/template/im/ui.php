<?php
/**
 * @var $host string
 * @var $port int
 */
?>
<!doctype html>
<html lang="zh">
<head>
    <meta charset="UTF-8">
    <title>Dce Websocket Sample</title>
    <script src="/static/plugins/lodash/lodash.min.js"></script>
    <script src="/static/plugins/jquery/jquery.min.js"></script>
    <script src="/static/plugins/vue/vue.min.js"></script>
    <style type="text/css">
        * {
            box-sizing: border-box;
        }
        body {
            padding: 10px;
            font-size: 14px;
        }
        .login-alert {
            width: 500px;
            height: 275px;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-shadow: 1px 3px 4px #ccc;
            margin: 10% auto 0;
            padding-top: 30px;
        }
        .login-alert dd {
            margin: 0;
        }
        .login-alert input, .login-alert textarea {
            display: block;
            border: 1px solid #ccc;
            border-radius: 4px;
            margin: 20px auto 0;
            width: 60%;
            height: 30px;
            padding: 3px 5px;
        }
        .login-alert textarea {
            height: 52px;
            resize: none;
        }
        .head {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .body {
            height: 80vh;
            display: flex;
        }
        .chat-body {
            flex: auto;
            display: flex;
            flex-flow: column;
        }
        .chat-side {
            width: 200px;
            margin-left: 10px;
            flex: 0 0 200px;
            display: flex;
            flex-flow: column;
        }
        .chat-board {
            flex: auto;
            border: 1px solid #ccc;
            border-radius: 5px 5px 0 0;
            overflow-y: auto;
        }
        .message-list dl {
            margin: 15px 0;
        }
        .message-list dl.mine dt {
            flex-direction:row-reverse;
        }
        .message-list dl.mine dd {
            display: flex;
            flex-direction:row-reverse;
        }
        .message-list dt {
            display: flex;
            margin: 10px;
        }
        .message-list dt span {
            margin: 0 5px;
        }
        .message-list dd {
            margin: 0 10px 10px;
            padding: 0 5px;
        }
        .message-list dd p {
            display: inline-block;
            border: 1px solid #eee;
            border-radius: 5px;
            padding: 3px 10px;
            line-height: 26px;
            margin: 0;
        }
        .chat-form {
            flex: 0 0 100px;
            border: 1px solid #ccc;
            border-radius: 0 0 5px 5px;
            border-top: 0;
        }
        .chat-input {
            margin: 0 5px;
            padding: 5px 0;
            position: relative;
        }
        .chat-input textarea {
            width: 100%;
            height: 50px;
            border-radius: 5px;
            resize: none;
        }
        .chat-control {
            padding: 0 10px;
            text-align: right;
        }
        .user-list {
            flex: auto;
            border: 1px solid #ccc;
            border-radius: 5px 5px 0 0;
            overflow-y: auto;
        }
        .ul-head, .ui-head {
            border-bottom: 1px solid #ccc;
        }
        .user-list ul {
            margin: 5px;
        }
        .chat-side h3 {
            margin: 0 10px;
            padding: 5px 0;
        }
        .user-info {
            flex: 0 0 100px;
            border: 1px solid #ccc;
            border-radius: 0 0 5px 5px;
            border-top: 0;
            overflow: hidden;
        }
        .ui-body {
            overflow: hidden;
            padding: 5px 8px;
        }
        .ui-nickname {
            font-weight: bold;
        }
    </style>
</head>
<body>
<div id="app">

<template v-if="! isLogged">
<div class="login-alert">
    <dl>
        <dd>
            <input placeholder="昵称" v-model="registrar.nickname">
        </dd>
        <dd>
            <textarea placeholder="简介" v-model="registrar.brief"></textarea>
        </dd>
        <dd>
            <input type="button" value="登录" @click="login">
        </dd>
    </dl>
</div>
</template>

<template v-else>
<div class="head">
    <h1>Dce Websocket Sample</h1>
    <input type="button" value="退出登录" @click="logout">
</div>
<div class="body">
    <div class="chat-body">
        <div class="chat-board">
            <div class="message-list">
                <dl v-for="(message) of messageList" :key="message.id" :class="{mine: message.nickname === registrar.nickname}">
                    <dt>
                        <span>{{message.nickname}}</span>
                        <span>{{message.create_time}}</span>
                    </dt>
                    <dd>
                        <p>{{message.message}}</p>
                    </dd>
                </dl>
            </div>
        </div>
        <div class="chat-form">
            <div class="chat-input">
                <textarea placeholder="输入聊天内容" v-model="message"></textarea>
            </div>
            <div class="chat-control">
                <input type="button" value="发送" @click="sendMessage">
            </div>
        </div>
    </div>
    <div class="chat-side">
        <div class="user-list">
            <div class="ul-head">
                <h3>在线列表</h3>
            </div>
            <div class="ul-body">
                <ul>
                    <li v-for="(user, key) of userList" :key="key" @click="selectUser(key)" :title="user.brief">{{user.nickname}}</li>
                </ul>
            </div>
        </div>
        <div class="user-info">
            <div class="ui-head">
                <h3>用户信息</h3>
            </div>
            <div class="ui-body">
                <div class="ui-nickname">{{currentUser.nickname}}</div>
                <div class="ui-brief">{{currentUser.brief}}</div>
            </div>
        </div>
    </div>
</div>
</template>

</div>

<script>
new Vue({
    el: '#app',
    data () {
        return Object.assign({
            ws: null,
        }, this.init());
    },
    mounted() {
        this.init();
        this.connect();
    },
    methods: {
        /**
         * 初始化Vue数据
         */
        init() {
            const initData = {
                registrar: {
                    nickname: '',
                    brief: 'Never drink but always Drunk ~',
                },
                message: '',
                messageList: [],
                userList: [],
                currentUser: {
                    nickname: '',
                    brief: '',
                },
                isLogged: 0,
            };
            _(initData).each((value, key) => {
                this[key] = value;
            });
            return initData;
        },
        /**
         * 建立Websocket连接
         */
        connect() {
            const ws = new WebSocket('ws://<?=$host?>:<?=$port?>');
            ws.onopen = () => {
                this.ws = ws;
            };
            ws.onmessage = (evt) => {
                const data = evt.data;
                // 解包数据并转处理
                this.handleMessage(this.unpack(data));
            };
            ws.onclose = () => {
                // 初始化Vue数据
                this.init();
                if (this.ws) {
                    this.ws = null;
                    alert('连接已断开');
                }
            };
        },
        /**
         * 登录
         */
        login() {
            if (this.registrar.nickname && this.registrar.brief) {
                this.send(api.login, this.registrar);
                if (this.ws) {
                    this.isLogged = 1;
                    this.currentUser = this.registrar;
                }
            } else {
                alert('昵称或简介不能为空');
            }
        },
        /**
         * 发送消息
         */
        sendMessage() {
            if (! this.message) {
                return alert('请输入聊天内容');
            }
            this.send(api.sendMessage, {message: this.message});
            this.message = '';
        },
        /**
         * 退出
         */
        logout() {
            this.send(api.logout);
            this.init();
        },
        /**
         * 发送Websocket消息
         * @param path
         * @param data
         */
        send(path, data) {
            if (! this.ws) {
                return alert('连接尚未建立');
            }
            const dataEncoded = this.pack(path, data);
            this.ws.send(dataEncoded);
        },
        /**
         * Websocket消息打包
         * @param path
         * @param data
         * @returns {string}
         */
        pack(path, data) {
            return path + '\n' + JSON.stringify(data);
        },
        /**
         * Websocket消息解包
         * @param data
         * @returns {{path: string, data: *}}
         */
        unpack(data) {
            let semicolonIndex = data.indexOf('\n');
            if (-1 === semicolonIndex) {
                semicolonIndex = data.length;
            }
            const path = data.substr(0, semicolonIndex);
            data = JSON.parse(data.substr(semicolonIndex + 1));
            return {path, data};
        },
        /**
         * 选中在线用户回调
         * @param key
         */
        selectUser(key) {
            this.currentUser = this.userList[key];
        },

        /**
         * 处理接收的消息
         * @param path
         * @param data
         */
        handleMessage({path, data}) {
            const that = this;
            const methodMapping = {
                /**
                 * 更新消息列表
                 * @param messageList
                 */
                setMessageList (messageList) {
                    _(messageList).each((message) => {
                        // 追加消息
                        that.messageList.push(message);
                    });
                    _.delay(() => {
                        // 延迟调整滚动位置
                        const chatBoard = $('.chat-board')[0];
                        if (chatBoard && chatBoard.scrollHeight) {
                            chatBoard.scrollTop = chatBoard.scrollHeight - chatBoard.clientHeight;
                        }
                    }, 100);
                },
                /**
                 * 更新在线列表
                 * @param userList
                 */
                setUserList (userList) {
                    // 直接刷新整个列表
                    that.userList = userList;
                }
            };
            methodMapping[path].call(null, data);
        },
    }
});

const api = {
    login: 'sample/im/login',
    sendMessage: 'sample/im/send',
    logout: 'sample/im/logout',
};
</script>

</body>
</html>
