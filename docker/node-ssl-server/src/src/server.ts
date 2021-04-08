import express, {Application} from "express";
import {createServer, Server as HTTPServer} from "https";
import fs from 'fs';
import path from "path";

export class Server {

    readonly clientHttpServer: HTTPServer;
    readonly credentials: { key: string, cert: string };
    readonly clientServer: Application;
    private settings: any;

    constructor() {
        this.credentials = {
            key: fs.readFileSync(process.env.CERT_KEY_PATH, 'utf8'),
            cert: fs.readFileSync(process.env.CERT_PATH, 'utf8')
        };

        this.clientServer = express();

        this.clientHttpServer = createServer(this.credentials, this.clientServer);

        this.getSettings();
        this.configureRoutes();
    }

    private configureRoutes(): void {

        this.clientServer.use(express.static(path.join(__dirname, "../public")));

        this.clientServer.get("/", (req, res) => {
            res.sendFile("index.html");
        });

        this.clientServer.get("/debug", (req, res) => {
            res.sendFile(path.join(__dirname, "../public/debug.html"));
        });

    }

    private getSettings(): void {

        this.settings = {
            SERVER_PORT: process.env.SERVER_PORT,
        };
    }

    // STARTING HTTP SERVER
    public listen(callback: (clientPort: number) => void): void {

        this.clientHttpServer.listen(this.settings.SERVER_PORT, () => {
        });

        callback(this.settings.SERVER_PORT);
    }
}
