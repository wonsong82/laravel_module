const app = require('express')()
const http = require('http').Server(app)
const io = require('socket.io')(http)
const Redis = require('ioredis')
const redis = new Redis()

redis.subscribe('app-channel', (err, count) => {})


redis.on('message', (channel, message) => {
    message = JSON.parse(message)
    io.emit(`${channel}:${message.event}`, message.data)
})


http.listen(3000, () => {
    console.log('Listening on Port 3000')
})