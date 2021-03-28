<!doctype html>
<html lang="zh">
<head>
    <meta charset="UTF-8">
    <title><?=$title?></title>
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
            height: 300px;
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
        .user-list .ul-head i {
            font-size: 12px;
            font-style: normal;
            color: #999;
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

    <template v-if="showingSignIn">
        <div class="login-alert">
            <dl>
                <dd>
                    <input placeholder="昵称" v-model="registrar.nickname">
                </dd>
                <dd>
                    <textarea placeholder="简介" v-model="registrar.brief"></textarea>
                </dd>
                <dd>
                    <input type="button" value="登录" @click="signIn">
                    <input type="button" value="取消" @click="showingSignIn = false">
                </dd>
            </dl>
        </div>
    </template>

    <template v-else>
        <div class="head">
            <h2>{{title}}</h2>
            <input v-if="signedIn" type="button" value="退出登录" @click="signOut">
            <input v-else type="button" value="登录" @click="showSignIn">
        </div>
        <div class="body">
            <div class="chat-body">
                <div class="chat-board">
                    <div class="message-list">
                        <dl v-for="(message) of messageList" :key="message.id" :class="{mine: message.mid === signer.mid}">
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
                        <textarea placeholder="输入聊天内容" v-model="message" @focus="signInValid('您尚未登录, 无法发送消息, 请先登录')"></textarea>
                    </div>
                    <div class="chat-control">
                        <input type="button" value="发送" @click="sendMessage">
                    </div>
                </div>
            </div>
            <div class="chat-side">
                <div class="user-list">
                    <div class="ul-head">
                        <h3>在线列表<i>(点击选择对聊)</i></h3>
                    </div>
                    <div class="ul-body">
                        <ul>
                            <li v-for="(user, key) of userList" :key="user.mid" @click="selectUser(user)" :title="user.brief">
                                {{user.nickname}}
                                <span v-if="user.signInPlaces > 1" :title="'在 ' +user.signInPlaces+ ' 地登录'">({{user.signInPlaces}})</span>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="user-info">
                    <div class="ui-head">
                        <h3>我的信息</h3>
                    </div>
                    <div class="ui-body">
                        <div class="ui-nickname">{{signer.nickname}}</div>
                        <div class="ui-brief">{{signer.brief}}</div>
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
                        brief: '欢迎使用Dce',
                    },
                    title: '群聊广场',
                    message: '',
                    messageList: [],
                    userList: [],
                    signer: {
                        mid: 0,
                        nickname: '未登录',
                        brief: '',
                    },
                    talkTarget: 0,
                    showingSignIn: 0,
                    signedIn: false,
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
                    this.loadSigner();
                    this.loadMessage(0);
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
            signIn() {
                if (this.registrar.nickname && this.registrar.brief) {
                    this.send(api.signIn, this.registrar);
                } else {
                    alert('昵称或简介不能为空');
                }
            },
            /**
             * 载入登录信息
             */
            loadSigner() {
                this.send(api.loadSigner);
            },
            /**
             * 发送消息
             */
            sendMessage() {
                if (! this.message) {
                    return alert('请输入聊天内容');
                }
                this.send(api.sendMessage, {target_mid: this.talkTarget, message: this.message});
                this.message = '';
            },
            /**
             * 载入消息
             */
            loadMessage(targetMid) {
                this.send(api.loadMessage, {target_mid: targetMid});
            },
            /**
             * 弹出登录
             */
            showSignIn() {
                this.showingSignIn = true;
            },
            /**
             * 退出
             */
            signOut() {
                this.send(api.signOut);
                this.init();
                this.loadMessage(0);
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
                const dataEncoded = this.pack(path, data ?? '');
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
             * @param user
             */
            selectUser(user) {
                if (! this.signInValid('您尚未登录, 无法发起对聊, 请先登录')) {
                    return false;
                }
                if (user.mid === this.talkTarget) {
                    this.title = '群聊广场';
                    this.talkTarget = 0;
                } else {
                    this.talkTarget = user.mid;
                    this.title = '你正在与 ' + user.nickname + ' 对聊';
                }
                this.messageList = [];
                this.loadMessage(this.talkTarget);
            },

            signInValid(tip) {
                if (! this.signedIn) {
                    alert(tip);
                    this.showSignIn();
                    return false;
                }
                return true;
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
                    [api.loadMessage] (messageList) {
                        _(messageList).each((message) => {
                            if (
                                that.talkTarget === 0 && message.target_mid > 0 // 如果在群聊界面, 则只渲染群消息
                                || (that.talkTarget > 0 && (message.mid !== that.signer.mid || message.target_mid !== that.talkTarget)
                                && (message.mid !== that.talkTarget || message.target_mid !== that.signer.mid)) // 如果在对聊界面, 则只渲染当前对话目标的消息
                            ) {
                                return false;
                            }
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
                    [api.signIn] (userList) {
                        // 直接刷新整个列表
                        that.userList = userList;
                    },
                    /**
                     * 载入登录信息
                     * @param signer
                     */
                    [api.loadSigner] (signer) {
                        that.signedIn = !! signer;
                        if (signer) {
                            // 直接刷新整个列表
                            that.signer = signer;
                            that.showingSignIn = false;
                        }
                    },
                };
                methodMapping[path].call(null, data);
            },
        }
    });

    const api = {
        signIn: 'project/im/sign_in',
        loadSigner: 'project/im/signer',
        sendMessage: 'project/im/send',
        loadMessage: 'project/im/load',
        signOut: 'project/im/sign_out',
    };
</script>

</body>
</html>
