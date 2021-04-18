
class Socket {
    /** @type WebSocket */
    connection = null;

    last_receive_message = null;
    last_send_message = null;

    /** @type function(message) */
    onMessageCallback = null;

    /** @type function() */
    onOpenCallback = null;

    static auth = new SocketAuth();

    constructor(onOpenCallback, onMessageCallback) {
        this.connection = new ReconnectingWebSocket('ws://127.0.0.1:8443/');

        this.onOpenCallback = onOpenCallback;
        this.onMessageCallback = onMessageCallback;

        this.connection.onopen = (event) => {
            this.onOpenCallback();
            // Socket.auth.authenticate(this);
        };
        this.connection.onclose = (event) => {

        };
        this.connection.onmessage = (event) => {
            this.last_receive_message = JSON.parse(event.data);
            if (this.last_receive_message.token) {
                Socket.auth.onTokenReceive(this.last_receive_message);
            }
            this.onMessageCallback(this.last_receive_message);
        };
        this.connection.onerror = (event) => {
            this.connection.close();
        };
    }

    send(data) {
        this.last_send_message = data;
        this.last_send_message.token = Socket.auth.token;
        console.log('sending', this.last_send_message);
        this.connection.send(JSON.stringify(this.last_send_message));
    }
}