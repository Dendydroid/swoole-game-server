class SocketAuth {

    token = null;

    authenticate(socket) {
        let email = prompt("Enter your email", "taras.galatsiuk@gmail.com");
        let password = prompt("Enter your password", "123456");

        socket.send({
            route: `/auth/try`,
            data: {
                email,
                password,
            }
        });
    }

    onTokenReceive(message) {
        this.token = message.token;
    }
}