const { ipcRenderer } = require('electron');

// 5초 후 창 크기 변경 요청
setTimeout(() => {
    ipcRenderer.send('resize-window', 500, 900);  // (width, height)
}, 2000);
var _app = {};
$(document).ready(function() {

    _app = {
        // socket: new WebSocket('ws://localhost:8080'),
        init: function() {
            _app.eventHandler();
        },
        eventHandler: function() {
            // _app.openWebSocket();
            // _app.closeWebSocket();
            // _app.getWebSocketMessage();
            // delete _app.openWebSocket;

            $(".waiting").on("click", _app.viewPointPage);
        },
        openWebSocket: function() {
            _app.socket.onopen = function() {
                console.log("Connected to WebSocket server");
                const registerMessage = JSON.stringify({ type: 'register', token: '8a5106ea1541c6ca81ef5e6a9c60681d' });
                _app.socket.send(registerMessage);
            };
        },
        closeWebSocket: function() {
            _app.socket.onclose = function() {
                console.log("Disconnected from WebSocket server");
            };
        },
        getWebSocketMessage: function() {
            _app.socket.onmessage = function(event) {
                console.log("test");
            };
        },
        viewPointPage: function() {
            ipcRenderer.send('resize-window', 500, 900);
        },
        viewWaitingPage: function() {

        }
    }
    _app.init();
});