const WebSocket = require('ws');
const express = require('express');
const http = require('http');
const app = express();
const port = 8080;

// JSON 요청을 처리할 수 있도록 설정
app.use(express.json());

// WebSocket 서버와 HTTP 서버 통합
const server = http.createServer(app);
const wss = new WebSocket.Server({ noServer: true });

// 각 클라이언트의 고유 ID를 저장할 객체
const clients = new Map();

// WebSocket 연결 설정
wss.on('connection', (ws, req) => {
    // 클라이언트로부터 첫 메시지로 특정 식별자(userId)를 수신
    ws.on('message', (message) => {
        const parsedMessage = JSON.parse(message);

        // 클라이언트를 식별할 ID 저장
        if (parsedMessage.type === 'register') {
            const token = parsedMessage.token;
            clients.set(ws, token);
            console.log(`Client with token ${token} connected`);
        }
    });

    // 클라이언트 연결 종료 시 Map에서 제거
    ws.on('close', () => {
        clients.delete(ws);
        console.log('Client disconnected');
    });
});

// HTTP 서버에서 특정 클라이언트에게만 메시지를 보냄
app.post('/send', (req, res) => {
    const { token, mobile } = req.body;

    console.log(token);

    // 해당 userId와 일치하는 클라이언트에게만 메시지를 전송
    for (let [client, id] of clients) {
        if (id === token && client.readyState === WebSocket.OPEN) {
            client.send(`Message for token ${token}: ${mobile}`);
        }
    }
});

// HTTP와 WebSocket 업그레이드 설정
server.on('upgrade', (request, socket, head) => {
    wss.handleUpgrade(request, socket, head, (ws) => {
        wss.emit('connection', ws, request);
    });
});

// 서버 실행
server.listen(port, () => {
    console.log(`Server is running on http://localhost:${port}`);
});