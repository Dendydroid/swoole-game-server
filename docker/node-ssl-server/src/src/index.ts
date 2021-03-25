import {Server} from "./server";

require('dotenv').config();

const server = new Server();

server.listen((clientPort) => {
    console.log(`Server is listening on https://127.0.0.1:${clientPort}`);
});
